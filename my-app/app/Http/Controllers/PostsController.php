<?php

namespace App\Http\Controllers;
use App\Models\Post;
//Postモデルはデータベーステーブルと対話するためのクラス。
use Illuminate\Http\Request;
//Requestオブジェクトが提供するvalidateメソッドを使用していく。
use Illuminate\Validation\ValidationException;
//バリデーションに失敗すると、例外を投げ適切なエラーレスポンスを自動的にユーザーに返送

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at','desc')->orderBy('id','desc')->get();
        // $posts = Post::all();
        //Postモデルに対応するすべてのデータベーステーブルのすべてのレコードを取得する。そして$postsに格納される。
        return View('bbc.index',['posts' => $posts]);
        //return View::make('bbc.index')->with('posts',$posts);
        //viewヘルパ関数を使用して結果を返している。
        //['第一引数が渡す先での変数名',$第二引数が今回渡す変数名]
    }

    public function show(string $id)
    {
        $post = Post::find($id);
        return View('bbc.single',['post' => $post]);
        // return View::make('bbc.single')->with('post', $post);
    }

    public function store(Request $request)
    {
        //バリデーションの設定
        $rules = ([
                'title' => 'required|max:10',
                'content' => 'required|max:100',
                'cat_id' => 'required'     
        ]);

        $messages = ([
            'title.required' => '※ タイトルを正しく入力してください。',
            'title.max' => '※ 10文字以内で入力してください。',
            'content.required' => '※ 本文を正しく入力してください。',
            'content.max' => '※ 100文字以内で入力してください。',
            'cat_id.required' => '※ カテゴリーを選択してください。'
        ]);

        // 以降のコード
        

        //例外処理とバリデーションの実行
        try {
            $validated = $request->validate($rules, $messages);

        }catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
            }
    
            // バリデーションが通った場合の処理
            $post = new Post;
            $post->title = $validated['title'];
            $post->content = $validated['content'];
            $post->cat_id = $validated['cat_id'];
            $post->save();
    
            return back()->with('message', '✓ 投稿が完了しました。');
    }
    
    public function destroy(string $id)
    {
        $post=Post::find($id);
        $post->delete();
        return redirect('/bbc');
    }
    
}
