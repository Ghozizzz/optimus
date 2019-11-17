@extends('layouts.master')
{!! Html::style('/assets/css/admin.css') !!}
@section('content')

<div class="container body-container">

  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('customer.left_menu')
    </div>

    <div class="col-md-10 right-menu-div">
      
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<script>  
  

</script>
@stop
