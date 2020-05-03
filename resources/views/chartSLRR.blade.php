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
  $(function(){
    $( ".datepicker" ).datepicker({
      todayHighlight: true,
      autoclose: true,
    });

    var rpi_info = <?php echo json_encode($rpi_info,JSON_NUMERIC_CHECK); ?>,
        sound_data = [],
        rain_data = [],
        i = 0;

    for (i; i<rpi_info.length;i+=1){
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
      xAxis: {
        type: 'datetime',
        labels: { format: '{value:%b %d, %Y}' }
      },
      yAxis: [{
        gridLineWidth: 0,
        plotBands: [
          { label: {  text: 'Light',      style: {color:'#555'} },
            from: 31.3, to: 60.3,         color: 'rgba(0, 0, 0, 0.1)' },
          { label: {  text: 'Moderate',   style: {color:'#555'} },
            from: 60.4, to: 72.2,         color: 'rgba(0, 0, 0, 0.1)' },
          { label: {  text: 'Intense',    style: {color:'#555'} },
            from: 72.3, to: 84.5,         color: 'rgba(245, 154, 24, 0.1)' },
          { label: {  text: 'Torrential', style: {color:'#555'} },
            from: 84.6, to: 10000,        color: 'rgba(228, 27, 33, 0.1)' }
        ]},{
          title: { text: 'Sound level',          style: {color: Highcharts.getOptions().colors[0]} },
          labels: { format: '{value} dB',        style: {color: Highcharts.getOptions().colors[0]} }
        },
        {
          min: 0,
          opposite: false,
          title: { text: 'Rain rate',            style: {color: Highcharts.getOptions().colors[1]} },
          labels: { format: '{value} mm/hr',     style: {color: Highcharts.getOptions().colors[1]} }
        }],
      tooltip: {
        pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y}</b>{point.tooltip.valueSuffix}<br/>',
        xDateFormat: '%a, %b %d, %Y %H:%M',
        valueDecimals: 2,
        shared: true,
        split: false
      },
      series: [{
        name: "Sound level",  data: sound_data, tooltip: { valueSuffix: " dB" },
        type: 'spline',
        yAxis: 1
      },{
        name: "Rain rate",    data: rain_data,  tooltip: { valueSuffix: " mm" },
        type: 'spline',
        // yAxis: 1
      }]
    });
  });
</script>

<script src="{{asset('/js/highcharts/highstock.js')}}"></script>
<script src="{{asset('/js/highcharts/data.js')}}"></script>
<script src="{{asset('/js/highcharts/boost.js')}}"></script>
<script src="{{asset('/js/highcharts/exporting.js')}}"></script>
<script src="{{asset('/js/highcharts/export-data.js')}}"></script>
<script src="{{asset('/js/highcharts/accessibility.js')}}"></script>
@stop