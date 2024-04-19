<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Thread;
use App\Models\ThreadTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThreadTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ThreadTag::factory()->times(100)->create();
    }
}
