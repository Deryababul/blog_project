<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table="blogs";
    protected $fillable=["title","text","is_active","created_at","updated_at","user_id","category_id"];
    public function labels(){
        return $this->belongsToMany(Label::class, 'label_to_blog', 'blog_id','label_id');
    }
//bire çok ilişki olduğu için ve bir kullanıcının birden fazla postu olabilir fakat bir postun sadece 1 kullanıcısı olabilir 
//bu yüzden 1 olan taraf yani user model tarafında 1-çok olduğu için hasmany()
//çok-1 olan taraf yani blog tarafında ise belongsto kullanıyoruz
    public function user(){ 
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function comments(){
        return $this->belongsToMany(Comment::class, 'comment_to_blogs','blog_id','comment_id');
    }
    public function media(){
        return $this->belongsToMany(Media::class, 'blog_to_media','blog_id','media_id');
    }



}


