@extends('manager_layout')
@section('content')
  <div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">게시물 현황 차트</h1>
    <p class="mb-4">SUDA 앱의 게시물 현황을 나타내는 페이지</p>

    <!-- Content Row -->
    <div class="row">

      <div class="col-xl-8 col-lg-7">

        <!-- Area Chart -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">전체 게시물 증가 수</h6>
          </div>
          <div class="card-body">
            <div class="chart-area">
              <canvas id="myAreaChart"></canvas>
            </div>
            <hr>
            전체 게시물의 월별 증가 현황을 보여주는 차트표 <code>/js/demo/chart-area-demo.js</code> file.
          </div>
        </div>

        <!-- Bar Chart -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
          </div>
          <div class="card-body">
            <div class="chart-bar">
              <canvas id="myBarChart"></canvas>
            </div>
            <hr>
            Styling for the bar chart can be found in the <code>/js/demo/chart-bar-demo.js</code> file.
          </div>
        </div>

      </div>

      <!-- Donut Chart -->
      <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
          <!-- Card Header - Dropdown -->
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">게시판별 게시물 현황</h6>
          </div>
          <!-- Card Body -->
          <div class="card-body">
            <div class="chart-pie pt-4">
              <canvas id="myPieChart"></canvas>
            </div>
            <hr>
            게시판별 게시물 현황을 파이형식 차트로 나타낸 부분 <code>/js/demo/chart-pie-demo.js</code> file.
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
