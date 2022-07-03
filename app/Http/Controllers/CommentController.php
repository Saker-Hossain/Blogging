<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
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
            $validator = Validator::make($request->all(), [
                'comment' => 'required|string'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('status', "Comment area is mandatory");
            }
            $post = Post::where('id', $request->post_id)->first();
            if ($post) {
                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => Auth::user()->id,
                    'comment' => $request->comment
                ]);
                return redirect()->back()->with('status', "Commented Successfully.");
            } else {
                return redirect()->back()->with('status', "No Such Post Found.");
            }
        } else {
            return redirect('login')->with('status', "Login first to comment.");
        }
    }
    public function destroy(Request $request)
    {
        if (Auth::check()) {
            $comment = Comment::where('id', $request->comment_id)
                ->where('user_id', Auth::user()->id)
                ->first();

            if ($comment) {
                $comment->delete();
                return response()->json([
                    'status' => 200,
                    'message' => "Comment Deleted Successfully",
                ]);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => "Something Went Wrong.",
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => "Login to delete this comment",
            ]);
        }
    }
}