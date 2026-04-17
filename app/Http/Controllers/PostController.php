<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Tambahkan ini untuk otorisasi
use Illuminate\Routing\Controllers\HasMiddleware; // Tambahkan ini untuk Laravel 11
use Illuminate\Routing\Controllers\Middleware; // Tambahkan ini untuk Laravel 11

class PostController extends Controller implements HasMiddleware
{
    /**
     * Pengaturan Middleware di Laravel 11
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    /**
     * Menampilkan semua daftar post.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Menyimpan post baru (terhubung ke user yang login).
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        // Menyimpan post melalui relasi user agar user_id terisi otomatis
        $post = $request->user()->posts()->create($fields);

        return $post;
    }

    /**
     * Menampilkan satu post spesifik.
     */
    public function show(Post $post)
    {
        return $post;
    }

    /**
     * Memperbarui post (hanya pemilik yang boleh).
     */
    public function update(Request $request, Post $post)
    {
        // Cek apakah user ini adalah pemilik post tersebut (PostPolicy)
        Gate::authorize('modify', $post);

        $fields = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        $post->update($fields);

        return $post;
    }

    /**
     * Menghapus post (hanya pemilik yang boleh).
     */
    public function destroy(Post $post)
    {
        // Cek apakah user ini adalah pemilik post tersebut (PostPolicy)
        Gate::authorize('modify', $post);

        $post->delete();

        return ['message' => 'Post deleted successfully'];
    }
}