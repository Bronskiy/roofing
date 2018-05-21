@extends('admin.layouts.master')

@section('content')

{!! Form::open(['route' => 'mailbox.list', 'method' => 'post', 'id' => 'form-with-validation', 'class' => 'form-horizontal']) !!}


<div class="form-group">
    {!! Form::label('account_select', 'Account', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::select('account_select', $accounts, old('account_select'), array('class'=>'form-control')) !!}

    </div>
</div>

<div class="form-group">
    {!! Form::label('cat_date', 'Date', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('cat_date', old('cat_date'), array('class'=>'form-control datepicker')) !!}

    </div>
</div>

<div class="form-group">
  {!! Form::label('from_email', 'Email', array('class'=>'col-sm-2 control-label')) !!}
  <div class="col-sm-10">
    {!! Form::email('from_email', old('from_email'), array('class'=>'form-control')) !!}

  </div>
</div>

<div class="form-group">
  <div class="col-sm-10 col-sm-offset-2">
    {!! Form::submit( 'Search' , array('class' => 'btn btn-primary')) !!}
  </div>
</div>

{!! Form::close() !!}

@endsection
