<?php

namespace Database\Seeders;

use App\Models\Motion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $motions = [
            ['type' => 'Motion for Extension of Time','description' => '','created_by' => '2'],
            ['type' => 'Motion to Withdraw','description' => '','created_by' => '2'],
            ['type' => 'Motion for Summary Judgment','description' => '','created_by' => '2'],
            ['type' => 'Motion to Compel Discovery','description' => '','created_by' => '2'],
            ['type' => 'Motion for Protective Order','description' => '','created_by' => '2'],
            ['type' => 'Motion to Dismiss','description' => '','created_by' => '2'],
            ['type' => 'Motion for Preliminary Injunction','description' => '','created_by' => '2'],
            ['type' => 'Motion for Class Certification','description' => '','created_by' => '2'],
            ['type' => 'Motion for Sanctions','description' => '','created_by' => '2'],
            ['type' => 'Motion for Settlement Approval','description' => '','created_by' => '2'],
            ['type' => 'Motion for Continuance','description' => '','created_by' => '2'],
            ['type' => 'Motion for Temporary Restraining Order','description' => '','created_by' => '2'],
            ['type' => 'Motion for Discovery','description' => '','created_by' => '2'],
            ['type' => 'Motion for Protective Order','description' => '','created_by' => '2'],
            ['type' => 'Motion for Leave to Amend','description' => '','created_by' => '2'],
            ['type' => 'Motion for Default Judgment','description' => '','created_by' => '2'],
            ['type' => 'Motion in Limine','description' => '','created_by' => '2'],
            ['type' => 'Motion for Change of Venue','description' => '','created_by' => '2'],
            ['type' => 'Motion for Joinder','description' => '','created_by' => '2'],
            ['type' => 'Motion for Recusal','description' => '','created_by' => '2'],
            ['type' => 'Motion for Relief from Judgment','description' => '','created_by' => '2'],
            ['type' => 'Motion to Intervene','description' => '','created_by' => '2'],
            ['type' => 'Motion for Reconsideration','description' => '','created_by' => '2'],
            ['type' => 'Motion for Replevin','description' => '','created_by' => '2'],
            ['type' => 'Motion to Quash','description' => '','created_by' => '2'],
            ['type' => 'Motion for Equitable Relief','description' => '','created_by' => '2'],
            ['type' => 'Motion for Disqualification','description' => '','created_by' => '2'],
            ['type' => 'Motion for Expert Witness Disclosure','description' => '','created_by' => '2'],
            ['type' => 'Motion for Severance','description' => '','created_by' => '2'],
            ['type' => 'Motion for Rehabilitation','description' => '','created_by' => '2'],
            ['type' => 'Motion for Substitution of Counsel','description' => '','created_by' => '2'],
            ['type' => 'Motion for Judicial Notice','description' => '','created_by' => '2'],
        ];

        Motion::insert($motions);
    }
}
