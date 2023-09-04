<?php

namespace App\Policies;

use App\Models\Blog;
use App\Models\Label;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user,Blog $blog): bool
    {
        return $user->id == $blog->user_id;

    }

}
