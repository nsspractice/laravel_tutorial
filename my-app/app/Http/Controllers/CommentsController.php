<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'commenter' => 'required|max:10',
            'comment' => 'required|max:50',
            'post_id' => 'required'
        ];

        $messages = [
            'commenter.required' => '※ 名前を正しく入力してください。',
            'commenter.max' => '※ 10文字以内で入力してください。',
            'comment.required' => '※ コメントを正しく入力してください。',
            'comment.max' => '※ 50文字以内で入力してください。',
        ];

        try{
            $validated = $request->validate($rules,$messages);
        }catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        //バリデーションが通った時の処理
        $comment = new Comment;
        $comment->commenter = $validated['commenter'];
        $comment->comment = $validated['comment'];
        $comment->post_id = $validated['post_id'];
        $comment->save();

        $post = Post::find($validated['post_id']);
        $post->comment_count++;
        $post->save();

        return redirect()->back()->with('message','✓ コメントが投稿されました。');
    }
}
