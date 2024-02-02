<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;


//モデルのリレーション設定
class Post extends Model
{
    // use HasFactory;
    public function comments(){
        //1対多の関係、主テーブルが複数の従テーブルのレコードが紐づく
        //投稿はたくさんのコメントを持つため
        return $this->hasMany(Comment::class,'post_id')->orderBy('created_at','desc')->orderBy('id','desc');
        //引数として関連付けるモデルのクラスと、外部キーの名前をとる。
        //orderByを使用して、一つの掲示板投稿対する複数のコメントを並び替えることができる
    }
    public function category(){
        //逆に従テーブルから、主テーブルのレコードを取り出す
        //投稿は１つのカテゴリーに属するため
        return $this->belongsTo(Category::class,'cat_id');
    }
}
