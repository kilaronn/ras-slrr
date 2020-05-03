@extends('welcome')
@section('content')

<div class="container h-100">

  <ul class="nav">
    <li class="nav-item">
      <a href="{{route('home')}}" class="nav-link">HOME</a>
    </li>
    <li class="nav-item">
      <a href="{{route('import')}}" class="nav-link active">IMPORT</a>
    </li>
  </ul>


 
  <div class="row">
    <div class="col-6 offset-3">

      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Import data from file</h4>
          {!! Form::open(['url' => '/import','files'=>'true']) !!}
            {!! Form::file('csv_file', ['class' => 'form-control p-1 mt-5 mb-3']) !!}
            <div class="form-inline mb-5">
              <label class="sr-only" for="parameter">Parameter</label>
              {!! Form::select('parameter',[
                              "sl"=>"Sound Level",
                              "rr"=>"Rain Rate"
                            ], null, ['class' => 'custom-select form-control mr-sm-2']) !!}
              <label class="sr-only" for="station">Station</label>
              {!! Form::select('station',[
                              "up"=>"UP",
                              "at"=>"Ateneo"
                            ], null,['class' => 'custom-select form-control mr-sm-2']) !!}
              {!! Form::submit('Upload File', ['class' => 'btn btn-primary ml-sm-5']) !!}
            </div>
          {!! Form::close() !!}
        </div>
      </div>
      @isset($metaData)
        <div class="alert alert-success" role="alert">
          <strong>Success!</strong> 
          {{ $metaData[0] }} <em>({{ $metaData[1] }} bytes)</em> for {{ $metaData[2] }} from Station {{ $metaData[3] }}
        </div>
      @endisset
    </div>
  </div>
</div>

@stop