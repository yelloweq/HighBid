<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreThreadRequest;
use App\Models\Thread;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function index(): View
    {
        return view('forum');
    }

    public function showThreads(): View
    {
        $threads = Thread::all();
        return view('partials.forum-body', compact('threads'));
    }

    public function showThread($post)
    {
        // 
    }

    public function editThread(Thread $thread): View
    {
        return view('components.thread-edit-form', compact('thread'));
    }

    public function createThread(): View
    {
        return view('components.thread-create-form');
    }

    public function storeThread(StoreThreadRequest $request)
    {
        dd($request->all());
        // Thread::create([
        //     'title' => $request->title,
        //     'body' => $request->body,
        //     'user_id' => auth()->id(),
        // ]);
    }

    public function storeComment(StoreCommentRequest $request, Thread $thread)
    {
        // 
    }

    public function edit($request, $post)
    {
        // 
    }

    public function delete($request, $post)
    {
        // 
    }
}
