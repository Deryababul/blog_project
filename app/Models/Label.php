<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable=["name","created_at","updated_at","user_id"];
    
    // public function derya(){
    //     $blog = Blog::find(1);
    //     $blog->labels()->attach('label_id');
    // }

    public function blogs(){
        return $this->belongsToMany(Blog::class, 'label_to_blog', 'label_id','blog_id');// ilişkinin modeli - ilişkinin tablo adı - ana model id- ilişki kurulacak model id
    } //çoka çoksa böyle
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
} 


