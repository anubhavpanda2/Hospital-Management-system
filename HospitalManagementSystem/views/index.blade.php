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
    <img src="{{ URL::to('assets/images/index_high.jpg') }}">  
    <h4>  
    <div class="row">
      <div class="col s12 m6 l3 red"><p><a class="white-text modal-trigger" href="#patientModal">Patient</a></p></div>
      <div class="col s12 m6 l3 green"><p><a class="white-text modal-trigger" href="#doctorModal">Doctor</a></p></div>
      <div class="col s12 m6 l3 blue"><p><a class="white-text modal-trigger" href="#staffModal">Staff</a></p></div>
      <div class="col s12 m6 l3 brown"><p><a class="white-text modal-trigger" href="#nurseModal">Nurse</a></p></div>
    </div>
    </h4>
    </center>

    <div id="patientModal" class="modal">
      <div class="modal-content">
      <h3 class="red-text">Patient</h3>
      <div class="row">
      <input type="text" placeholder="Patient ID" name="patientID">
      <input type="date" class="datepicker">
      </div>      
      </div>
      <div class="modal-footer">
      <a href="#" class="waves-effect waves-green btn-flat" onClick="login('patient')">Login</a>
      <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">Cancel</a>
      </div>
    </div>

    <div id="doctorModal" class="modal">
      <div class="modal-content">
      <h3 class="green-text">Doctor</h3>
      <div class="row">
      <input type="text" placeholder="Doctor ID" name="doctorID">
      <input type="password" name="doctorPassword">
      </div>      
      </div>
      <div class="modal-footer">
      <a href="#" class="waves-effect waves-green btn-flat" onClick="login('doctor')">Login</a>
      <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">Cancel</a>
      </div>
    </div>

    <div id="staffModal" class="modal">
      <div class="modal-content">
      <h3 class="blue-text">Patient</h3>
      <div class="row">
      <input type="text" placeholder="Staff ID" name="staffID">
      <input type="password" name="staffPassword">
      </div>      
      </div>
      <div class="modal-footer">
      <a href="#" class="waves-effect waves-green btn-flat" onClick="login('staff')">Login</a>
      <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">Cancel</a>
      </div>
    </div>

    <div id="nurseModal" class="modal">
      <div class="modal-content">
      <h3 class="brown-text">Patient</h3>
      <div class="row">
      <input type="text" placeholder="Staff ID" name="staffID">
      <input type="password" name="nursePassword">
      </div>      
      </div>
      <div class="modal-footer">
      <a href="#" class="waves-effect waves-green btn-flat" onClick="login('nurse')">Login</a>
      <a href="#" class="waves-effect waves-red btn-flat modal-action modal-close">Cancel</a>
      </div>
    </div>

    </div>
@endsection

@section('footer')
    @include('footer')
@endsection

@section('script')
<script>
$(document).ready(function(){
  $('.modal-trigger').leanModal();
  d=new Date();
  $('.datepicker').pickadate({      
    selectYears: d.getYear()+1900-1920+1,
    onClose: function(){
      $(document.activeElement).blur();
    },
    formatSubmit: 'yyyy/mm/dd',
    min: [1920,0,1],
    max: [d.getYear()+1900,d.getMonth(),d.getDate()]
  });
})
</script>
@endsection
