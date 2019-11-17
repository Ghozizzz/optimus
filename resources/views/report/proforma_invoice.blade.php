<html>
  <head>
    <style>
    body{
      font-size:10px;
    }
    th.tbl{
      font-size:9px;
      line-height: 5px;
    }
    .term{
      font-size:8px;
    }
    </style>
  </head>
  <body>
    <table>
      <tr>
        <td>
          <img src="assets/css/logo.png" width="100px">
        </td>
        <td>
          <b>Optimus Auto Trading Pte Ltd</b><br>
          33 Ubi Avenue 3 #01-09 Vertex<br>
          Singapore 408868<br>
          (65) 6570 9482<br>
        </td>
      </tr>
    </table> 

    <?php
      $createdon = substr($session['proforma_invoice']->createdon,0,10)
    ?>
    <table width="100%" cellpadding="10" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="3">
          <font color="#000099">PERFORMA INVOICE</font>
        </td>
      </tr>
      <tr>
        <td class="tbl" width="30%"><b>CONSIGNEE ADDRESS : </b></td>
        <td class="tbl" width="30%"><b>NOTIFY PARTY : </b></td>
        <td class="tbl" width="50%"><b>QUOTE/SALE NO. :</b>{{$session['proforma_invoice']->proforma_invoice_number}}</td>
      </tr>
      <tr>
        <td class="tbl">
          {{$session['proforma_invoice']->company_name}}<br>
          {{$session['proforma_invoice']->address}}<br>
          {{$session['proforma_invoice']->city}}<br>
        </td>
        <td></td>
        <td width="50%"><b>Date : </b>{{$createdon}}</td>
      </tr>      
    </table>
    <table>
      <tr>
        <td colspan="5"><hr></td>
      </tr>
      <tr>
        <td colspan="5">
          <table>
            <tr>
              <td style="background-color:#DFDFDF" width="70%">DESCRIPTION</td>
              <td style="background-color:#DFDFDF" width="10%">QTY</td>
              <td style="background-color:#DFDFDF" width="20%">AMOUNT</td>
            </tr>
            <tr>
              <td>
                @if($car->state == '2')
                  {{'USED VEHICLE'}}
                @elseif($car->state == '1')
                  {{'NEW VEHICLE'}}
                @elseif($car->state == '3')
                  {{'BROKEN VEHICLE'}}
                @endif
                <br>
                <b>{{$car->manufacture_year.' '.$car->make.' '.$car->model}}</b><br>
                Registration Date : {{ date('d F Y', strtotime($car->registration_date))}}<br>
                Exterior color : {{$car->exterior_color.', '.\App\Http\Controllers\API::currency_format($car->engine).' CC, '.$car->car_transmission_description.', '.$car->fuel}}<br>
                {{'Chassis number : ' . $car->vin}}<br>
                {{'Steering : '}} {{ $car->steering == '1' ? 'left ' : 'right '}}, {{' Door : '.$car->door}}, {{' Seat : '.$car->seat}}
                {{'Mileage : '. \App\Http\Controllers\API::currency_format($car->distance) .' km'}}
                <br>
                <?php $dimesion = explode(',',$car->dimension); ?> 
                {{'Dimension : '. (isset($dimesion[0])? $dimesion[0].' cm x ' : '') .  (isset($dimesion[1])? $dimesion[1].' cm x ' : '') . (isset($dimesion[2])? $dimesion[2].' cm' : '') }}          
              </td>
              <td align='center'>1</td>
              <td>{{number_format($session['proforma_invoice']->total_amount,2,'.',',')}}</td>
            </tr>
          </table>
        </td>
        </tr>
      <tr>
        <td colspan="5"></td>
      </tr>
      <tr>
        <td colspan="5">
          @if($session['proforma_invoice']->incoterm == 'FOB')
            {{$session['proforma_invoice']->incoterm}} {{$session['proforma_invoice']->currency}} {{number_format($session['proforma_invoice']->total_amount,2,'.',',')}}
          @elseif($session['proforma_invoice']->incoterm == 'C&F')
            {{$session['proforma_invoice']->incoterm}} <?php echo isset($destination_port[0]->port_name) ? $destination_port[0]->port_name : '' ?> 
            
            @if($session['proforma_invoice']->inspection == 1)
              with {{$session['proforma_invoice']->inspection_value}}
            @endif
            
            {{$session['proforma_invoice']->currency}} {{number_format($session['proforma_invoice']->total_amount,2,'.',',')}}
            
            @if($session['proforma_invoice']->inspection == 1)
            <br>
              pre-shipment inspection USD {{number_format($session['negotiation']->inspection_fee,2,'.',',')}}
            @endif
            
          @else 
            
            {{$session['proforma_invoice']->incoterm}} <?php echo isset($destination_port[0]->port_name) ? $destination_port[0]->port_name : '' ?> 
            
            with marine insurance 
            
            @if($session['proforma_invoice']->inspection == 1)
              and {{$session['proforma_invoice']->inspection_value}}
            @endif
            
            {{$session['proforma_invoice']->currency}} {{number_format($session['proforma_invoice']->total_amount,2,'.',',')}}  
            
            <br>
            Marine insurance {{$session['proforma_invoice']->currency}} {{number_format($session['negotiation']->insurance_fee,2,'.',',')}} 
            
            @if($session['proforma_invoice']->inspection == 1)
              <br>
              pre-shipment inspection USD {{number_format($session['negotiation']->inspection_fee,2,'.',',')}}
            @endif
          @endif
        </td>
      </tr>
      <tr>
        <td colspan="5">
          @if($session['proforma_invoice']->incoterm == 'FOB')
            <!-- do nothing -->
          @else 
            Ocean freight : {{$session['proforma_invoice']->currency}} {{number_format(round($session['proforma_invoice']->shipping_price + $session['negotiation']->ocean_freight_fee),2,'.',',')}}
          @endif
        </td>
      </tr>
      @if($session['negotiation']->inspection == 1)
<!--      <tr>
        <td colspan="5">
          Car Inspection : USD {{number_format($session['negotiation']->inspection_fee,2,'.',',')}}
        </td>
      </tr>-->
      @endif
      @if($session['negotiation']->insurance == 1)
<!--      <tr>
        <td colspan="5">
          Car Insurance : USD {{number_format($session['negotiation']->insurance_fee,2,'.',',')}}
        </td>
      </tr>-->
      @endif
      <tr>
        <td colspan="5">
          @if($session['proforma_invoice']->incoterm == 'FOB')
          <?php
            $car_price = $session['proforma_invoice']->total_amount - $session['negotiation']->ocean_freight_fee;
          ?>
            Car Price : USD {{number_format(round($car_price),2,'.',',')}}
          @elseif($session['proforma_invoice']->incoterm == 'FOB')
            
            <?php 
            $car_price = $session['proforma_invoice']->total_amount - $session['negotiation']->insurance_fee - $session['proforma_invoice']->shipping_price - $session['negotiation']->ocean_freight_fee;
            if($session['proforma_invoice']->inspection == 1){
              $car_price -= $session['negotiation']->inspection_fee;
            }?>
            
            Car Price : USD {{number_format(round($car_price),2,'.',',')}}
          @else
          <!--cif-->
            
            <?php
              $car_price = $session['proforma_invoice']->total_amount - $session['negotiation']->insurance_fee - $session['proforma_invoice']->shipping_price - $session['negotiation']->ocean_freight_fee;
              
              if($session['proforma_invoice']->inspection == 1) {
                $car_price -= $session['negotiation']->inspection_fee;
              }
            ?>
            
            Car Price : USD {{number_format(round($car_price),2,'.',',')}}
          @endif
        </td>
      </tr>
      <tr>
        <td colspan="5"></td>
      </tr>
      <tr>
        <td colspan="5"></td>
      </tr>
      <tr>
        <td></td>
        <td>SUBTOTAL</td>
        <td colspan="3" align="right">{{number_format($session['proforma_invoice']->total_amount,2,'.',',')}}</td>        
      </tr>
      <tr>
        <td></td>
        <td>GST TOTAL</td>
        <td colspan="3" align="right">{{number_format(00,2,'.',',')}}</td>        
      </tr>
      <tr>
        <td></td>
        <td>TOTAL</td>
        <td colspan="3" align="right"><b>{{$session['proforma_invoice']->currency}} {{number_format($session['proforma_invoice']->total_amount,2,'.',',')}}</b></td>        
      </tr>
    </table>
    <br><br><br><br><br>
    <p class="term">
      1. Price quoted is only valid for 2 days and subject to change without prior notice<br>
      2. Payment must be received by the agreed date (within 2 days), otherwise the order will be<br>
      automatically become invalid and deposit may be forfeited<br>
      3. Seller has the right to knock off the payment against the order which due earlier<br>
      4. Always quote the Order No for all payment made<br>
      5. All payment is to be made payable to Optimus Auto Trading Pte Ltd<br>
      6. Bank charges shall be borned by Buyer. Only the actual amount received will be taken as payment<br>
      7. Cancellation is not allowed once Proforma Invoice is confirmed, unless with agreement from the<br>
      Seller reserves the right to forfeit deposit paid<br>
    </p>
    <br><br><br><br>
    <table>
      <tr>
        <td>Bank</td>
        <td width="10px">:</td>
        <td>{{\App\Http\Controllers\API::getSetting('bank name')}}</td>
        <td></td>
      </tr>
      <tr>
        <td>Account Name</td>
        <td>:</td>
        <td>{{ \App\Http\Controllers\API::getSetting('bank account name') }}</td>
        <td></td>
      </tr>
      <tr>
        <td>Account Num</td>
        <td>:</td>
        <td>{!! \App\Http\Controllers\API::getSetting('bank account number') !!}</td>
        <td></td>
      </tr>
      <tr>
        <td>Swift Code</td>
        <td>:</td>
        <td>{{\App\Http\Controllers\API::getSetting('bank swift code')}}</td>
        <td></td>
      </tr>
      <tr>
        <td>Bank Code</td>
        <td>:</td>
        <td>{{\App\Http\Controllers\API::getSetting('bank code')}}</td>
        <td></td>
      </tr>
      <tr>
        <td>Bank Address</td>
        <td>:</td>
        <td>{!! \App\Http\Controllers\API::getSetting('bank address') !!}</td>
        <td></td>
      </tr>
      <tr>
        <td>Office Address</td>
        <td>:</td>
        <td>{!! \App\Http\Controllers\API::getSetting('office address') !!}</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>Singapore 408868</td>
        <td></td>
      </tr>
    </table>
  </body>
</html>