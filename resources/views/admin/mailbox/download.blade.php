<htmlpageheader name="pageHeader">
@if(! empty($headerFooter->header_top))
{!! $headerFooter->header_top !!}
@endif
<style>
  td{
    padding: 10px 10px;
  }
</style>
<table width="100%">
  <thead>
    <tr style="background: #e0e0e0;">
      <th>#</th>
      <th>Name</th>
      <th>Phone(s)</th>
      <th>Time</th>
      <th>Roof Age</th>
      <th>Roof Type</th>
      <th>Address</th>
    </tr>
  </thead>
  <tbody>
    @foreach($data as $row)
    <tr style="@if($loop->iteration  % 2 != 0) background: #e0e0e0; @else background: #fbfbfb; @endif">
      <td rowspan="2">{{ $loop->iteration }}</td>
      <td>{{ strtoupper($row['name']) }}</td>
      <td>{!! $row['phones'] !!}</td>
      <td>{{ strtoupper($row['time']) }}</td>
      <td>{{ strtoupper($row['age']) }}</td>
      <td>{{ strtoupper($row['type']) }}</td>
      <td>{{ strtoupper($row['address']) }}</td>
    </tr>
    <tr style="@if($loop->iteration  % 2 != 0) background: #e0e0e0; @else background: #fbfbfb; @endif">
      <td colspan="6">{{ $row['notes'] }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</htmlpageheader>
<htmlpagefooter name="pageFooter" style="display:none">
    <div style="text-align: right; padding-bottom: 10px; color: #666; font-size: 9px;">
      @if(! empty($headerFooter->header_top))
      {!! $headerFooter->header_bottom !!}
      @endif
        Page {PAGENO} from {nbpg}</td>
    </div>
</htmlpagefooter>
<sethtmlpageheader name="pageHeader" page="O" value="on" show-this-page="1" />
<sethtmlpagefooter name="pageFooter" value="ON" page="ALL" />
