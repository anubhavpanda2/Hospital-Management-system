@extends('master')

@section('title')
	Hospital Management System
@endsection

<?php session_start(); ?>

@section('header')
	@include('header')
@endsection

@section('scriptIncludes')
    {{ HTML::script('assets/js/slippry.js') }} 
@endsection

@section('miscellaneous')
    <div class="container">
    <center>
      <div class="row">
    <form class="col s12">
      <div class="row">
        <div class="input-field col s6">
          <input  id="first_name" type="text" class="validate">
          <label for="first_name">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="last_name" type="text" class="validate">
          <label for="last_name">Last Name</label>
        </div>

 <p>
      <input name="group1" type="radio" id="Male" />
      <label for="Male">Male</label>
    </p>
    <p>
      <input name="group1" type="radio" id="Female" />
      <label for="Female">Female</label>
    </p>
        <div class="input-field col s6">
       <input id="Date_of_Birth" type="date" class="datepicker">
           <label for="Date_of_Birth">Date Of Birth</label>
        </div>
      </div>
      
      <div class="row">
        <div class="input-field col s6">
           <textarea id="Address" class="materialize-textarea"></textarea>
          <label for="Address">Address</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s6">
          <input id="Phone_No" type="tel" class="validate">
          <label for="Phone_No">Phone No</label>
        </div>
      </div>
    </form>
  </div>
    </center>
    </div>
@endsection

@section('footer')
    @include('footer')
@endsection


@section('script')
<script type="text/javascript">
   $('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15 // Creates a dropdown of 15 years to control year
  });
</script>
@endsection