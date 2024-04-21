<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreThreadRequest;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\ThreadTag;
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

    public function viewThread(Thread $thread): View
    {
        return view('partials.thread-view', compact('thread'));
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

        $thread = Thread::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);
        $tag = Tag::where('name', $request->tag)->first();
        if (!$tag) {
            return response()->view('components.message', ['message' => 'Tag not found!', 'type' => 'error']);
        }
        ThreadTag::create([
            'thread_id' => $thread->id,
            'tag_id' => $tag->id,
        ]);
        return response()->view('components.message', ['message' => 'Thread created successfully!', 'type' => 'success']);
    }

    public function getThreadTagsList(): View
    {
        $tags = Tag::all();
        return view('components.tag-list', compact('tags'));
    }
    public function storeComment(StoreCommentRequest $request, Thread $thread)
    {
        // 
    }

    public function getThreadComments(Thread $thread): View
    {
        $comments = $thread->comments()->orderByDesc('created_at')->get();
        $comments = $this->addDepth($comments);
        return view('partials.comment-section', compact('comments'));
    }

    private function addDepth($comments, $depth = 0)
    {
        foreach ($comments as $comment) {
            $comment->depth = $depth;
            if ($comment->replies) {
                $this->addDepth($comment->replies, $depth + 1);
            }
        }
        return $comments;
    }

    public function getThreadCommentReplies(Thread $thread, int $parent_id): View
    {
        $replies = Thread::with('comments')->where('parent_id', $parent_id)->get();
        if ($replies->isEmpty()) {
            return response('');
        }
        return view('components.comment-seciton', compact('replies'));
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
