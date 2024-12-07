@extends('layouts.app')

@section('content')
<div>
    <div>
        <h1>Xem</h1>
    </div>

    <div>
        <form>
            @csrf
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ $category->name }}" readonly>
            </div>
            <div>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" value="{{ $category->description }}" readonly>
            </div>
        </form>
    </div>
</div>
@endsection