<?php

namespace App\Jobs;

use App\Models\UserData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;


class ProcessCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;


    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
          $csv = Reader::createFromPath($this->filePath, 'r');
        $csv->setHeaderOffset(0);

    foreach ($csv->getRecords() as $record) {
            try {
                UserData::create([
                    'name' => $record['Name'] ?? null,
                    'email' => $record['Email'] ?? null,
                    'contact_number' => $record['Contact Number'] ?? null,
                    'address' => $record['Address'] ?? null,
                    'birthday' => $record['Birthday'] ?? null,
                ]);
            } catch (\Exception $e) {
                // Log error and continue processing
                \Log::error("Failed to process record: " . json_encode($record) . " - " . $e->getMessage());
                continue;
            }
        }

        // Cleanup
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }
}
