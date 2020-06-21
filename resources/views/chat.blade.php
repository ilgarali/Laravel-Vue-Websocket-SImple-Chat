@extends('layouts.app')

@section('content')
<div id="app" class="container">
   @csrf
<chats :user="{{auth()->user()}}"></chats>
</div>
@endsection
