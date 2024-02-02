<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

use Faker\Provider\DateTime;
use Carbon\Carbon;


class PostCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void //戻り値がないことを示すためにある。
    {
        $content = 'この文章はダミーです。文字の大きさ、量、字間、行間などを確認するために入れています。この文章はダミーです。文字の大きさ、量、字間、行間などを確認するために入れています。この文章はダミーです。文字の大きさ、量、字間、行間などを確認するために入れています。';
        
        $commentdammy = 'コメントダミーです。ダミーコメントです。';

        $randomPostDate = strtotime('2023-01-01');
        $startComment= strtotime('2023-01-01');
        $endComment= strtotime('2024-01-31');

        for($i = 1; $i <= 10 ; $i++) {
            $startPost = $randomPostDate;
            $endPost = strtotime("+2 month",$startPost);
            $randomPostDate = mt_rand($startPost, $endPost);
            $randomPost = date('Y-m-d H:i:s', $randomPostDate);
            $post = new Post;
            $post->title = "{$i}番目の投稿";
            $post->content = $content;
            $post->cat_id = 1;
            $post->comment_count = 0;
            $post->created_at = $randomPost;
            $post->save();
        
            $maxComments = mt_rand(3,15);
            for($j = 0; $j <= $maxComments; $j++) {
                $randomCommentDate = mt_rand($startComment, $endComment);
                $randomComment = date('Y-m-d H:i:s', $randomCommentDate);
                $comment = new Comment;
                $comment->commenter = '名無しさん';
                $comment->comment = $commentdammy;
                $comment->created_at = $randomComment;

                //モデル(Post.php)のCommentsメソッドを読み込み、post_idにデータを保存する。
                $post->comments()->save($comment);
                $post->increment('comment_count');
            }
        }
        //カテゴリーを追加する
        $cat1 = new Category;
        $cat1->name = "電化製品";
        $cat1->save();

        $cat2 = new Category;
        $cat2->name = "食品";
        $cat2->save();
    }
}

