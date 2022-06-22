@extends('layout')
@section('head')
    <script src="//cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
@endsection
@section('main')
    <main class="container" style="background-color: #fff;">
        <section id="contact-us">
            <h1 style="padding-top: 50px">Edit Post!</h1>
            @if (session('status'))
                <p
                    style="color: #fff; width:100%; font-size:18px;font-weight:600;text-align:center; background: #5cb85c; padding: 17px 0; margin-bottom:6px;">
                    {{ session('status') }}</p>
            @endif
            <div class="contact-form">
                <form action="{{ Route('blog.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <label for="title"><span>Title</span></label>
                    <input type="text" id="title" name="title" value="{{ $post->title }}">
                    @error('title')
                        {{-- The $atributeValue field is/must be $validationRule --}}
                        <p style="color: red; margin-bottom:25px;">{{ $message }}</p>
                    @enderror

                    <label for="image"><span>Image</span></label>
                    <input type="file" id="image" name="image">
                    @error('image')
                        {{-- The $atributeValue field is/must be $validationRule --}}
                        <p style="color: red; margin-bottom:25px;">{{ $message }}</p>
                    @enderror

                    <label for="body"><span>Body</span></label>
                    <textarea name="body" id="body">{{ $post->body }}</textarea>
                    @error('body')
                        {{-- The $atributeValue field is/must be $validationRule --}}
                        <p style="color: red; margin-bottom:25px;">{{ $message }}</p>
                    @enderror

                    <input type="submit" value="Submit" />
                </form>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <script>
        CKEDITOR.replace('body');
    </script>
@endsection
