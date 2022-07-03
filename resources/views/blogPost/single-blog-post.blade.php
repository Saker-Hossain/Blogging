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
        {{-- Add Comment --}}
        <section>
            <div class="comment-area mt-4">
                <div class="card my-5">
                    @include('includes.flash-message')
                    <h5 class="card-header">Add Comment</h5>
                    <div class="card-body">
                        <form action="{{ route('comment.save_comment') }}" method="POST" style="font-weight: 300; font-size: 16;">
                            @csrf
                            @method('POST')
                            <input name="post_id" type="hidden" value="{{ $post->id }}"></input>
                            <textarea name="comment" class="form-control" style="height: 50px;" placeholder="Write a comment.."></textarea>
                            {{-- <input type="submit" value="Submit" /> --}}
                            <button type="submit" class="button button1"Submit>Submit</button>
                        </form>
                    </div>
                </div>

                {{-- edit comment --}}
                <div class="comment-area mt-4">
                    <div class="card my-5">
                        @include('includes.flash-message')
                        <h5 class="card-header">Edit & Update Comment</h5>
                        <div class="card-body">
                            <form action="{{ route('comment.save_comment') }}" method="POST" style="font-weight: 300; font-size: 16;">
                                @csrf
                                @method('POST')
                                <input name="post_id" type="hidden" value="{{ $post->id }}"></input>
                                <textarea name="comment" class="form-control" style="height: 50px;" placeholder="Write a comment.."></textarea>
                                {{-- <input type="submit" value="Submit" /> --}}
                                <button type="submit" class="button button1"Submit>Update</button>
                            </form>
                        </div>
                    </div>
                    {{--end- edit comment --}}

                @forelse ($post->comments as $comment)
                    <div class="comment-container card card-body shadow-sm mr-3"
                        style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;
                    box-sizing: border-box; margin-top: 6px; margin-bottom: 20px; font-size: 16px;">
                        <div class="detail-area">
                            <h5 class="user-name mb-1">
                                @if ($comment->user)
                                    {{ $comment->user->name }}
                                @endif
                                <small class="ms-3 text-primary" style="color: rgb(90, 144, 213);">Commented on:
                                    {{ $comment->created_at->diffForHumans() }}</small>
                            </h5>
                            <p class="user-comment mb-1">
                                {{ $comment->comment }}
                            </p>
                        </div>
                        @if (Auth::check() && Auth::id() == $comment->user_id)
                            <div>
                                <button type="button" value="{{ $comment->id }}" class="editComment button">Edit</button>
                                <button type="button" value="{{ $comment->id }}" class="deleteComment button">Delete</button>
                            </div>
                        @endif
                    </div>
                    <div class="card card-body shadow-sm mr-3">
                    @empty
                        <h6>No Comments Yet.</h6>
                @endforelse
            </div>
            {{-- </section> --}}
            </div>
        </section>

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

@section('scripts')
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            $(document).on('click','.deleteComment', function () {
                if (confirm('Are you sure you want to delete this comment?')) {
                    var thisClicked = $(this);
                    var comment_id = thisClicked.val();

                    $.ajax({
                        type: "POST",
                        url: "/delete-comment",
                        data: {
                            'comment_id': comment_id
                        },
                        success: function (res) {
                            if (res.status == 200) {
                                thisClicked.closest('.comment-container').remove();
                                alert(res.message);
                            }else{
                                alert(res.message);
                            }
                        }
                    });
                }
            });
            $(document).on('click','editComment' function (e) {
                e.preventDefault();
                // var thisClicked = $(this);
                var comment_id = $(this).val();
                console.log(comment_id);
            });
        });
    </script>
@endsection
