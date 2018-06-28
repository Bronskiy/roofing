@extends('admin.layouts.master')

@section('content')
@if (! isset($data))
<h2>No emails</h2>
<p>Choose different date range</p>
<h4><a href="/admin/mailbox"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Mailbox</a></h4>
@else
<div class="portlet box green">
  <div class="portlet-title">
    <div class="caption">{{ trans('quickadmin::templates.templates-view_index-list') }}</div>
  </div>
  <div class="portlet-body">

    {!! Form::open(['route' => 'mailbox.export', 'method' => 'post', 'id' => 'form-with-validation', 'class' => 'form-horizontal']) !!}

    <table class="table table-striped table-hover table-responsive " id="">
      <thead>
        <tr>
          <th>#</th>
          <th>Email's Subject</th>
          <th>Email Received At</th>
          <th style="width: 50%">Email's Body</th>
          <th>Export</th>
        </tr>
      </thead>

      <tbody>
        @foreach($data as $key)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $key['subject'] }}</td>
          <td>{{ $key['date'] }}</td>
          <td>{{ str_limit(strip_tags($key['bodyText']), $limit = 300, $end = '...') }}</td>
          <td><input id="checkBox" name="chckBx_{{ $loop->iteration }}" type="checkbox" value="export"></td>
        </tr>
        <input type="hidden" name="date_{{ $loop->iteration }}" value="{{ $key['date'] }}">
        <input type="hidden" name="bodyText_{{ $loop->iteration }}" value="{{ $key['bodyText'] }}">
        <input type="hidden" name="bodyHtml_{{ $loop->iteration }}" value="{{ $key['bodyHtml'] }}">
        @endforeach


      </tbody>
    </table>

    <div class="form-group">
      <div class="col-sm-10">
        {!! Form::submit( 'Export' , array('class' => 'btn btn-primary')) !!}
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
@endif


@endsection

@section('javascript')
<script>
$(document).ready(function () {

});
</script>
@stop
