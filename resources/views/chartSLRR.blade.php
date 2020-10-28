@extends('welcome')
@section('content')

  <div class="container">
    
    <ul class="nav">
      <li class="nav-item">
        <a href="{{route('home')}}" class="nav-link active">HOME</a>
      </li>
      <li class="nav-item">
        <a href="{{route('import')}}" class="nav-link">IMPORT</a>
      </li>
    </ul>

    {{-- <input type="text" class="datepicker"> --}}
    <div id="graph" class="bg-light p-5 shadow mb-5"></div>
    <table class="table table-sm table-hover">
      <thead>
        <tr>
          <th>Datetime</th>
          <th>Sound Level</th>
          <th>Rain Rate</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($rpi_info as $rd)
          @if ($rd[1]>0 || $rd[2]>0)
        <tr>
          <td>{{ \Carbon\Carbon::createFromTimestampMs($rd[0])->isoFormat('MMM DD, YYYY HH:mm') }}</td>
          <td>{{ $rd[1] }}</td>
          <td>{{ $rd[2] }}</td>
        </tr>
          @endif
        @endforeach
      </tbody>
    </table>

  </div>











{{-- Begin Script --}}
<script type="text/javascript">

var url_string = window.location.href;
var url = new URL(url_string);
var c = url.searchParams.get("data");

console.log(c);
  $(function(){
    $( ".datepicker" ).datepicker({
      todayHighlight: true,
      autoclose: true,
    });

    var rpi_info = <?php echo json_encode($rpi_info,JSON_NUMERIC_CHECK); ?>,
        sound_data = [],
        rain_data = [],
        i = 0;

    for (i; i < rpi_info.length; i+=1){
      if(rpi_info[i][1]>0 && rpi_info[i][2]>0){
      sound_data.push([
        rpi_info[i][0],
        rpi_info[i][1],
      ]);
      rain_data.push([
        rpi_info[i][0],
        rpi_info[i][2],
      ]);
      }
    }

    Highcharts.stockChart('graph', {
      boost: { enabled:true, useGPUTranslations:true, usePreallocated:true, allowForce:true },
      plotOptions: { series: { showInNavigator: true, connectNulls:false, marker:{ enabled:true } } },
      legend: { enabled: true, align: 'center', layout: 'horizontal', verticalAlign: 'bottom' },
      scrollbar: { liveRedraw: false },
      chart: { backgroundColor: null },
      colors: ['#ff0000','#9a71ca'],
      title: { text:"Sensor Data" },
      xAxis: [{
        type: 'datetime',
        labels: { format: '{value:%b %d, %Y %H:%M}' }
      }],
      yAxis: [{
        minRange:22000,
        min:0,
        gridLineWidth: 0,
        plotBands: [
          { label: {  text: 'Light',      style: {color:'#555'} },
            from: 2000, to: 12000,         color: 'rgba(0, 0, 0, 0.1)' },
          { label: {  text: 'Moderate',   style: {color:'#555'} },
            from: 12000, to: 14000,         color: 'rgba(0, 0, 0, 0.1)' },
          { label: {  text: 'Heavy',    style: {color:'#555'} },
            from: 14000, to: 16500,         color: 'rgba(0, 0, 0, 0.1)' },
          { label: {  text: 'Intense',    style: {color:'#555'} },
            from: 15600, to: 18000,         color: 'rgba(245, 154, 24, 0.1)' },
          { label: {  text: 'Torrential', style: {color:'#555'} },
            from: 18000, to: 1000000000,        color: 'rgba(228, 27, 33, 0.1)' }
        ]},{
          title: { text: 'Frequency',          style: {color: Highcharts.getOptions().colors[0]} },
          labels: { format: '{value} Hz',        style: {color: Highcharts.getOptions().colors[0]} }
        // },
        // {
        //   min: 0,
        //   opposite: false,
        //   title: { text: 'Rain rate',            style: {color: Highcharts.getOptions().colors[1]} },
        //   labels: { format: '{value} mm/hr',     style: {color: Highcharts.getOptions().colors[1]} }
        }],
      tooltip: {
        pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b>{point.tooltip.valueSuffix}<br/>',
        xDateFormat: '%a, %b %d, %Y %H:%M',
        valueDecimals: 2,
        shared: true,
        split: false
      },
        data: {
          enablePolling:true,
          csvURL:"http://128.199.161.14/storage/demo.csv"
        },
      series: [{
        name: "Frequency",
        tooltip: { valueSuffix: "Hz" },
        type: 'spline',
        // yAxis: 1
        // },{
        // name: "Rain rate",
        // data: ,
        // tooltip: { valueSuffix: " mm" },
        // type: 'spline',
        // yAxis: 1
      }]
    });
  });
</script>

@stop