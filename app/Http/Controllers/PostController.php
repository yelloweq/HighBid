<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return view('forum');
    }

    public function show($post)
    {
        // 
    }

    public function create($request)
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
