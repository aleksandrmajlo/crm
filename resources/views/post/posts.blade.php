@extends('layouts.app')
@section('title', $title)
@section('content')
    @if ($posts)
        @foreach($posts as $post)
        <div class="post-preview">
            <a href="/post/{{$post->slug}}">
                <h2 class="post-title">
                   {{$post->title}}
                </h2>
            </a>
            <p class="post-meta">
                Posted by
                {{ $post->created_at->format('Y-m-d') }}
            </p>
        </div>
        <hr>
        @endforeach

    @endif
    <div class="d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
@endsection


