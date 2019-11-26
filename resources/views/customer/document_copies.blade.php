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
              <th>Document</th>
              <th width="50%">File</th> 
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>BL</td>
              <td><?= (isset($document['bl']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['bl'].'">'.$document['bl'].'</a>' : '-') ?></td>
              <td>
            </tr>
            <tr>
              <td>2</td>
              <td>Invoice</td>
              <td><?= (isset($document['invoice']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['invoice'].'">'.$document['invoice'].'</a>' : '-') ?></td>
              <td>
            </tr>
            <tr>
              <td>3</td>
              <td>De-registration certificate</td>
              <td><?= (isset($document['registration_certificate']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['registration_certificate'].'">'.$document['registration_certificate'].'</a>' : '-') ?></td>
              <td>
            </tr>
            <tr>
              <td>4</td>
              <td>Inspection certificate</td>
              <td><?= (isset($document['inspection_certificate']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['inspection_certificate'].'">'.$document['inspection_certificate'].'</a>' : '-') ?></td>
              <td>
            </tr>
            <tr>
              <td>5</td>
              <td>Marine Insurance</td>
              <td><?= (isset($document['marine_insurance']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['marine_insurance'].'">'.$document['marine_insurance'].'</a>' : '-') ?></td>
              <td>
            </tr>
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
