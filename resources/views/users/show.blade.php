@extends('layouts.default')
@section('title', $user->name)
@section('content')
<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="col-md-12">
        <div class="col-md-offset-2 col-md-8" style="margin-top:100px;text-align:center;">
        <section class="user_info">
          @include('shared.user_info', ['user' => $user])
        </section>
      </div>
    </div>
  </div>
</div>
@stop