<table width="100%">
  <tr>
    <td width="12%" align="center">
      <?php
        if(isset($session['negotiation'])){
          $link = route('customer.negotiation',['id'=>$session['negotiation']->id]);
        }else{
          $link = '#';
        }
      ?>
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step1.png" alt="step1"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 1){?>
        <b>1. Negotiation</b>
        <?php }else{ ?>
          1. Negotiation
        <?php } ?>        
      </a> 
    </td>
    <td width="12%" align="center" class="order-process"> 
      <?php

        if(isset($session['proforma_invoice'])){
          $link = route('customer.proformainvoice',['id'=>$session['proforma_invoice']->id]);
        }else{
          $link = '#';
        }
      ?>
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step2.png" alt="step2"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 2){?>
        <b>2. Issue Performance</b>
        <?php }else{ ?>
          2. Issue Performance
        <?php } ?>        
      </a>
    </td>
    <td width="12%" align="center" class="order-process">
      <?php
        if(isset($session['invoice'])){
          $link = route('customer.invoice',['id'=>$session['invoice']->id]);
        }else{
          $link = '#';
        }
      ?>
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step3.png" alt="step3"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 3){?>
        <b>3. Order Item</b>
        <?php }else{ ?>
          3. Order Item
        <?php } ?>          
      </a>
    </td>
    <td width="12%" align="center" class="order-process">
      <?php
        if(isset($session['invoice'])){
          $link = route('customer.paymentConfirmation',['invoice_id'=>$session['invoice']->id]);
        }else{
          $link = '#';
        }
      ?>
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step4.png" alt="step4"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 4){?>
        <b>4. Payment Confirmation</b>
        <?php }else{ ?>
          4. Payment Confirmation
        <?php } ?>        
      </a>
    </td>
    <td width="12%" align="center" class="order-process">
      <?php
        if(isset($session['invoice'])){
          $link = route('customer.trackInvoice',['invoice_id'=>$session['invoice']->id]);
        }else{
          $link = '#';
        }
      ?>
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step5.png" alt="step5"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 5){?>
        <b>5. Shipping Schedule</b>
        <?php }else{ ?>
          5. Shipping Schedule
        <?php } ?>
        </a>
    </td>
    <td width="12%" align="center" class="order-process">
      <?php
        if(isset($session['invoice'])){
          $link = route('customer.documentCopies',['invoice_id'=>$session['invoice']->id]);
        }else{
          $link = '#';
        }
      ?>
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step6.png" alt="step6"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 6){?>
        <b>6. Document Copies</b>
        <?php }else{ ?>
          6. Document Copies
        <?php } ?>        
      </a>
    </td>
    <?php $link = route('customer.originalDocument'); ?> 
    <td width="12%" align="center" class="order-process">
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step7.png" alt="step7"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 7){?>
        <b>7. Send Original Document</b>
        <?php }else{ ?>
          7. Send Original Document
        <?php } ?>
      </a>
    </td>
    <td width="12%" align="center" class="order-process">
      <?php
        if(isset($session['invoice'])){
          $link = route('customer.receivedItem',['negotiation_id'=>$session['negotiation']->id]);
        }else{
          $link = '#';
        }
      ?>
      <a href="{{$link}}" class="order-process"><img src="{{URL::to('/')}}/assets/images/step8.png" alt="step8"><br>
        <?php if(isset($session['negotiation']) && $session['negotiation']->status == 8){?>
        <b>8. Item Received</b>
        <?php }else{ ?>
          8. Item Received
        <?php } ?>
      </a>
    </td>
</table>