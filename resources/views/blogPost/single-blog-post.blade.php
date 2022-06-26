@extends('layout')
@section('main')
    <!-- main -->
    <main class="container">
        <section class="single-blog-post">
            <h1>{{ $post->title }}</h1>
            {{-- @include('includes.flash-message') --}}
            <p class="time-and-author">
                {{ $post->created_at->diffForHumans() }}
                <span>Written By {{ $post->user->name }}</span>
            </p>

            <div class="single-blog-post-ContentImage" data-aos="fade-left">
                <img src="{{ asset($post->imagePath) }}" alt="" />
            </div>

            <div class="about-text">
                {!! $post->body !!}
            </div>
        </section>

        <div>
            @auth()
                {{-- Add Comment --}}
                <div class="card my-5">
                    <h5 class="card-header">Add Comment</h5>
                    @include('includes.flash-message')
                    <div class="card-body">
                        <form action="{{ route('comment.save_comment', $post) }}" method="post">
                            @csrf
                            @method('POST')
                            <input name="post_id" type="hidden" value="{{ $post->id }}"></input>
                            <textarea name="comment" class="form-control"></textarea>
                            <input type="submit" value="Submit" />
                        </form>
                    </div>
                </div>
            @endauth
            <section>
                {{-- Fetch Comment --}}
                <div class="card my-4">
                    <h5 class="card-header">Comments <span class="badge badge-dark"></span></h5>
                    <div class="card-body">
                        {{-- @dump($post->comments) --}}
                        {{-- @dd($post->comments) --}}
                        @if ($post->comments)
                            @foreach ($post->comments as $comment)
                                <blockquote class="blockquote">
                                    <p class="mb-0">{{ $comment->comment }}</p>
                                    {{-- @if ($comment->user_id == 0)
                                        <footer class="blockquote-footer">Admin</footer>
                                    @else --}}
                                        <footer class="blockquote-footer">{{ $comment->user->name }}</footer>
                                    {{-- @endif --}}
                                </blockquote>
                                <hr/>
                            @endforeach
                        @endif
                    </div>
                </div>
            </section>
        </div>

        <section class="recommended">
            <p>Related</p>
            <div class="recommended-cards">

                @foreach ($relatedPosts as $relatedPost)
                    <a href="">
                        <div class="recommended-card">
                            <img src="{{ asset($relatedPost->imagePath) }}" alt="" loading="lazy" />
                            <h4>
                                {{ $relatedPost->title }}
                            </h4>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </main>
@endsection
