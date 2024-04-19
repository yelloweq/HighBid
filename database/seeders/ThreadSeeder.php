<?php

namespace Database\Seeders;

use App\Models\Thread;
use Database\Factories\ThreadFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Thread::factory()->times(100)->create();
    }
}
