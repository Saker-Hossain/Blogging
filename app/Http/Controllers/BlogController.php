<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }
    public function index()
    {
        $posts = Post::latest()->get();
        return view('blogPost.blog', compact('posts'));
    }

    public function create()
    {
        return view('blogPost.create-blog-post');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'body' => 'required',
        ]);

        $title = $request->input('title');

        $postId = Post::latest()->take(1)->first()->id + 1;
        $slug = Str::slug($title, '-') . '-' . $postId;
        $user_id = Auth::user()->id;
        $body = $request->input('body');

        $imagePath = 'storage/'. $request->file('image')->store('postsImages', 'public');

        $post = new Post();
        $post->title = $title;
        $post->slug = $slug;
        $post->user_id = $user_id;
        $post->body = $body;
        $post->imagePath = $imagePath;
        $post->save();

        return redirect()->back()->with('status', "Post created successfully.");
    }

    public function edit(Post $post)
    {
        if(auth()->user()->id !== $post->user()->id)
        {
            abort(403);
        }
        return view('blogPost.edit-blog-post', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if(auth()->user()->id !== $post->user()->id)
        {
            abort(403);
        }
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'body' => 'required',
        ]);

        $title = $request->input('title');

        $postId = $post->id;
        $slug = Str::slug($title, '-') . '-' . $postId;
        $body = $request->input('body');

        $imagePath = 'storage/'. $request->file('image')->store('postsImages', 'public');

        $post->title = $title;
        $post->slug = $slug;
        $post->body = $body;
        $post->imagePath = $imagePath;
        $post->save();

        return redirect()->back()->with('status', "Post edited successfully.");
    }

    //using route model binding
    public function show(Post $post)
    {
        return view('blogPost.single-blog-post', compact('post'));
    }

    public function delete(Post $post)
    {
        $post->delete();
        return redirect()->back()->with('status', "Post Deleted successfully.");
    }
}