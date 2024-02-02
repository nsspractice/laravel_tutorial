@extends('layouts.default')
@section('content')

<div class="container">
	<div class="row">
		<div class="col-2"></div> 
		<div class="col-8">
			<div class="container">
				<div class="row">
					<div class="col-6">
						<h2>掲示板投稿一覧</h2>
					</div>
					<div class="col-6 d-flex justify-content-end">
						<p><a href="/create" class="btn btn-warning text-white">新規投稿</a></p>
					</div>
				</div>
			</div>
			<hr />
		@foreach($posts as $post)

			<h4>タイトル：{{ $post->title }}</h4>
			<small>投稿日：{{ date("Y/m/d/H:m:s",strtotime($post->created_at)) }}</small>
			<p>カテゴリー：{{ $post->category->name }}</p>
			<p>{{ Str::limit($post->content,40,'...') }}</p>
			<div class="d-flex justify-content-end">
				<p class="pe-3 pt-2">コメント数：{{ $post->comment_count }}</p>
				<p><a href="/bbc/{{ $post->id }}" class="btn btn-primary">続きを読む</a></p>	
				<p class="ps-2"><a onclick="openModal(this)" data-title="{{$post->title}}" data-postid="{{ $post->id }}" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">削除</a></p>
			</div>
			
			<!-- モーダル -->
			<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel"><p></p></h1>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p>こちらの投稿を削除してもよろしいでしょうか？</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
							<a href="#" id="deleteLink" class="btn btn-danger">削除する</a>
						</div>
					</div>
				</div>
			</div>

			<hr />
		@endforeach
		</div>
		<div class="col-2"></div> 
	</div>
</div>
<script>
	// モーダルが表示される前にデータ属性を更新
	function openModal(button) {
    var title = button.getAttribute('data-title');
    var post_id = button.getAttribute('data-postid');
	//モーダルのタイトルを更新
    document.getElementById('exampleModalLabel').innerText = 'タイトル：'+title;
	//モーダル内の削除リンクのhref属性を更新
    document.getElementById('deleteLink').setAttribute('href', '/bbc/delete/' + post_id);
	}	
</script>
@stop