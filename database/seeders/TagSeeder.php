<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\ThreadTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ["Discussion", "Help", "News", "Announcement", "Tutorial", "Question", "Feature Request", "Bug Report", "Feedback", "Showcase"];
        foreach ($tags as $tag) {
            Tag::factory()->create(['name' => $tag]);
        }
    }
}
