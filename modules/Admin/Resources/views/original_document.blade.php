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
          @if(session()->has('message') && session()->get('message') !== '')
            <div class='alert alert-info'>
          <?php echo session()->get('message'); ?>
            </div>
          @endif
          
          <div class='row'>
            <div class='col-md-12'>
              Courier Details : Please tick enclosed document and enter courrier details
            </div>
          </div>
          <div class='row'>
            <div class='col-md-12'>
            <form name="upload_document" method="post" action="{{route('admin.documentOriginalSubmit')}}" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">B/L</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type="checkbox" name="bl" id="bl" <?php echo isset($document->is_bl) && $document->is_bl == 1 ? 'checked' : ''; ?>> Original B/L
                    </div>                      
                  </div>
                </div>
              </div>
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">Invoice</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type="checkbox" name="invoice" id="invoice" <?php echo isset($document->is_invoice) && $document->is_invoice == 1 ? 'checked' : ''; ?>> Invoice
                    </div>                      
                  </div>
                </div>
              </div>
              
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">De-registration certificate</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type="checkbox" name="registration_certificate" id="registration_certificate" <?php echo isset($document->is_registration_certificate) && $document->is_registration_certificate == 1 ? 'checked' : ''; ?>> Original De-registration certificate
                    </div>                      
                  </div>
                </div>
              </div>
              
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">Inspection certificate</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type="checkbox" name="inspection_certificate" id="inspection_certificate" <?php echo isset($document->is_inspection) && $document->is_inspection == 1 ? 'checked' : ''; ?>> Inspection certificate
                    </div>                      
                  </div>
                </div>
              </div>
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">Marine Insurance</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type="checkbox" name="marine_insurance" id="marine_insurance" <?php echo isset($document->is_insurance) && $document->is_insurance == 1 ? 'checked' : ''; ?>> Marine Insurance
                    </div>                      
                  </div>
                </div>
              </div>
              
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">Ship With *</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type="textbox" name="shipping" id="shipping" value="<?php echo isset($document->shipping_logistic) ? $document->shipping_logistic : ''; ?>" required="true"> <br>Ex: UPS, EMS, DHL
                    </div>                      
                  </div>
                </div>
              </div>
              
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">Tracking No *</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type="textbox" name="tracking_no" id="tracking_no" value="<?php echo isset($document->tracking_number)?$document->tracking_number:''; ?>" required="true"> 
                    </div>                      
                  </div>
                </div>
              </div>
              
              
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">Comment</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <textarea name="comment" id="comment"><?php echo isset($document->comment)?$document->comment:''; ?></textarea>
                    </div>                      
                  </div>
                </div>
              </div>
              
              <div class='row padding5'>
                <div class='col-md-4'>
                  <span class="background1 inv-sub-span">Attach file</span>
                </div>
                <div class='col-md-8'>
                  <div class='row'>
                    <div class='col-md-6'>
                      <input type='file' name='filename' id='filename'>
                      <?php 
                      if(isset($document->file) && $document->file !== ''){
                        echo '<a href="'.URL::to('/').'/uploads/documents/'.$document->file.'" target="_blank">download file</a>';
                      }
                      ?>
                    </div>                      
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-4"><a href="{{route('admin.negotiation.view',['id'=>$session['negotiation']->id])}}" class="btn btn-danger" id='back-btn'>Back</a></div>
                <div class="col-md-4"><button name="save" class="btn btn-danger" id="upload_btn">Save</button></div>
              </div>
            </form>
            </div>  
          </div>
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
