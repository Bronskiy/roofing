@extends('admin.layouts.master')

@section('content')

<div class="portlet box green">
  <div class="portlet-title">
    <div class="caption">Export</div>
  </div>
  <div class="portlet-body">
  {!! Form::open(['route' => 'mailbox.file-export', 'method' => 'post', 'id' => 'form-with-validation', 'class' => 'form-horizontal']) !!}
    <h1>Choose Header/Footer for the PDF/XLS document</h1>
    <select>
      <option value="0">Choose</option>
      @foreach ($documentheaders as $row)
      <option value="{{ $row->id }}">{{ $row->header_title }}</option>
      @endforeach
    </select>

    @foreach($data as $key)
    <h1>#{{ $loop->iteration }} - {{ $key['date'] }}</h1>
    <p>{{ $key['txt'] }}</p>
    <table class="table table-striped table-hover table-responsive ">
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
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td style="text-transform:  capitalize;">{{ $row['Name'] }}</td>
          <td>
            @foreach( $row['Phones'] as $phone)
            {{ $phone }}<br />
            @endforeach
          </td>
          <td>{{ $row['Time'] }}</td>
          <td>{{ $row['Age'] }}</td>
          <td>{{ $row['Type'] }}</td>
          <td>{{ $row['Zipcode'] }}| {{ $row['Address'] }}</td>
          <td><input id="checkFileBox" name="chckFileBx_{{ $loop->iteration }}" type="checkbox" value="exportToFile"></td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endforeach
    <div class="form-group">
      <div class="col-sm-10">
        {!! Form::submit( 'Download' , array('class' => 'btn btn-primary')) !!}
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
@endsection

@section('javascript')
<script>
$(document).ready(function () {

});
</script>
@stop
