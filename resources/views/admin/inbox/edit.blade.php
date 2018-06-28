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

{!! Form::model($inbox, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'method' => 'PATCH', 'route' => array(config('quickadmin.route').'.inbox.update', $inbox->id))) !!}

<div class="form-group">
    {!! Form::label('inbox_sender', 'Sender', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('inbox_sender', old('inbox_sender',$inbox->inbox_sender), array('class'=>'form-control', 'disabled' => '')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('inbox_date', 'Date', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('inbox_date', old('inbox_date',$inbox->inbox_date), array('class'=>'form-control', 'disabled' => '')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('inbox_subject', 'Subject', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('inbox_subject', old('inbox_subject',$inbox->inbox_subject), array('class'=>'form-control', 'disabled' => '')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('inbox_text_body', 'Text Body', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::textarea('inbox_text_body', old('inbox_text_body',$inbox->inbox_text_body), array('class'=>'form-control', 'disabled' => '')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('inbox_html_body', 'HTML Body', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::textarea('inbox_html_body', old('inbox_html_body',$inbox->inbox_html_body), array('class'=>'form-control', 'disabled' => '')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('inbox_edited_body', 'Edited Body', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::textarea('inbox_edited_body', old('inbox_edited_body',$inbox->inbox_edited_body), array('class'=>'form-control')) !!}

    </div>
</div><div class="form-group">
    {!! Form::label('inbox_leads_count', 'Leads', array('class'=>'col-sm-2 control-label')) !!}
    <div class="col-sm-10">
        {!! Form::text('inbox_leads_count', old('inbox_leads_count',$inbox->inbox_leads_count), array('class'=>'form-control', 'disabled' => '')) !!}

    </div>
</div>

<div class="form-group">
    <div class="col-sm-10 col-sm-offset-2">
      {!! Form::submit(trans('quickadmin::templates.templates-view_edit-update'), array('class' => 'btn btn-primary')) !!}
      {!! link_to_route(config('quickadmin.route').'.inbox.index', trans('quickadmin::templates.templates-view_edit-cancel'), null, array('class' => 'btn btn-default')) !!}
    </div>
</div>

{!! Form::close() !!}

@endsection
