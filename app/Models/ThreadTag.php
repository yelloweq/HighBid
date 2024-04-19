<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadTag extends Model
{
    use HasFactory;

    protected $table = 'threads_tags';

    protected $fillable = [
        'thread_id',
        'tag_id',
    ];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
