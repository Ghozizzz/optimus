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
          <h2>Negotiation</h2>
        </div>
        <div>
          <hr>
        </div>
        <div class='admin-content'>
          @php
            $negotiation_id = isset($negotiation->id) ? $negotiation->id : '';
          @endphp
          @if($negotiation_id !== '')
            <div width="100%">
              @include('customer.negotiation_menu')
            </div>

            <div class='negotiation-term'>
              <h2>Negotiate with the Buyer by exchanging message</h2>
              <p>The following things should be discussed</p>
              <p>
                <ol>
                <li> Total Price : FOB Price (not including freight cost) or C&F Price (Cost & Freight),Insurance cost, preshop inspection fee etc<br>
                  <a href="#">Click here for calculating sales charge</a></li>
                <li> Payment term & payment date</li>
                <li> Condition of the item: If you have unsure points about the deal, please make sure before you confirm the order.</li>
                </ol>
              </p>
              <hr>
<!--              <h2>Next Action:</h2>
              <p>
                After above points and other conditions are agreed, please send proforma invoice by clicking on [Issue proforma invoice] and submit the form.              
              </p>-->
              @if($negotiation->status == '2' && empty($session['proforma_invoice']))
              <!--<p><a href="{{route('customer.save-proformainvoice',['negotiation_id' => $negotiation_id])}}"><button type="button" class="btn btn-danger">Issue Proforma Invoice</button></a></p>-->            
              @else
              <!--<p><button type="button" class="btn btn-danger" disabled='true'>Issue Proforma Invoice</button></p>-->
              @endif
            </div>

            <div class="height10"></div>

            <div class='chatbox'>
              <?php if(count($negotiation_line)>0){
                foreach($negotiation_line as $negotiation_line_detail){
                  if($negotiation_line_detail->customer_chat_id === $session['user_id']){?>
                    <div class="row" style="margin-top:5px;border-radius: 10px;">
                      <div class="col-md-1"></div>
                      <div class="col-md-11 user-chat-div" align='right'>
                        <b>Me</b>
                        <p class='chat-date'><?php echo date_format(date_create($negotiation_line_detail->createdon),"d M Y H:i:s") .' SG Time' ?></p>
                        
                        <div class="row">
                          <div class="col-md-12">                      
                              {!! nl2br($negotiation_line_detail->chat) !!}
                          </div>
                        </div>
                        <?php if($negotiation_line_detail->file !== ''){?>
                          <div class="row" align="center">
                            <div class="col-md-12">
                              attachment: <a href="{{URL::to('/')}}/uploads/negotiation/{{$negotiation_line_detail->file}}">{{$negotiation_line_detail->file}}</a>
                            </div>
                          </div>
                        <?php }?>
                        
                      </div>
                    </div>
                    
                  <?php }else{?>
                  <div class="row" style="margin-top:5px;border-radius: 10px;">
                      @if(in_array($negotiation_line_detail->negotiation_line_id,$unread_message))
                        <div class="col-md-11 sales-chat-div unread-negotiation">
                      @else 
                        <div class="col-md-11 sales-chat-div">
                      @endif
                      
                        <b>{{$negotiation_line_detail->sales_name}}</b>
                        <p class='chat-date'><?php echo date_format(date_create($negotiation_line_detail->createdon),"d M Y H:i:s") .' SG Time'?></p>
                        <div class="row">  
                          <div class="col-md-12">
                            {!! nl2br($negotiation_line_detail->chat) !!}
                          </div>                    
                        </div>
                        <?php if($negotiation_line_detail->file !== ''){?>
                          <div class="row" align="center">
                            <div class="col-md-12">
                              attachment: <a href="{{URL::to('/')}}/uploads/negotiation/{{$negotiation_line_detail->file}}">{{$negotiation_line_detail->file}}</a>
                            </div>
                          </div>
                        <?php }?>
                      </div>
                    </div>
                    
                  <?php }                
                  }              
                }?>
            </div>

            <div class='chat-input-div'>
            <div class='row'>
              <form action="{{route('customer.saveComment')}}" method="post" enctype="multipart/form-data"  >
                <input type="hidden" name="_token" value="{{ csrf_token() }}" /> 
                <input type="hidden" name="negotiation_id" value="{{$negotiation->id}}">
              <div class='col-md-12 col-xs-12'>
                <label>Comment</label>
                <textarea class="form-control" placeholder="Type your message here" name='chat-content' id='chat-content'></textarea>
              </div>
              <div class='col-md-12 col-xs-12'>
                <div class="row">
                  <div class="col-md-12">
                    <label>Attach File (optional)</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <input type="file" name="picture">
                  </div>
                </div>
                <small>*WORD, EXCEL, BITMAP, JPEG, PNG, TIFF, ZIP, PDF format only (5MB Max)  </small>
              </div>
              
              <div class='col-md-12 chat-btn-div'>                
                <input class='btn btn-danger send-chat-btn' type='submit' name='submit' value='Reply'>
              </div>
              </form>
            </div>
          </div>
          
          @else
            <table>
            <tr>
              <td>@if($car->new_negotiation == 0) You ever make a negotiation for this car @endif</td>
            </tr>
            <tr>
              <td>
                <img src="{{URL::to('/')}}/uploads/car/{{$car->picture}}" alt="{{$car->description}}" width='250px'>
              </td>
            </tr>
            <tr>
              <td>
                {!! $car->description !!}
              </td>
            </tr>
            
            <tr>
              <td>
                {!! $car->currency.' '. \App\Http\Controllers\API::currency_format($car->price) !!}
              </td>
            </tr>
            
            <tr>
              <td>
                <form action="{{route('customer.createNegotiate')}}" method="post">
                  <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                  <input type="hidden" name="car_id" value="{{$car->id}}">
                  <input type="hidden" name="insurance" value="{{$car->insurance}}">
                  <input type="hidden" name="inspection" value="{{$car->inspection}}">
                  <input type="hidden" name="currency" value="{{$car->currency}}" size="5">
                  <input type="hidden" name="destination_port" value="{{$car->destination_port}}">
                  <input type="hidden" name="logistic_id" value="{{$car->logistic_id}}">
                  <input type="hidden" name="chat" value="{{$car->chat}}">                 
                  <input type="hidden" name="shipping_price" value="{{isset($car->shipping_price) ? $car->shipping_price : 0}}">                 
                  <input type="hidden" name="original_price" value="{{isset($car->original_price) ? $car->original_price : 0}}">                 
                  <input type="hidden" name="ocean_freight" value="{{isset($car->ocean_freight) ? $car->ocean_freight : 0}}"> 
                  <input type="hidden" name="vprice" value="{{ isset($car->vprice) ? $car->vprice : 0 }}">                
                  <div class="row">
                    <div class="col-md-12">
                      @if($car->insurance == 1)
                        <span class='glyphicon glyphicon-ok'> insurance
                      @else
                        <span class='glyphicon glyphicon-remove'> insurance
                      @endif
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">                      
                      @if($car->inspection == 1)
                        <span class='glyphicon glyphicon-ok'> Pre-ship Inspection
                      @else
                        <span class='glyphicon glyphicon-remove'> Pre-ship Inspection
                      @endif
                    </div>
                  </div>
                  <div class='row'>
                    <div class='col-md-12'>&nbsp;</div>
                  </div>  
                  <div class='row'>
                    <div class='col-md-12'><b>Detail Price</b></div>
                  </div>  
                  <div class='row'>
                    <div class='col-md-12'>
                      <table>
                        <tr>
                          <td width='200px'>
                            Car Price :
                          </td>
                          <td width='50px'>
                            <b>USD </b> 
                          </td>
                          <td width='100px' align='right'>
                            <b>{{isset($car->original_price) ? \App\Http\Controllers\API::currency_format($car->original_price) : 0}}</b>
                          </td>
                        </tr>
                        
                        @if($car->inspection == 1)
                          <tr>
                            <td>
                              Car Inspection :
                            </td>
                            <td width='50px'>
                              <b>USD </b> 
                            </td>
                            <td width='100px' align='right'>
                              <b>{{isset($car->inspection_fee) ? \App\Http\Controllers\API::currency_format($car->inspection_fee) : 0}}</b>
                            </td>
                          </tr>
                        @endif
                        
                        @if($car->insurance == 1)
                          <tr>
                            <td>
                              Marine Insurance :
                            </td>
                            <td width='50px'>
                              <b>USD </b> 
                            </td>
                            <td width='100px' align='right'>
                              <b>{{isset($car->insurance_fee) ? \App\Http\Controllers\API::currency_format($car->insurance_fee) : 0}}</b>
                            </td>
                          </tr>
                        @endif
                        
                        <tr>
                          <td>
                            Ocean Freight :
                          </td>
                          <td width='50px'>
                            <b>USD </b> 
                          </td>
                          <td width='100px' align='right'>
                            <b>{{\App\Http\Controllers\API::currency_format(($car->vprice == 0) ? $car->shipping_price : $car->shipping_price + $car->ocean_freight)}}</b>
                          </td>
                        </tr>
                          
                        <tr style='border-top:solid 1px;'>
                          <td>
                            Estimated Car Price :
                          </td>
                          <td width='50px'>
                            <b>USD </b> 
                          </td>
                          <td width='100px' align='right'>
                            <b>{{ \App\Http\Controllers\API::currency_format($car->price) }}</b>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class='row'>
                    <div class='col-md-12'><br><br></div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">                      
                      Negotiate Price: {{$car->currency}} <input type="text" name="price" value="{{$car->price}}" size="10" @if($car->new_negotiation==0) readonly="true" @endif >
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      @if($car->new_negotiation == 0) 
                        <button class='btn btn-danger'>View Negotiation</button>
                      @else
                        <button class='btn btn-danger top10'>Create Negotiation</button>
                      @endif
                    </div>
                  </div>
                </form>
              </td>
            </tr>
          </table>
          @endif
          
        </div>
      </div>  
    </div>
  </div>
  
</div>
<!-- /.row -->

@stop


@section('script')
<script>  
  var chat_id = '{{$negotiation_id}}';
  $(".send-chat-btn").on('click',function(){
    
  })

  function getChat(){
    var requestData = {
      id : chat_id,
    };
    
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': token,
        },
        url: "{{route('customer.getChat')}}",
        data: requestData,
        type: 'post',
        dataType: 'json',
        beforeSend:function(){
        },
        success: function (result) {
        }
    });
  }
  
  function sendChat(){
    var requestData = {
      id : chat_id,
      chat_content : $("#chat-content").val(),
    };
    
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': token,
        },
        url: "{{route('customer.sendChat')}}",
        data: requestData,
        type: 'post',
        dataType: 'json',
        beforeSend:function(){
        },
        success: function (result) {
        }
    });
  }
  
  
</script>
@stop
