<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LabelPolicy
{
    /**
     * Determine whether the user can view any models.
     */


    public function viewAny(User $user, Label $label)
    {
        return $user->id == $label->user_id;
    }

 
}
