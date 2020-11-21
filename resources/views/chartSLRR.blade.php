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
    
    <div id="rain-acc" class="bg-light p-5 shadow mb-5"></div>
    {{-- 
    <div id="rainrate" class="bg-light p-5 shadow mb-5"></div>
    
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
    </table> --}}

  </div>











{{-- Begin Script --}}
<script type="text/javascript">

  var url_string = window.location.href;
  var url = new URL(url_string);
  var c = url.searchParams.get("data");

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
      legend: { enabled: false, align: 'center', layout: 'horizontal', verticalAlign: 'bottom' },
      scrollbar: { liveRedraw: false },
      chart: { backgroundColor: null },
      colors: ['#ff0000','#9a71ca'],
      title: { text:"Sound Level Data" },
      xAxis: [{
        type: 'datetime',
        labels: { format: '{value:%b %d, %Y %H:%M}' },
      }],
      yAxis: [{
        minRange:22000,
        min:0,
        gridLineWidth: 0,
        plotBands: [
          { label: {  text: 'Light',      style: {color:'#555'} },
            from: 0, to: 12000,           color: 'rgba(189, 215, 238, 0.3)' },
          { label: {  text: 'Moderate',   style: {color:'#555'} },
            from: 12000, to: 14000,       color: 'rgba(148, 207, 80, 0.3)' },
          { label: {  text: 'Heavy',      style: {color:'#555'} },
            from: 14000, to: 16000,       color: 'rgba(255, 255, 0, 0.3)' },
          { label: {  text: 'Intense',    style: {color:'#555'} },
            from: 16000, to: 18000,       color: 'rgba(255, 190, 0, 0.3)' },
          { label: {  text: 'Torrential', style: {color:'#555'} },
            from: 18000, to: 1000000000,  color: 'rgba(255, 69, 65, 0.3)' }
        ]},{
          title: { text: 'Frequency',          style: {color: Highcharts.getOptions().colors[0]} },
          labels: { format: '{value} Hz',        style: {color: Highcharts.getOptions().colors[0]} }
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
        tooltip: { valueSuffix: " Hz" },
        type: 'spline'
      }]
    });
    /*
    var options = {
      boost: { enabled:true, useGPUTranslations:true, usePreallocated:true, allowForce:true },
      plotOptions: { series: { showInNavigator: true, connectNulls:false, marker:{ enabled:true } } },
      legend: { enabled: false, align: 'center', layout: 'horizontal', verticalAlign: 'bottom' },
      scrollbar: { liveRedraw: false },
      
      tooltip: {
        pointFormat: '<span style="color:{point.color}">\u25CF</span> <b>{point.y}</b>{point.tooltip.valueSuffix}<br/>',
        xDateFormat: '%a, %b %d, %Y %H:%M',
        valueDecimals: 2,
        shared: false,
        split: false
      },
      chart: {
          defaultSeriesType: 'column',
          backgroundColor: null
      },
      title: {
          text: 'Rain Accumulation'
      },
      xAxis: {
        categories:[],
        type: 'datetime',
        labels: { format: '{value:%b %d, %Y %H:%M}' },
      },
      yAxis: {
          title: {
              text: 'Millimeter'
          }
      },
      series: [{
        data:[]
      }]
    };
      Highcharts.ajax({
        url: 'http://128.199.161.14/storage/demo.csv',
        dataType: 'text',
        success: function(data) {

          var lines = data.split('\n');
          var series = {data:[]};  

          var DT = new Date(), aRR = 0, a = 0.00000000003, b = 0.0000007, c = 0.005, d = 8.65, t = 60;

          lines.forEach(function(line, lineNo) {

            var items = line.split(',');

            if (isNaN(parseFloat(items[0]))) {
              console.log("a NaN has been removed.");
            } else {
              DT = Date.parse(items[0]) + 28800000;
              x = items[1];
              aRR = parseFloat(aRR + (((a*Math.pow(x,3)) - (b*Math.pow(x,2)) + (c*x) - d) / t));
              
              series.data.push([DT,aRR]);
            }
          });

          options.series.splice(0, options.series.length);
          options.series.push(series);

          Highcharts.stockChart('rainrate', options);
        },
        error: function (e, t) {
          console.error(e, t);
        }
      });*/






    let go;
    async function requestData() {
      const result = await fetch('http://128.199.161.14/storage/demo.csv');
      if (result.ok) {
        const data = await result.text();
        var point = [];
        var series = [];
        var DT = new Date(), aRR = 0, a = 0.00000000003, b = 0.0000007, c = 0.005, d = 8.65, t = 60, x = 0;

        var lines = data.split('\n');
        
        var dataPoints = new Array;

        lines.forEach(function(line, lineNo) {

          var items = line.split(',');

          if (isNaN(parseFloat(items[0]))) {
            console.log("a NaN has been removed.");
          } else {
            DT = Date.parse(items[0]) + 28800000;
            x = parseFloat(items[1]);
            aRR = aRR + (((a*Math.pow(x,3)) - (b*Math.pow(x,2)) + (c*x) - d) / t);
            
            point = [DT,aRR];
            series = go.series[0],
            shift = 0 ; // shift if the series is longer than 20

            // add the point
            // go.series[0].addPoint(point, true, shift);
            dataPoints.push(point);
            console.log("dataPoints " + dataPoints);
          }
          go.series[0].setData([]);
          go.series[0].setData(dataPoints,false);
          go.redraw();
    
        });
        setTimeout(requestData, 60000);
      }
    }

    go = new Highcharts.stockChart({
      boost: { enabled:true, useGPUTranslations:true, usePreallocated:true, allowForce:true },
      plotOptions: { series: { showInNavigator: true, connectNulls:false, marker:{ enabled:true }, animation:{duration:500} } },
      legend: { enabled: false, align: 'center', layout: 'horizontal', verticalAlign: 'bottom' },
      scrollbar: { liveRedraw: false },
      
      tooltip: {
        pointFormat: '<span style="color:{point.color}">\u25CF</span> <b>{point.y} mm</b>{point.tooltip.valueSuffix}<br/>',
        xDateFormat: '%a, %b %d, %Y %H:%M',
        valueDecimals: 2,
        shared: false,
        split: false
      },
      title: {
          text: 'Rain Accumulation'
      },
      chart: {
        renderTo: 'rain-acc',
        defaultSeriesType: 'column',
        backgroundColor: null,
        events: {
          load: requestData
        }
      },
      xAxis: {
          type: 'datetime',
          labels: { format: '{value:%b %d, %Y %H:%M}' },
        // tickPixelInterval: 150,
        // maxZoom: 20 * 1000
      },
      yAxis: {
        min:0,
        // minPadding: 0.2,
        // maxPadding: 0.2,
        title: {
          text: 'Millimeter',
          // margin: 80
        },
        plotBands: [
          { label: {  text: 'Light',      style: {color:'#555'} },
            from: 0, to: 2.5,             color: 'rgba(189, 215, 238, 0.3)' },
          { label: {  text: 'Moderate',   style: {color:'#555'} },
            from: 2.5, to: 7.5,           color: 'rgba(148, 207, 80, 0.3)' },
          { label: {  text: 'Heavy',      style: {color:'#555'} },
            from: 7.5, to: 15,            color: 'rgba(255, 255, 0, 0.3)' },
          { label: {  text: 'Intense',    style: {color:'#555'} },
            from: 15, to: 30,             color: 'rgba(255, 190, 0, 0.3)' },
          { label: {  text: 'Torrential', style: {color:'#555'} },
            from: 30, to: 1000000000,     color: 'rgba(255, 69, 65, 0.3)' }
        ]
      },
      series: [{
        // name: 'Random data',
        data: []
      }]
    });



  });
</script>

@stop