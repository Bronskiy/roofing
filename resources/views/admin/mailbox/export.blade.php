@extends('admin.layouts.master')

@section('content')

<div class="portlet box green">
  <div class="portlet-title">
    <div class="caption">Export</div>
  </div>
  <div class="portlet-body">
    {!! Form::open(['route' => 'mailbox.xls-export', 'method' => 'post', 'id' => 'form-with-validation', 'class' => 'form-horizontal']) !!}
    <h1>Choose Header/Footer for the PDF/XLS document</h1>
    <select name="headerFooter">
      <option value="0">Choose</option>
      @foreach ($documentheaders as $row)
      <option value="{{ $row->id }}">{{ $row->header_title }}</option>
      @endforeach
    </select>

    @foreach($data as $key)
    <h1>#{{ $loop->iteration }} - {{ $key['date'] }}</h1>
    <div class="test-information">
      <p class="for-testing">For testing <i class="fa fa-chevron-down" aria-hidden="true"></i></p>
      <div class="test-hidden">
        <pre>{{ $key['txt'] }}</pre>
        <pre>{{ $key['html']}}</pre>
      </div>
    </div>
    <table class="table table-responsive ">
      <thead>
        <th>#</th>
        <th>Name</th>
        <th>Phone(s)</th>
        <th>Time</th>
        <th>Roof Age</th>
        <th>Roof Type</th>
        <th>Address</th>
        <th>Export</th>
      </thead>
      <tbody>
        @foreach($key['row'] as $row)
        @if( $row['Name'] != '')
        <tr @if($loop->iteration  % 2 == 0)class="even-stripe"@endif>
          <td rowspan="2">{{ $loop->iteration }}</td>
          <td style="text-transform:  capitalize;">{{ $row['Name'] }}</td>
          <td>
            @foreach( $row['Phones'] as $phone)
            {{ preg_replace('/\s+/', '', $phone) }}<br />
            @endforeach
          </td>
          <td>{{ date('g:i a', strtotime($row['Time'])) }}</td>
          <td>{{ $row['Age'] }}</td>
          <td>{{ $row['Type'] }}</td>
          <td>{{-- $row['Zipcode'] --}}<input style="width:100%;" type="text" name="address_{{ $loop->parent->iteration }}{{ $loop->iteration }}" value="{{ $row['Address'] }}"> </td>
          <td rowspan="2"><input id="checkFileBox" name="chckFileBx_{{ $loop->parent->iteration }}{{ $loop->iteration }}" type="checkbox" value="exportToFile"></td>
        </tr>
        <tr @if($loop->iteration  % 2 == 0)class="even-stripe"@endif>
          <td colspan="6">
            <textarea name="notes_{{ $loop->parent->iteration }}{{ $loop->iteration }}" rows="3" cols="80">Notes: {{ $row['Notes'] }}</textarea>
            <div class="">
              <p class="lead-text">Open original text <i class="fa fa-chevron-down" aria-hidden="true"></i></p>
              <pre class="lead-pre">
                {{ preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "<!--1-->", $row['Lead']) }}
              </pre>
            </div>
          </td>
        </tr>
        <input type="hidden" name="loop_{{ $loop->parent->iteration }}{{ $loop->iteration }}" value="{{ $loop->iteration }}">
        <input type="hidden" name="name_{{ $loop->parent->iteration }}{{ $loop->iteration }}" value="{{ $row['Name'] }}">
        <input type="hidden" name="phones_{{ $loop->parent->iteration }}{{ $loop->iteration }}" value="@foreach( $row['Phones'] as $phone) {{ $phone }}<br /> @endforeach">
        <input type="hidden" name="time_{{ $loop->parent->iteration }}{{ $loop->iteration }}" value="{{ $row['Time'] }}">
        <input type="hidden" name="age_{{ $loop->parent->iteration }}{{ $loop->iteration }}" value="{{ $row['Age'] }}">
        <input type="hidden" name="type_{{ $loop->parent->iteration }}{{ $loop->iteration }}" value="{{ $row['Type'] }}">
        @endif
        @endforeach
      </tbody>
    </table>
    <style media="screen">
    .even-stripe {
      background: #f9f9f9;
    }
    .for-testing,
    .lead-text{
      color: #0872ff;
      font-size: 14px;
      font-weight: bold;
      border-bottom: 1px dotted #0872ff;
      cursor: pointer;
    }
    .test-hidden,
    .lead-pre{
      display: none;
    }
    </style>
    @endforeach
    <style media="screen">
      #download-pdf{

      }
      #download-xls{

      }
    </style>
    <div class="form-group">
      <div class="col-sm-10">
        <a class="btn btn-primary" id="download-pdf" href="#download-pdf"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download PDF</a>
        <a class="btn btn-primary" id="download-xls" href="#download-xls"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download XLS</a>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection

@section('javascript')
<script>
$(document).ready(function () {
  $(".lead-text").click(function(){
    $parent_box = $(this).closest('div');
    $parent_box.find(".lead-pre").toggle();
  });
  $(".for-testing").click(function(){
    $parent_box = $(this).closest('div.test-information');
    $parent_box.find(".test-hidden").toggle();
  });
  $("#download-pdf").click(function() {
    $(this).closest("form").attr('action', '/admin/mailbox/file-export');
    $(this).closest("form").submit();
  });
  $("#download-xls").click(function() {
    $(this).closest("form").attr('action', '/admin/mailbox/xls-export');
    $(this).closest("form").submit();
  });
});
</script>
@stop
