
@extends('layouts.default')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <h2>新規投稿</h2>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <p><a href="/bbc" class="btn btn-warning text-white">掲示板一覧へ</a></p>
                    </div>
                </div>
            </div>
            <hr />
            {{-- 投稿完了時にフラッシュメッセージを表示 --}}
            @if(Session::has('message'))
                <div class="bg-info">
                    <p>{{Session::get('message')}}</p>
                </div>
            @endif

            {{-- エラーメッセージの表示 --}}
            {{-- @foreach($errors->all() as $error)
                <p class="bg-danger">{{$error}}</p>
            @endforeach
             --}}
            
            {{-- {{Form::open(['route' => 'bbc.store'],array('class' => 'form'))}} --}}
            {{ Form::open(['route' => 'bbc.store', 'class' => 'form']) }}
            {{-- <form method="POST" action="http://localhost/bbc/store" class="form"> --}}
            
                <div class="form-group">
                    @if($errors->has('title'))
                        <p class="bg-danger">{{$errors->first('title')}}</p>
                    @endif
                    <label for= "title" class="">タイトル</label>
                    <div class="">
                        {{-- {{Form::text('title',null,array('class' => ''))}} --}}
                        {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) }}
                        {{-- <input type="text" name="title" class="form-control" value=""> --}}

                    </div>
                </div>
                
                <div class="form-group">
                    @if($errors->has('cat_id'))
                        <p class="bg-danger">{{$errors->first('cat_id')}}</p>
                    @endif
                    <label for= "cat_id" class="">カテゴリー</label>
                    <div class="">
                        {{Form::select('cat_id', [null=>'-カテゴリーを選択してください-',1=>'電化製品', 2=>'食品'],null,['class' => 'form-control selectBox', 'id' => 'category'])}}
                    </div>
                </div>

                <div class="form-group">
                    @if($errors->has('content'))
                        <p class="bg-danger">{{$errors->first('content')}}</p>
                    @endif
                    <label for= "content" class="">本文</label>
                    <div class="">
                        {{-- {{Form::textarea('content',null,array('class' => ''))}} --}}
                        {{ Form::textarea('content', null, ['class' => 'form-control', 'id' => 'content']) }}
                        {{-- <input type="text" name="title" class="form-control" value=""> --}}
                    </div>
                </div>

                <div class="form-group mt-3 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">掲示板を投稿する</button>
                </div>
                
                {{ Form::close() }}
        </div>
        <div class="col-2"></div>
    </div>
</div>
<script>
    //title,category,contentから、３つすべてエラーが起きた場合の処理
    @if($errors->has('title') && $errors->has('cat_id') && $errors->has('content'))
        document.getElementById('title').focus();.
    //title,category,contentから、2つエラーが起きた場合の処理
    @elseif($errors->has('title') && $errors->has('cat_id'))
        document.getElementById('title').focus();
    @elseif($errors->has('title') && $errors->has('content'))
        document.getElementById('title').focus();
    @elseif($errors->has('cat_id') && $errors->has('content'))
        document.getElementById('category').focus();
    //title,category,contentから、1つエラーが起きた場合の処理
    @elseif($errors->has('title'))
        document.getElementById('title').focus();
    @elseif($errors->has('cat_id'))
        document.getElementById('category').focus();
    @elseif($errors->has('content'))
        document.getElementById('content').focus();
    @endif
</script>
    @stop
    {{-- @endsectionでも可 --}}

    