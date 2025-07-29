<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Writer;
use Faker\Factory as Faker;

class GenerateSampleCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:generate {rows=50000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
     public function handle()
    {
        $rows = (int)$this->argument('rows');
        $faker = Faker::create();

        $csv = Writer::createFromPath(storage_path('app/sample_users.csv'), 'w+');

        $csv->insertOne(['Name', 'Email', 'Contact Number', 'Address', 'Birthday']);

        $this->output->progressStart($rows);

        for ($i = 0; $i < $rows; $i++) {
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $email = strtolower($firstName . '.' . $lastName . '@' . $faker->safeEmailDomain);
            $phone = $faker->numerify('+094#########');

            $csv->insertOne([
                $firstName . ' ' . $lastName,
                $email,
                $phone,
                $faker->buildingNumber . ' ' . $faker->streetName . ', ' .
                $faker->city . ', ' . $faker->stateAbbr . ' ' . $faker->postcode,

                $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            ]);

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("Sample CSV with {$rows} records  " . storage_path('app/sample_users.csv'));
    }
}
