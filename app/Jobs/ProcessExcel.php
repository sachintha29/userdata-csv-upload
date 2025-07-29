<?php



namespace App\Jobs;

use App\Models\UserData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $chunkSize = 1000;

    /**
     * The path to the Excel file in storage
     */
    public function __construct(protected string $storagePath)
    {
        // Increase memory limit for large files
        ini_set('memory_limit', '512M');
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if (!Storage::disk('public')->exists($this->storagePath)) {
            \Log::error("Excel file not found at path: {$this->storagePath}");
            return;
        }

        $fullPath = Storage::disk('public')->path($this->storagePath);

        try {
            $this->processExcelFile($fullPath);
            Storage::disk('public')->delete($this->storagePath);

        } catch (\Exception $e) {
            \Log::error("Excel processing failed: " . $e->getMessage());
            throw $e;
        }
    }

    protected function processExcelFile(string $filePath)
    {
        $reader = SimpleExcelReader::create($filePath)
            ->useDelimiter(',')
            ->useHeaders(['Name', 'Email', 'Contact Number', 'Address', 'Birthday']);

        $reader->getRows()
            ->chunk($this->chunkSize)
            ->each(function (LazyCollection $chunk) {
                $this->processChunk($chunk);
            });
    }

    protected function processChunk(LazyCollection $chunk)
    {
        $data = $chunk->map(function (array $row) {
            return [
                'name' => $this->cleanInput($row['Name'] ?? ''),
                'email' => $this->cleanInput($row['Email'] ?? ''),
                'contact_number' => $this->cleanInput($row['Contact Number'] ?? ''),
                'address' => $this->cleanInput($row['Address'] ?? ''),
                'birthday' => $this->parseDate($row['Birthday'] ?? null),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        DB::transaction(function () use ($data) {
            DB::table('user_data')->insert($data);
        });
    }

    protected function parseDate($date)
    {
        try {
            return $date ? Carbon::parse($date) : null;
        } catch (\Exception $e) {
            \Log::warning("Failed to parse date: {$date}");
            return null;
        }
    }

    protected function cleanInput(string $value): string
    {
        return trim(strip_tags($value));
    }

    public function failed(\Exception $e)
    {
        \Log::error("Excel processing job failed: " . $e->getMessage());

        // Optional: Notify admin or mark job as failed in database
    }
}
