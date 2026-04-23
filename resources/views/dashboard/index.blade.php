@extends('layouts.stisla')

@section('title', 'Dashboard')

@section('content')
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>

  <div class="section-body">
    <p class="section-title">Selamat datang, {{ Auth::user()->name }}</p>

    <div class="row">
    {{-- Card untuk Jumlah Alternatif --}}
    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
      <div class="card-icon bg-primary">
        <i class="far fa-user"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
        <h4>Total Alternatif</h4>
        </div>
        <div class="card-body">
        {{ $data['alternatifCount'] }}
        </div>
      </div>
      </div>
    </div>

    {{-- Card untuk Jumlah Kriteria --}}
    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
      <div class="card-icon bg-danger">
        <i class="far fa-clipboard"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
        <h4>Total Kriteria</h4>
        </div>
        <div class="card-body">
        {{ $data['kriteriaCount'] }}
        </div>
      </div>
      </div>
    </div>
    </div>

    <div class="row">
    <div class="col-12">
      <div class="card">
      <div class="card-header">
        <h4>Hasil Perhitungan Metode MOORA</h4>
      </div>
      <div class="card-body">
        <canvas id="myChart"></canvas>
      </div>
      </div>
    </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('assets/js/chart.min.js') }}"></script>

  <script>
    "use strict";

    var labels = {!! json_encode($data['alternatifNames']) !!};
    var dataValues = {!! json_encode($data['hasilValues']) !!};

    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
      label: 'Nilai Yi (Moora)',
      data: dataValues,
      backgroundColor: [
        'rgba(63, 81, 181, .8)',
        'rgba(255, 99, 132, .8)',
        'rgba(255, 159, 64, .8)',
        'rgba(75, 192, 192, .8)',
        'rgba(54, 162, 235, .8)',
        'rgba(153, 102, 255, .8)'
      ],
      borderColor: [
        '#3f51b5',
        '#ff6384',
        '#ff9f40',
        '#4bc0c0',
        '#36a2eb',
        '#9966ff'
      ],
      borderWidth: 1
      }]
    },
    options: {
      scales: {
      yAxes: [{
        ticks: {
        beginAtZero: true
        }
      }]
      }
    }
    });
  </script>
@endpush