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
     <table class="striped">
        

        <tbody>
          <tr>
            <td>Patient Id</td>
            <td>{{ $patient['patient_id'] }}</td>
            
          </tr>
          <tr>
            <td>Patient_Name</td>
            <td>{{ $patient['name'] }}</td>
            
          </tr>
          <tr>
            <td>Sex</td>
            <td>{{$patient['sex']}}</td>
            
          </tr>
          <tr>
            <td>Date_Of_Birth</td>
            <td>{{$patient['dob']}}</td>
            
          </tr>
          <tr>
            <td>Doctor</td>
            <td>{{ $patient['doctor'] }}</td>
            
          </tr>

        </tbody>
      </table>
      <h3>Reports</h3>
       <table>
        <thead>
          <tr>
              <th data-field="Date">Date</th>
              <th data-field="Diagnosis">Diagnosis</th>
              <th data-field="Reports">Reports</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>Alvin</td>
            <td>Eclair</td>
            <td>$0.87</td>
          </tr>
          <tr>
            <td>Alan</td>
            <td>Jellybean</td>
            <td>$3.76</td>
          </tr>
          <tr>
            <td>Jonathan</td>
            <td>Lollipop</td>
            <td>$7.00</td>
          </tr>
        </tbody>
      </table>
    </center>
    </div>
@endsection

@section('footer')
    @include('footer')
@endsection
