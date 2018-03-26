@extends('backend.layout')
@section('content')
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Members
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="{{ route( 'customer.index') }}">Members</a></li>
    <li class="active">List</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @if(Session::has('message'))
      <p class="alert alert-info" >{{ Session::get('message') }}</p>
      @endif     
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Filter</h3>
        </div>
        <div class="panel-body">
          <form class="form-inline" role="form" method="GET" action="{{ route('customer.index') }}" id="frmContact">               
            <div class="form-group">
              <label for="name">&nbsp;&nbsp;Full name :</label>
              <input type="text" class="form-control" name="fullname" value="{{ $fullname }}">
            </div>                                                 
            <div class="form-group">
              <label for="name">&nbsp;&nbsp;Email :</label>
              <input type="text" class="form-control" name="email" value="{{ $email }}">
            </div>            
            <button type="submit" class="btn btn-default btn-sm">Filter</button>
          </form>         
        </div>
      </div>
      <div class="box">

        <div class="box-header with-border">
          <h3 class="box-title">List ( <span class="value">{{ $items->total() }} members )</span></h3>
        </div>
        
        <!-- /.box-header -->
        <div class="box-body">        
          <!--<a href="{{ route('customer.export') }}" class="btn btn-info btn-sm" style="margin-bottom:5px;float:left" target="_blank">Export</a>-->
          <div style="text-align:center">
            {{ $items->appends( ['status' => $status, 'email' => $email, 'fullname' => $fullname] )->links() }}
          </div>  
          <table class="table table-bordered" id="table-list-data">
            <tr>
              <th style="width: 1%">#</th>                            
              <th>Full name</th>      
              <td>Email</td>                                
              <th width="10%">Created at</th>
              <th width="1%;white-space:nowrap">Action</th>
            </tr>
            <tbody>
            @if( $items->count() > 0 )
              <?php $i = 0; ?>
              @foreach( $items as $item )
                <?php $i ++; ?>
              <tr id="row-{{ $item->id }}">
                <td><span class="order">{{ $i }}</span></td>                       
                <td>                  
                  @if($item->fullname != '')
                  {{ $item->fullname }}</br>
                  @endif
                </td>
                <td>
                  @if($item->email != '')
                  <a href="{{ route( 'customer.edit', [ 'id' => $item->id ]) }}">{{ $item->email }}</a>
                  @endif
                </td>
                <td>{{ date('d-m-Y H:i', strtotime($item->created_at)) }}</td>
                <td style="white-space:nowrap;text-align: right">
                  <a href="{{ route('customer.edit', $item->id) }}" class="btn btn-sm btn-info" title="Set hide player logo"><i class="fa fa-money"></i></a>
                
                  <a onclick="return callDelete('{{ $item->email }}','{{ route( 'customer.destroy', [ 'id' => $item->id ]) }}');" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span></a>
                  
                </td>
              </tr> 
              @endforeach
            @else
            <tr>
              <td colspan="9">No data.</td>
            </tr>
            @endif

          </tbody>
          </table>
          <div style="text-align:center">
            {{ $items->appends( ['status' => $status, 'email' => $email, 'fullname' => $fullname] )->links() }}
          </div>  
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