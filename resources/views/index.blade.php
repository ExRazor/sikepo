@extends('layouts.master')

@section('title','Beranda')

@section('content')

<div class="br-pagetitle">
  <i class="icon ion-ios-home-outline"></i>
  <div>
    <h4>Welcome</h4>
    <p class="mg-b-0">Selamat datang {{Auth::user()->name}}</p>
  </div>
</div>

@endsection
