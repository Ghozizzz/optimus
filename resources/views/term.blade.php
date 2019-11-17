@extends('layouts.master')

@section('content')
<div class="container">

  <div class='title'>
    <h1>Term</h1>
  </div> 


  <div class="about-content">
    {!! $term_condition !!}
  </div>  



</div>
@stop


@section('script')

@stop