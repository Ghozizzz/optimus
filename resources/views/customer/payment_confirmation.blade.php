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
          <h2>Invoice</h2>
        </div>
        <div>
          <hr>
        </div>
        <div class='admin-content'>
          <div width="100%">
            @include('customer.negotiation_menu')
          </div>
          
          <div class="height10"></div>
         
          @if(session()->has('message') && session()->get('message') !== '')
            <div class='alert alert-info'>
          <?php echo session()->get('message'); ?>
            </div>
          @endif
       
          <div class="height10"></div>
          <form method='post' action="{{route('customer.paymentConfirmationSave')}}" enctype="multipart/form-data" onsubmit="return checkValidation()">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class='container'>
              <div class='row'>
                <div class='col-md-9'>
                  <div class='row'>
                    <div class='col-md-12 header-pay-div'>
                      <b>Data Order</b>
                    </div>
                  </div>


                  <div class='row padding5'>
                    <div class='col-md-4'>
                      <span class="background1 inv-sub-span">Invoice Number</span>
                    </div>
                    <div class='col-md-8'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input type="text" class="form-control" name="invoice-number" id="invoice-number" readonly="true" value="{{isset($invoice->invoice_number)?$invoice->invoice_number:''}}">
                        </div>                      
                      </div>

                    </div>
                  </div>

                  <div class='row'>
                    <div class='col-md-12 header-pay-div'>
                      <b>Data Payment</b>
                    </div>
                  </div>

                  <div class='row padding5'>
                    <div class='col-md-4'>
                      <span class="background1 inv-sub-span">Transfer Date</span>
                    </div>
                    <div class='col-md-8'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input class='form-control' type='text' name='transfer-date' id="transfer-date" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" autocomplete="off">
                        </div>                      
                      </div>

                    </div>
                  </div>

                  <div class='row padding5'>
                    <div class='col-md-4'>
                      <span class="background1 inv-sub-span">Destination Bank</span>
                    </div>
                    <div class='col-md-8'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input type="text" class="form-control" name="destination-bank" id="destination-bank" value="">
                        </div>                      
                      </div>

                    </div>
                  </div>

                  <div class='row padding5'>
                    <div class='col-md-4'>
                      <span class="background1 inv-sub-span">Account Number</span>
                    </div>
                    <div class='col-md-8'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input type="text" class="form-control" name="account-number" id="account-number" value="">
                        </div>                      
                      </div>                    
                    </div>
                  </div>

                  <div class='row padding5'>
                    <div class='col-md-4'>
                      <span class="background1 inv-sub-span">Account Name</span>
                    </div>
                    <div class='col-md-8'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input type="text" class="form-control" name="account-name" id="account-name" value="">
                        </div>                      
                      </div>                    
                    </div>
                  </div>

                  <div class='row padding5'>
                    <div class='col-md-4'>
                      <span class="background1 inv-sub-span">Total Payment</span>
                    </div>
                    <div class='col-md-8'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input type="text" class="form-control" name="total-payment" id="total-payment" value="">
                        </div>                      
                      </div>                    
                    </div>
                  </div>

                  <div class='row padding5'>
                    <div class='col-md-4'>
                      <span class="background1 inv-sub-span">Upload</span>
                    </div>
                    <div class='col-md-8'>
                      <div class='row'>
                        <div class='col-md-6'>
                          <input type="file" name="picture" id="picture" class="form-control">
                        </div>                      
                      </div>                    
                    </div>
                  </div>
                </div>              
              </div>
              <div class='col-md-12' align='center'>              
                <input type="submit" class='btn btn-danger' value="Submit">
              </div>  
            </div>
          </form>
          <a href="{{route('customer.negotiation',['id'=>$session['negotiation']->id])}}"><button class="btn btn-danger" id='back-btn'>Back</button></a>
          <div class="height30"></div>
        </div>
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<script src="{{URL::to('/')}}/assets/js/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="{{URL::to('/')}}/assets/js/datetimepicker/locales/bootstrap-datetimepicker.id.js"></script>
<script>  
  
  $('#transfer-date').datetimepicker({
      language:  'en',
      weekStart: 1,
      todayBtn:  1,
      autoclose: 1,
      todayHighlight: 1,
      startView: 2,
      minView: 2,
      forceParse: 0
  });
  function checkValidation(){
    if($("#picture").val() == ''){
      alert('picture required');
      return false;
    }
    return true;
  }
</script>
@stop
