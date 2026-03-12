<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::paginate();
        return response()->json([
            "status" => 1,
            "data" => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "body" => "string|nullable"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => $validator->errors()->all()
            ], 422);
        }

        $validatedData = $validator->validated();

        $post = Post::create($validatedData);

        return response()->json([
            "status" => 1,
            "message" => "Post Created!",
            "data" => $post
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            "status" => 1,
            "data" => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "body" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "message" => $validator->errors()->all()
            ], 422);
        }

        $validatedData = $validator->validated();

        $post->update($validatedData);

        return response()->json([
            "status" => 1,
            "message" => "Post Updated!",
            "data" => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            "status" => 1,
            "message" => "Post Deleted!",
        ]);
    }
}
