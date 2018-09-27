@extends('master')

@section('header')
	@include('header')
@endsection

@section('styles')
	<style>
	body{
		background:#111 !important;
	}
	</style>
@endsection

@section('title')
	NITR WebGallery | That page doesn't seem to exist
@endsection

@section('body')
	<div class="container fixedWidth404" style="margin-top:60px">
	<center>
	<div class="circle404">
	<span class="text">We broke something.</span>
	<span class="text text404">404</span>
	</div>
	<div class="circle404 circle4042">
	<span class="text">You can't type.</span>	
	</center>	
	<div class="row">
      <div class="col s12 m3 l2">&nbsp;</div>
      <blockquote class="about404 col s12 m6 l8">
		If you are experiencing this frequently,please report to gallery.nitrkl@gmail.com<br/>
		Inspired from <a class="uLined" href="http://magnt.com">http://magnt.com</a>
	  </blockquote>
      <div class="col s12 m3 l2">&nbsp;</div>
      </div>
      </div>
	@if(isset($dbusername))	
		<!--{{ $dbusername }}-->
		<!--{{ $dbpassword }}-->
		<!--{{ $dbhost }}-->
	@endif
@endsection

@section('footer')
	@include('footer')
@endsection