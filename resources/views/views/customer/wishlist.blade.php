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
          <h2>My Wishlist</h2>
        </div>
        <div>
          <hr>
        </div>
        <div class='admin-content'>
          <table>
            @foreach($wishlist_car['car'] as $wishlist_car_detail)
            <tr>
              <td>
                <?php 
                $picture = json_decode($wishlist_car_detail->picture);
                ?>
                <img src="{{URL::to('/')}}/uploads/car/{{$picture[0]->picture}}" alt="{{$wishlist_car_detail->description}}" width='250px'>
              </td>
              <td>
                <b>{!! $wishlist_car_detail->description !!}</b>
                  <div>
                    <a class="btn btn-primary" href="{{route('front.productdetail',['id'=>$wishlist_car_detail->id])}}">View</a>
                  </div>
            </tr>
            @endforeach
          </table>
          
          <?php
          $this_page = isset($_GET['page'])?$_GET['page']:1;
          $limit = 5;
          $offset = ($this_page-1) * $limit;
          $total_page = ceil($wishlist_car['total']/$limit);
          ?>
          
          @if($wishlist_car['total'] >0)
          <div class='col-md-12' align='center'>
            <?php if($this_page>1){ 
              $page = $this_page-1;
              ?>
            <a href='{{route('customer.wishlist').'?page='.$page}}'><button class='btn btn-danger'><<</button></a>
            <?php }?>

            <?php if($this_page>1){ 
             $page = $this_page-1;
             ?>
            <a href='{{route('customer.wishlist').'?page='.$page}}'><button class='btn btn-danger'>{{$page}}</button></a>
            <?php }?>

            <button class='btn btn-danger' disabled="true">{{$this_page}}</button>

            <?php if($total_page>$this_page){
              $page = $this_page+1;
              ?>
            <a href='{{route('customer.wishlist').'?page='.$page}}'><button class='btn btn-danger'>{{$page}}</button></a>
            <?php }?>

            <?php if($total_page>$this_page){
              $page = $this_page+1;
              ?>
            <a href='{{route('customer.wishlist').'?page='.$page}}'><button class='btn btn-danger'>>></button></a>
            <?php }?>
          </div>
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
  

</script>
@stop
