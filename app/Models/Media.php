<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    public function blogs(){
        return $this->belongsToMany(Blog::class, 'blog_to_media','media_id','blog_id');
    }
    public function user(){
        return $this->hasOne(User::class, 'media_id');
    }
    protected $fillable = [
        'path_name','type'
    ];
}
