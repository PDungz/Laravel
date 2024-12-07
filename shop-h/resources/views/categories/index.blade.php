@extends('layouts.app')

@section('content')
<div>
    <table>
        <thead>
            <th>Id</th>
            <th>name</th>
            <th>description</th>
            <th>Thao tac</th>
        </thead>
        <div>
            @if (session('success'))
                {{ session('success')}}
            @elseif (session('error'))
            {{ session('error')}}
            @endif
        </div>
        <a href="{{route('categories.create')}}">Them</a>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <th>{{$category->id}}</th>
                    <th>{{$category->name}}</th>
                    <th>{{$category->description}}</th>
                    <th>
                        <a href="{{route('categories.show', $category->id)}}">Xem</a>
                        <a href="{{route('categories.edit', $category)}}">Cap nhat</a>
                        <form action="{{route('categories.destroy', $category->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Xoa</button>
                        </form>
                    </th>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection