@extends('layouts.public')

@section('content')
    <div
        id="app"
        data-page="{{ $page }}"
        data-props='@json($props)'
    ></div>
@endsection
