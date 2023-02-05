@extends('templates.base')
@section("content")
    <h5 class="text-danger">{{ $exception->getMessage() }}</h5>
@endsection
