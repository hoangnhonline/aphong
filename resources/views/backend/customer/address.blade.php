@extends('backend.layout')
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Sổ địa chỉ của thành viên : <strong>{{ $order->fullname }}</strong>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route( 'customer.index') }}">Thành viên</a></li>
    <li class="active">Danh sách</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <a class="btn btn-default btn-sm" href="{{ route('customer.index') }}" style="margin-bottom:5px">Quay lại</a>
  <div class="row">
    <div class="col-md-12">
      @if(Session::has('message'))
      <p class="alert alert-info" >{{ Session::get('message') }}</p>
      @endif     
      
      <div class="box">

        <div class="box-header with-border">
          <h3 class="box-title">Danh sách ( <span class="value">{{ $addressList->count() }} địa chỉ )</span></h3>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">        
          <!--<a href="{{ route('customer.export') }}" class="btn btn-info btn-sm" style="margin-bottom:5px;float:left" target="_blank">Export</a>-->         
          <table class="table table-bordered" id="table-list-data">
            <tr>
              <th style="width: 1%">#</th>                            
              <th>Họ tên</th>
              <th>Số điện thoại</th>        
              <th>Địa chỉ</th>    
              <th width="1%;white-space:nowrap">Thao tác</th>
            </tr>
            <tbody>
            @if( $addressList->count() > 0 )
              <?php $i = 0; ?>
              @foreach( $addressList as $item )
                <?php $i ++; ?>
              <tr id="row-{{ $item->id }}">
                <td><span class="order">{{ $i }}</span></td>                       
                <td>                  
                  @if($item->fullname != '')
                  {{ $item->fullname }}</br>
                  @endif
                 @if($item->email != '')
                  <a href="{{ route( 'customer.edit', [ 'id' => $item->id ]) }}">{{ $item->email }}</a>
                  @endif
                 
                </td>   
                <td> @if($item->phone != '')
                  {{ $item->phone }}</br>
                  @endif</td>                
                            
                <td>{{ $item->address }}, {{ $item->ward_id ? Helper::getName($item->ward_id, 'ward') : "" }}, {{ $item->district_id ? Helper::getName($item->district_id, 'district') : "" }}, {{ $item->city_id ? Helper::getName($item->city_id, 'city') : "" }}</td>
                </td>           
                
                <td style="white-space:nowrap">                                  
                  <a href="{{ route('customer.edit-address', $item->id) }}" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-pencil"></span></a>                  
                </td>
              </tr> 
              @endforeach
            @else
            <tr>
              <td colspan="9">Không có dữ liệu.</td>
            </tr>
            @endif

          </tbody>
          </table>
          
        </div>        
      </div>
      <!-- /.box -->     
    </div>
    <!-- /.col -->  
  </div> 
</section>
<!-- /.content -->
</div>
@stop
@section('js')
<script type="text/javascript">
function callDelete(name, url){  
  swal({
    title: 'Bạn muốn xóa "' + name +'"?',
    text: "Dữ liệu sẽ không thể phục hồi.",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes'
  }).then(function() {
    location.href= url;
  })
  return flag;
}
$(document).ready(function(){
  $('#type').change(function(){
    $('#frmContact').submit();
  });
});
</script>
@stop