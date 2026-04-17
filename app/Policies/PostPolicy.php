<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Menentukan apakah user boleh mengubah (update/delete) post.
     */
    public function modify(User $user, Post $post): bool
    {
        // User hanya boleh akses jika id-nya sama dengan user_id di tabel posts
        return $user->id === $post->user_id;
    }
}