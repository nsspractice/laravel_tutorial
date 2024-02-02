@extends('layouts.default')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <h2>投稿詳細</h2>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <p><a href="/bbc" class="btn btn-warning text-white">掲示板一覧へ</a></p>
                    </div>
                </div>
            </div>   
            <hr />
            
            <h3 class="my-4">タイトル：{{$post->title}}</h3>
                <small>投稿日：{{date("Y/m/d/H:m:s",strtotime($post->created_at))}}</small>
            <p>カテゴリー：{{$post->category->name}}</p>
            <p>{{$post->content}}</p>
        
            <hr />
            <h4 class="my-4">コメント投稿</h4>
            {{-- 投稿完了時にフラッシュメッセージを表示 --}}
            @if(Session::has('message'))
                <div class="bg-info">
                    <p>{{ Session::get('message') }}</p>
                </div>
            @endif

            {{-- エラーメッセージの表示
            @foreach($errors->all() as $message)
                <p class="bg-danger">{{ $message }}</p>
            @endforeach --}}

            {{ Form::open(['route' => 'comment.store','class' => 'form']) }}

                <div class="form-group">
                    @if($errors->has('commenter'))
                        <p class="bg-danger">{{$errors->first('commenter')}}</p>
                    @endif
                    <label for="commenter" class="">名前</label>
                    <div class="">
                        {{ Form::text('commenter', null, ['class' => 'form-control', 'id' => 'commenter']) }}
                    </div>
                </div>

                <div class="form-group">
                    @if($errors->has('comment'))
                        <p class="bg-danger">{{$errors->first('comment')}}</p>
                    @endif
                    <label for="comment" class="">コメント</label>
                    <div class="">
                        {{ Form::textarea('comment', null, ['class' => 'form-control','id' => 'comment']) }}
                    </div>
                </div>

                {{ Form::hidden('post_id', $post->id) }}

                <div class="form-group mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">コメントを投稿する</button>
                </div>


            {{ Form::close() }}

            <hr />

            <h4 class="my-4">コメント一覧</h4>
            @foreach($post->comments as $single_comment)
                <h6>{{$single_comment->commenter}}</h6>
                    <small>投稿日：{{ date("Y/m/d/H:m:s",strtotime($single_comment->created_at)) }}</small>
                <p>{{$single_comment->comment}}</p><br />
            @endforeach

        </div>
        <div class="col-2"></div>
    </div>
</div>
<script>
    //commenter,commentから、2つすべてエラーが起きた場合の処理
    @if($errors->has('commenter') && $errors->has('comment'))
        document.getElementById('commenter').focus();
    //commenter,commentから、1つエラーが起きた場合の処理
    @elseif($errors->has('commenter'))
        document.getElementById('commenter').focus();
    @elseif($errors->has('comment'))
        document.getElementById('comment').focus();
    @endif

</script>
@stop