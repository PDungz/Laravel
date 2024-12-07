@extends('layouts.app')

@section('content')
<div>
    <div>
        <h1>Cap nhat</h1>
    </div>

    <div>
        <form action="{{ route('categories.update', $category->id ) }}" method="POST">
            @csrf
            @method('PUT')
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name',  $category->name) }}" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" value="{{ old('description',  $category->description )}}" required>
            </div>
            <button type="submit">Cap nhat</button>
        </form>
    </div>
</div>
@endsection