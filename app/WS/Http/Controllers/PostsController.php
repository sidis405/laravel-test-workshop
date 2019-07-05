<?php

namespace WS\Http\Controllers;

use WS\Models\Post;
use App\Jobs\SendUpdateMail;
use WS\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');  // $this->middleware('auth')->only('create', 'store');
        // $this->middleware('can:update,post')->only('edit', 'update');
    }

    public function index()
    {
        return Post::with('user', 'category', 'tags')->get();
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        $post = auth()->user()->posts()->create($request->validated());

        $post->tags()->sync($request->tags);

        return redirect()->route('posts.show', $post);
    }

    public function show(Post $post)
    {
        return $post->load('user', 'category', 'tags');
    }

    public function edit(Post $edit)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Post $post, PostRequest $request)
    {
        $post->update($request->validated());

        $post->tags()->sync($request->tags);

        SendUpdateMail::dispatch($post);

        return redirect()->route('posts.show', $post);
    }
}
