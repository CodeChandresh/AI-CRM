<?php

// File: database/seeders/DealStageSeeder.php

namespace Database\Seeders;

use App\Models\DealStage;
use Illuminate\Database\Seeder;

class DealStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the default pipeline stages
        $stages = [
            [
                'name' => 'Prospecting',
                'description' => 'Initial contact with the lead',
                'order' => 1,
            ],
            [
                'name' => 'Qualification',
                'description' => 'Assessing the lead\'s interest and fit',
                'order' => 2,
            ],
            [
                'name' => 'Needs Analysis',
                'description' => 'Understanding the lead\'s needs and pain points',
                'order' => 3,
            ],
            [
                'name' => 'Proposal/Presentation',
                'description' => 'Presenting the solution to the lead',
                'order' => 4,
            ],
            [
                'name' => 'Negotiation',
                'description' => 'Finalizing the deal',
                'order' => 5,
            ],
            [
                'name' => 'Closed Won',
                'description' => 'The deal has been closed and won',
                'order' => 6,
            ],
            [
                'name' => 'Closed Lost',
                'description' => 'The deal has been closed and lost',
                'order' => 7,
            ],
        ];

        // Insert the default pipeline stages into the database
        foreach ($stages as $stage) {
            DealStage::create($stage);
        }
    }
}