<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    public function index(Request $request)
    {
        if ($request->search) {
            $posts = Post::where('title', 'like', '%' . $request->search . '%')
            ->orWhere('body', 'like', '%' . $request->search . '%')->latest()->paginate(4);
        }elseif($request->category){
            $posts = Category::where('name', $request->category)->firstOrFail()->posts()->paginate(3)->withQueryString();
        }
        else {
            $posts = Post::latest()->paginate(4);
        }
        $categories = Category::all();
        return view('blogPost.blog', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('blogPost.create-blog-post', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image',
            'body' => 'required',
            'category_id' => 'required',
        ]);

        $title = $request->input('title');
        $category_id = $request->input('category_id');

        if (Post::latest()->first() !== null) {
            $postId = Post::latest()->first()->id + 1;
        } else {
            $postId = 1;
        }

        $slug = Str::slug($title, '-') . '-' . $postId;
        $user_id = Auth::user()->id;
        $body = $request->input('body');

        $imagePath = 'storage/'. $request->file('image')->store('postsImages', 'public');

        $post = new Post();
        $post->title = $title;
        $post->category_id = $category_id;
        $post->slug = $slug;
        $post->user_id = $user_id;
        $post->body = $body;
        $post->imagePath = $imagePath;
        $post->save();

        return redirect()->back()->with('status', "Post created successfully.");
    }

    public function edit(Post $post)
    {
        if(auth()->user()->id !== $post->user->id)
        {
            abort(403);
        }
        return view('blogPost.edit-blog-post', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        if(auth()->user()->id !== $post->user->id)
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
        $category = $post->category;
        $relatedPosts = $category->posts()->where('id', '!=', $post->id)->latest()->take(3)->get();
        return view('blogPost.single-blog-post', compact('post','relatedPosts'));
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->back()->with('status', "Post Deleted successfully.");
    }

    public function save_comment(Request $request)
    {
        // $request->validate([
        //     'post_id' => 'required',
        //     'comment' => 'required'
        // ]);

        // $data = new Comment();
        // $data->user_id = Auth::user()->id;
        // $data->post_id = $request->post_id;
        // $data->comment = $request->comment;
        // // dd($data);
        // $data->save();
        // $post = $data->post;
        // $category = $post->category;
        // $relatedPosts = $category->posts()->where('id', '!=', $post->id)->latest()->take(3)->get();


        // return view('blogPost.single-blog-post', compact('post', 'relatedPosts'))->with('status', "Comment has been submitted.");

        if (Auth::check()) {
            $validator = Validator::make($request->all(),[
                'comment' => 'required|string'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('status',"Comment area is mandatory");
            }
            $post = Post::where('id', $request->post_id)->first();
            if ($post) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => Auth::user()->id,
                    'comment' => $request->comment
                ]);
                return redirect()->back()->with('status',"Commented Successfully.");
            } else {
                return redirect()->back()->with('status',"No Such Post Found.");
            }

        } else {
            return redirect('login')->with('status',"Login first to comment.");
        }

    }
}