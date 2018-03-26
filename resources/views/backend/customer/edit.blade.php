@extends('backend.layout')
@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Set hide player logo
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="{{ route('customer.index') }}">Members</a></li>
      <li class="active">Update</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <a class="btn btn-default btn-sm" href="{{ route('customer.index') }}" style="margin-bottom:5px">Back</a>    
    <form role="form" method="POST" action="{{ route('customer.update') }}">
    <div class="row">
      <!-- left column -->
      <input name="id" value="{{ $detail->id }}" type="hidden">
      <div class="col-md-7">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            Update
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
                      <div class="form-group">
                          <label>From</label>
                          <input type="text" class="form-control no-round datepicker" id="valid_from" name="valid_from" placeholder="" value="{{ old('valid_from', $detail->valid_from) }}">
                      </div>
                      <div class="form-group">
                          <label>To</label>
                          <input type="text" class="form-control no-round datepicker" id="valid_to" name="valid_to" placeholder="" value="{{ old('valid_to', $detail->valid_to) }}">
                      </div>                      
                  </div> 
            </div>                      
            <div class="box-footer">
              <button type="submit" class="btn btn-primary btn-sm">Save</button>
              <a class="btn btn-default btn-sm" href="{{ route('customer.index')}}">Cancel</a>
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
@stop
