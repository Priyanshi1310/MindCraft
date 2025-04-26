<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index() {
        return Post::with('user')->latest()->get();
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'author' => Auth::user()->name,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Post Created',
            'post' => $post
        ], 201);
    }

    public function show(Post $post) {
        return $post->load('content');
    }

    public function update(Request $request, Post $post) {
        $this->authorize('update', $post);

        $post->update($request->only(['title', 'body']));
        return response()->json($post);
    }

    public function destroy(Post $post) {
        $this->authorize('delete', $post);

        $post->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request) {
        $query = Post::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', "%{$request->title}%");
        }
        if ($request->filled('author')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->author}%"));
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        return response()->json($query->with('user')->get());
    }
    
}