@extends('layouts.app')

@section('content')
<div>
    <div>
        <h1>Them moi</h1>
    </div>

    <div>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" required>
            </div>
            <button type="submit">Them moi</button>
        </form>
    </div>
</div>
@endsection