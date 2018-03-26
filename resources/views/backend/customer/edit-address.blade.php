@extends('backend.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Chỉnh sửa địa chỉ 
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="{{ route('customer.index') }}">Chỉnh sửa địa chỉ </a></li>
      <li class="active">Chỉnh sửa</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <a class="btn btn-default btn-sm" href="{{ route('customer.address', $customer->id) }}" style="margin-bottom:5px">Quay lại</a>    
    <form role="form" method="POST" action="{{ route('customer.update-address') }}">
    <div class="row">
      <!-- left column -->
      <input name="id" value="{{ $detail->id }}" type="hidden">
      <div class="col-md-7">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            Chỉnh sửa
          </div>
          <!-- /.box-header -->               
            {!! csrf_field() !!}

            <div class="box-body">
              @if(Session::has('message'))
              <p class="alert alert-info" >{{ Session::get('message') }}</p>
              @endif
              @if (count($errors) > 0)
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif                
                                           
                
             <div class="other-address">
                      <div class="row">
                      <input type="hidden" name="id" value="{{ $detail->id }}">
                      <input type="hidden" name="customer_id" value="{{ $detail->customer_id }}">
                          <div class="form-group col-md-12">
                            <input type="text" class="form-control no-round req" id="fullname" name="fullname" placeholder="Họ tên" value="{{ $detail->fullname }}">
                          </div>
                          <div class="form-group col-md-12">
                            <input type="text" class="form-control no-round req" id="phone" name="phone" placeholder="Số điện thoại" value="{{ $detail->phone }}">
                          </div>
                          <div class="form-group col-md-12">
                            <input type="email" class="form-control no-round" id="email" name="email" placeholder="Email" value="{{ $detail->email }}">
                          </div>
                      </div>
                      
                      <div class="div-parent" id="primary_address">
                        <div class="row">
                            <div class="col-md-12">
                                <select class="form-group form-control city_id req" name="city_id" id="city_id">
                                    <option>Tỉnh/TP</option>
                                    @foreach($cityList as $city)
                                      <option value="{{$city->id}}"
                                      @if($detail->city_id == $city->id)
                                      selected
                                      @endif
                                      >{{$city->name}}</option>
                                    @endforeach                                
                                                                    </select>
                            </div>
                            <div class="col-md-12">
                                <select class="form-group form-control no-round district_id req" name="district_id" id="district_id">
                                    <option>Quận/Huyện</option>                              
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <select class="form-control no-round ward_id req" name="ward_id" id="ward_id">
                                    <option>Phường/Xã</option>
                                </select>
                            </div>
                        </div>                    
                      </div>
                      <div class="form-group row">
                          <div class="col-md-12"><input type="text" class="form-control no-round req" id="address" name="address" placeholder="Địa chỉ" value="{{ $detail->address }}"></div>
                      </div>                      
                  </div> 
            </div>                      
            <div class="box-footer">
              <button type="submit" class="btn btn-primary btn-sm">Lưu</button>
              <a class="btn btn-default btn-sm" href="{{ route('customer.address', $customer->id)}}">Hủy</a>
            </div>
            
        </div>
        <!-- /.box -->     

      </div>
      <div class="col-md-5">
        
      <!--/.col (left) -->      
    </div>
    </form>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>


@stop
@section('js')
<script type="text/javascript">

    
$(document).ready(function(){
  $('.city_id').change(function(){         
        var obj = $(this);
            $.ajax({
              url : '{{ route('get-child') }}',
              data : {
                mod : 'district',
                col : 'city_id',
                id : obj.val()
              },
              type : 'POST',
              dataType : 'html',
              success : function(data){
                obj.parents('.div-parent').find('.district_id').html(data);      
              }
            });
          
        });
  @if($detail->city_id)
  $.ajax({
              url : '{{ route('get-child') }}',
              data : {
                mod : 'district',
                col : 'city_id',
                id : {{ $detail->city_id }}
              },
              type : 'POST',
              dataType : 'html',
              success : function(data){
                $('#district_id').html(data).val({{ $detail->district_id }});      

              }
            });
  @endif
   @if($detail->district_id)
  $.ajax({
              url : '{{ route('get-child') }}',
              data : {
                mod : 'ward',
                col : 'district_id',
                id : {{ $detail->district_id }}
              },
              type : 'POST',
              dataType : 'html',
              success : function(data){
                $('#ward_id').html(data).val({{ $detail->ward_id }});      

              }
            });
  @endif
  $('#district_id').change(function(){         
        var obj = $(this);
            $.ajax({
              url : '{{ route('get-child') }}',
              data : {
                mod : 'ward',
                col : 'district_id',
                id : obj.val()
              },
              type : 'POST',
              dataType : 'html',
              success : function(data){
                $('#ward_id').html(data);                                
              }
            });
          
        });
});
</script>
@stop
