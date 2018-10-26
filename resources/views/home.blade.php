@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    <chat :users="{{$users->toJson()}}" :user="{{auth()->user()->toJson()}}"></chat>
    </div>
</div>
@endsection
