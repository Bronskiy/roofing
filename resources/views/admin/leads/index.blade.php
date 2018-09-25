@extends('admin.layouts.master')

@section('content')

<p></p>
@if ($leads->count())
<div class="portlet box green">
  <div class="portlet-title">
    <div class="caption">{{ trans('quickadmin::templates.templates-view_index-list') }}</div>
  </div>
  <div class="portlet-body">
    <table class="table table-striped table-hover table-responsive datatable" id="datatable">
      <thead>
        <tr>
          <th>
            {!! Form::checkbox('delete_all',1,false,['class' => 'mass']) !!}
          </th>
          <th>#</th>
          <th>Name</th>
          <th>Phone(s)</th>
          <th>Time</th>
          <th>Roof Age</th>
          <th>Roof Type</th>
          <th>Address</th>
          <th>Source Email Date</th>
          <th>Exp</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tbody>
        @foreach ($leads as $row)
        <tr>
          <td>
            {!! Form::checkbox('del-'.$row->id,1,false,['class' => 'single','data-id'=> $row->id]) !!}
          </td>
          <td>{{ $row->id }}</td>
          <td>
            <div class="percent100">
            {{ $row->lead_name }}
          </div>
          </td>
          <td>
            <div class="percent100">
            {{ $row->lead_phones }}
          </div>
        </td>
          <td>{{ $row->lead_time }}</td>
          <td>{{ $row->lead_roof_age }}</td>
          <td>{{ $row->lead_foor_type }}</td>
          <td>
            <div class="percent100">
            {{ $row->lead_address }}
          </div>
          </td>
          <td>
            @if(isset($row->inbox->inbox_date))
            <a href="/admin/inbox/{{ $row->inbox->id }}/edit">{{ $row->inbox->inbox_date }}</a>
            @endif
          </td>
          <td>
            {!! Form::checkbox('export-'.$row->id,1,false,['class' => 'single-export','data-id'=> $row->id]) !!}
          </td>

          <td>
            {!! link_to_route(config('quickadmin.route').'.leads.edit', trans('quickadmin::templates.templates-view_index-edit'), array($row->id), array('class' => 'btn btn-xs btn-info')) !!}
            {!! Form::open(array('style' => 'display: inline-block;', 'method' => 'DELETE', 'onsubmit' => "return confirm('".trans("quickadmin::templates.templates-view_index-are_you_sure")."');",  'route' => array(config('quickadmin.route').'.leads.destroy', $row->id))) !!}
            {!! Form::submit(trans('quickadmin::templates.templates-view_index-delete'), array('class' => 'btn btn-xs btn-danger')) !!}
            {!! Form::close() !!}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="clearfix"></div>
    <hr>
    <div class="row" style="margin-bottom: 20px;">
      <div class="col-md-12">
        {!! Form::open(['route' => 'mailbox.xls-export', 'method' => 'post', 'id' => 'massExport', 'class' => 'form-horizontal']) !!}
        <h1>Choose Header/Footer for the PDF/XLS document</h1>
        <select name="headerFooter">
          <option value="0">Choose</option>
          @foreach ($documentheaders as $row)
          <option value="{{ $row->id }}">{{ $row->header_title }}</option>
          @endforeach
        </select>
        <input type="hidden" id="send-to-export" name="toExport">
        {!! Form::close() !!}
      </div>
    </div>
    <div class="row" style="margin-bottom: 35px;">
      <div class="col-xs-12">
        <a class="btn btn-primary" id="download-pdf" href="#download-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download PDF</a>
        <a class="btn btn-primary" id="download-xls" href="#download-xls"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download XLS</a>
      </div>
    </div>
    <div class="clearfix"></div>
    <hr>
    <div class="row">
      <div class="col-xs-12">
        <button class="btn btn-danger" id="delete">
          {{ trans('quickadmin::templates.templates-view_index-delete_checked') }}
        </button>
      </div>
    </div>
    {!! Form::open(['route' => config('quickadmin.route').'.leads.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
    <input type="hidden" id="send" name="toDelete">
    {!! Form::close() !!}
  </div>
</div>
@else
{{ trans('quickadmin::templates.templates-view_index-no_entries_found') }}
@endif

@endsection

@section('javascript')
<script>
$(document).ready(function () {
  $('#delete').click(function () {
    if (window.confirm('{{ trans('quickadmin::templates.templates-view_index-are_you_sure') }}')) {
      var send = $('#send');
      var mass = $('.mass').is(":checked");
      if (mass == true) {
        send.val('mass');
      } else {
        var toDelete = [];
        $('.single').each(function () {
          if ($(this).is(":checked")) {
            toDelete.push($(this).data('id'));
          }
        });
        send.val(JSON.stringify(toDelete));
      }
      $('#massDelete').submit();
    }
  });
  $('#download-pdf, #download-xls').click(function () {

    if($(this).attr("id") == "download-pdf"){
      alert('PDF');
      $("#massExport").attr('action', '/admin/mailbox/file-export');
    }else if($(this).attr("id") == "download-xls"){
      alert('XLS');
      $("#massExport").attr('action', '/admin/mailbox/xls-export');
    }else{
      alert('PDF default');
      $("#massExport").attr('action', '/admin/mailbox/file-export');
    }
    var send = $('#send-to-export');
    var toExport = [];
    $('.single-export').each(function () {
      if ($(this).is(":checked")) {
        toExport.push($(this).data('id'));
      }
    });
    send.val(JSON.stringify(toExport));
    $('#massExport').submit();
  });
});
</script>
@stop
