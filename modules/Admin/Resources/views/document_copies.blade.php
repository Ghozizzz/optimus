@extends('layouts.master')
{!! Html::style('/assets/css/admin.css') !!}
@section('content')

<div class="container body-container">

  <div class="row">
    <div class="col-md-2 left-menu-div">
      @include('admin::layouts.menu')
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
            @include('admin::negotiation_menu')
          </div>
          
          <div class="height10"></div>
          
          
          
          <div class="height10"></div>
          
          <form name="upload_document" method="post" action="{{route('admin.documentUpload')}}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="row">            
                <!-- <div class="col-md-2">File</div>
                <div class="col-md-4"><input type="file" name="filename" id="filename"></div>             -->
            </div>
            <div class="row">
              <div class="col-md-4"><button name="upload_btn" class="btn btn-danger" id="upload_btn">upload</button></div>
            </div>
          
            @if(session()->has('message') && session()->get('message') !== '')
              <div class='alert alert-info'>
            <?php echo session()->get('message'); ?>
              </div>
            @endif
            
            <table class="table table-striped">
            <thead>
              <tr>
                <th width="10%">No</th>
                <th width="70%">File</th>              
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><?= (isset($document['bl']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['bl'].'">'.$document['bl'].'</a>' : '-') ?></td>
                <td>BL</td>
                <td><input type="file" name="bl"></td>
              </tr>
              <tr>
                <td>2</td>
                <td><?= (isset($document['invoice']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['invoice'].'">'.$document['invoice'].'</a>' : '-') ?></td>
                <td>Invoice</td>
                <td><input type="file" name="invoice"></td>
              </tr>
              <tr>
                <td>3</td>
                <td><?= (isset($document['registration_certificate']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['registration_certificate'].'">'.$document['registration_certificate'].'</a>' : '-') ?></td>
                <td>De-registration certificate</td>
                <td><input type="file" name="registration_certificate"></td>
              </tr>
              <tr>
                <td>4</td>
                <td><?= (isset($document['inspection_certificate']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['inspection_certificate'].'">'.$document['inspection_certificate'].'</a>' : '-') ?></td>
                <td>Inspection certificate</td>
                <td><input type="file" name="inspection_certificate"></td>
              </tr>
              <tr>
                <td>5</td>
                <td><?= (isset($document['marine_insurance']) ? '<a href="'.URL::to('/').'/uploads/documents/'.$document['marine_insurance'].'">'.$document['marine_insurance'].'</a>' : '-') ?></td>
                <td>Marine Insurance</td>
                <td><input type="file" name="marine_insurance"></td>
              </tr>
            </tbody>
            </table>
          </form>
          <div class="height30"></div>
        </div>
        <div class="row">
          <div class='col-md-1 col-xs-1' align='center'>
            <a href="{{route('admin.negotiation.view',['id'=>$session['negotiation']->id])}}">
              <button class="btn btn-danger" id='back-btn'>Back</button>
            </a>
          </div>
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
