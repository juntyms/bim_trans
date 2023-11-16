<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('status')->delete();
        Status::create(['name'=>'Paid','description'=>'When the transaction is fully paid']);
        Status::create(['name'=>'Outstanding','description'=>'When transaction is not fully paid and part and due on date has not
passed today’s date']);
        Status::create(['name'=>'Overdue','description'=>'The transaction is overdue if it is not fully paid and the due on date
has passed today’s day']);
    }
}
