<?php

namespace Database\Seeders;

use App\Models\Comment;
use Database\Factories\CommentFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Comment::factory()->times(500)->create();
        Comment::factory()->times(500)->create(['parent_id' => Comment::all()->random()->id]);
    }
}
