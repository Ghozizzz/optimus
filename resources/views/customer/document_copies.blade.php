@extends('layouts.master')
{!! Html::style('/assets/css/admin.css') !!}
@section('content')

<div class="container body-container">

  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('customer.left_menu')
    </div>

    <div class="col-md-10 right-menu-div">
      <div class='wraper'>
        <div class='admin-title'>
          <h2>Document Copies</h2>
        </div>
        <div>
          <hr>
        </div>
        <div class='admin-content'>
          <div width="100%">
            @include('customer.negotiation_menu')
          </div>
          
          <div class="height10"></div>
          
          
          
          <div class="height10"></div>
          <table class="table table-striped">
          <thead>
            <tr>
              <th width="10%">No</th>
              <th width="90%">File</th>              
            </tr>
          </thead>
          <tbody>
          @if(count($document)>0)
            
          <?php $i = 1; ?>
            @foreach($document as $d)
            <tr>
              <td>{{ $i }}</td>              
              <td><a href="{{URL::to('/')}}/uploads/documents/{{ $d->filename }}">{{ $d->filename }}</a></td>              
            </tr>
            <?php $i++ ?>
            @endforeach
          
        
          @endif
          </tbody>
          </table>
          <div class="height30"></div>
        </div>
        <div class="row">
          <div class='col-md-1 col-xs-1' align='center'><a href="{{route('customer.negotiation',['id'=>$session['negotiation']->id])}}"><button class="btn btn-danger bottom10" id='back-btn'>Back</button></a></div>
        </div>
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<script>  
 
</script>
@stop
