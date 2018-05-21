@extends('admin.layouts.master')

@section('content')

<div class="row">
    <div class="col-sm-10 col-sm-offset-2">
        <h1>{{ trans('quickadmin::templates.templates-view_edit-edit') }}</h1>

        @if ($errors->any())
        	<div class="alert alert-danger">
        	    <ul>
                    {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                </ul>
        	</div>
        @endif
    </div>
</div>

{!! Form::model($accounts, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.accounts.update', $accounts->id))) !!}
<div class="form-group">
    {!! Form::label('name', 'Connection Name', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('name', old('name',$accounts->name), array('class'=>'form-control')) !!}

    </div>
</div>
<div class="form-group">
    {!! Form::label('host', 'Host', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('host', old('host',$accounts->host), array('class'=>'form-control')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('port', 'Port', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('port', old('port',$accounts->port), array('class'=>'form-control')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('encryption', 'Encryption', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('encryption', old('encryption',$accounts->encryption), array('class'=>'form-control')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('validate_cert', 'Validate Cert', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::hidden('validate_cert','') !!}
        {!! Form::checkbox('validate_cert', 1, $accounts->validate_cert == 1) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('username', 'Username', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('username', old('username',$accounts->username), array('class'=>'form-control')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('password', 'Password', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::password('password', array('class'=>'form-control')) !!}

    </div>
</div>

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
      {!! Form::submit(trans('quickadmin::templates.templates-view_edit-update'), array('class' => 'btn btn-primary')) !!}
      {!! link_to_route(config('quickadmin.route').'.accounts.index', trans('quickadmin::templates.templates-view_edit-cancel'), null, array('class' => 'btn btn-default')) !!}
    </div>
</div>

{!! Form::close() !!}

@endsection
