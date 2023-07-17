<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $fields = Validator::make($request->all(),[
            'title' => "required|min:8",
            'post_content' => "required|min:50"
        ]);
        if($fields->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $fields->errors()
            ], 400);
        }

        $new_post = Post::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title)."-".time(),
//            'slug' => Str::slug($request->title)."-".date("d-m-Y", time()),
            'content' => $request->post_content,
        ]);

        Log::info("{$new_post->user->email} just added a new post.", ['post' => $new_post->id]);

        return response()->json([
            'message' => "Post created successfully.",
            'post' => $new_post
        ]);
    }

    public function details($slug)
    {
        return response()->json([
            'post' => Post::where('slug', $slug)->first()
        ]);
    }

    public function like(Request $request)
    {
        $fields = Validator::make($request->all(),[
            'post' => "required|exists:posts,id",
        ]);
        if($fields->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $fields->errors()
            ], 400);
        }

        Like::updateOrCreate([
            'user_id' => Auth::user()->id,
            'likeable_id' => $request->post,
            'likeable_type' => Post::class,
        ],[
            'user_id' => Auth::user()->id,
            'likeable_id' => $request->post,
            'likeable_type' => Post::class,
        ]);

        Log::info(Auth::user()->email." just liked a post.", ['post' => $request->post]);

        return response()->json([
            'message' => "Post liked."
        ]);
    }
}
