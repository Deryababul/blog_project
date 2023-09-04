<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable=["text","created_at","updated_at","user_id","blog_id"];
    public function blogs(){
        return $this->belongsToMany(Comment::class,'comment_to_blogs','comment_id','blog_id');
    }
}
