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
            <div class="row">            
                <div class="col-md-2">File</div>
                <div class="col-md-4"><input type="file" name="filename" id="filename"></div>            
            </div>
            <div class="row">
              <div class="col-md-4"><button name="upload_btn" class="btn btn-danger" id="upload_btn">upload</button></div>
            </div>
          </form>
          
          @if(session()->has('message') && session()->get('message') !== '')
            <div class='alert alert-info'>
          <?php echo session()->get('message'); ?>
            </div>
          @endif
          
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
              <td><a href="{{URL::to('/')}}/uploads/documents/{{ $d->filename }}" target="_blank">{{ $d->filename }}</a></td>              
            </tr>
            <?php $i++ ?>
            @endforeach
          
        
          @endif
          </tbody>
          </table>
          <div class="height30"></div>
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
