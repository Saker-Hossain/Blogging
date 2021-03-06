@extends('layout')
@section('head')
    <script src="//cdn.ckeditor.com/4.19.0/standard/ckeditor.js"></script>
@endsection
@section('main')
    <main class="container" style="background-color: #fff;">
        <section id="contact-us">
            <h1 style="padding-top: 50px">Create New Post</h1>
            @include('includes.flash-message')
            <div class="contact-form">
                <form action="{{ Route('blog.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="title"><span>Title</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}">
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

                    <label for="categories"><span>Choose a category:</span></label>
                    <select name="category_id" id="categories">
                        <option selected disabled>Select option</option>
                        @foreach ($categories    as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        {{-- The $atributeValue field is/must be $validationRule --}}
                        <p style="color: red; margin-bottom:25px;">{{ $message }}</p>
                    @enderror

                    <label for="body"><span>Body</span></label>
                    <textarea name="body" id="body">{{ old('body') }}</textarea>
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
