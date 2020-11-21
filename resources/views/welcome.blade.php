<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <link rel="stylesheet" href="/css/app.css">

        <!-- Styles -->
        <style>
          html, body {
              min-height: 100vh;
              margin: 10px 0 0 0;
              width:100%;
          }
          #graph,#rain-acc{
            height: 680px;
          }
        </style>
        
        <script src="{{asset('/js/jquery-3.4.1.slim.min.js')}}"></script>
        <script src="{{asset('/js/app.js')}}"></script>

    </head>
    <body>

    @yield('content')
        
    <footer class="fixed-bottom bg-primary">
      <div class="content">
        <div class="text-center">
        <small>Copyright © ADMU Monitoring {{ date("Y") }}</small>
        </div>
      </div>
    </footer>



    
{{-- <script src="{{asset('/js/highcharts.js')}}"></script> --}}
<script src="{{asset('/js/highstock.js')}}"></script>
<script src="{{asset('/js/data.js')}}"></script>
<script src="{{asset('/js/boost.js')}}"></script>
<script src="{{asset('/js/exporting.js')}}"></script>
<script src="{{asset('/js/export-data.js')}}"></script>
<script src="{{asset('/js/accessibility.js')}}"></script>
  </body>
</html>
