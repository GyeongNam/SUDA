
@extends('manager_layout')

@section('content')
  <div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">SUDA 통계</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">어제 방문자 수</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$yesterday_visitors_count}}명</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">어제 발생한 오류</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$yesterday_error_count}}개</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">월 방문자 수</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$month_visitors_count}}개</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">월 발생한 오류</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$month_error_count}}개</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Bar Chart -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">게시판별 방문자 수(어제 기준)</h6>
  </div>
  <div class="card-body">
    <div class="chart-bar">
      <canvas id="myBarChart"></canvas>
    </div>
    <hr>
    Styling for the bar chart can be found in the <code>/js/demo/chart-bar-demo.js</code> file.
  </div>
</div>
<!-- Bar Chart -->
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">게시판별 방문자 수(월별 기준)</h6>
  </div>
  <div class="card-body">
    <div class="chart-bar">
      <canvas id="myBarChartM"></canvas>
    </div>
    <hr>
    Styling for the bar chart can be found in the <code>/js/demo/chart-bar-demo.js</code> file.
  </div>
</div>

</div>
@foreach ($yesterday_json_board as $key => $value)
  <input type="hidden" name="board" value="{{$key}}">
  <input type="hidden" name="visit" value="{{$value}}">

@endforeach

@foreach ($month_json_board as $key => $value)
  <input type="hidden" name="Mboard" value="{{$key}}">
  <input type="hidden" name="Mvisit" value="{{$value}}">
@endforeach

<script>
var x = $('input[name=board]').length
var array =  new Array();
var array1 = new Array();
for(i=0;i<x;i++){
  array.push($('input[name=board]').eq(i).val());
  array1.push($('input[name=visit]').eq(i).val());
}
console.log(array);
console.log(array1);
</script>

<script>
var y = $('input[name=Mboard]').length
var arrayM =  new Array();
var arrayM1 = new Array();
for(i=0;i<y;i++){
  arrayM.push($('input[name=Mboard]').eq(i).val());
  arrayM1.push($('input[name=Mvisit]').eq(i).val());
}
console.log(arrayM);
console.log(arrayM1);
</script>
@endsection
