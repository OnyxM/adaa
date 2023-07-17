<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BeatController extends Controller
{
    /**
     * Create a new beat
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $fields = Validator::make($request->all(),[
            'title' => "required|min:8",
            'premium_file' => "required",
            'free_file' => "required",
        ]);
        if($fields->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $fields->errors()
            ], 400);
        }

        $new_beat = Beat::create([
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title)."-".time(),
            'premium_file' => $request->premium_file,
            'free_file' => $request->free_file,
        ]);

        Log::info("{$new_beat->user->email} just added a new beat.", ['beat' => $new_beat->id]);

        return response()->json([
            'message' => "Beat created successfully.",
            'beat' => $new_beat
        ]);
    }

    /**
     * Get details about a beat
     *
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function details($slug)
    {
        return response()->json([
            'beat' => Beat::where('slug', $slug)->first()
        ]);
    }

    /**
     * Like a beat
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function like(Request $request)
    {
        $fields = Validator::make($request->all(),[
            'beat' => "required|exists:beats,id",
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
            'likeable_id' => $request->beat,
            'likeable_type' => Like::class,
        ],[
            'user_id' => Auth::user()->id,
            'likeable_id' => $request->beat,
            'likeable_type' => Like::class,
        ]);

        Log::info(Auth::user()->email." just liked a beat.", ['post' => $request->beat]);

        return response()->json([
            'message' => "Beat liked."
        ]);
    }
}
