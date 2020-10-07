@extends('layouts/app')
@section('content')
<h1 class="mt-4">{{ $player }}</h1>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Scores
    </div>
    <div class="card-body">
        <p>Select week or hole for better anaylsis</p>
        <div class="table-responsive">
            <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th onclick="changeData('t')">wk</th>
                        @for ($i = 1; $i < 13; $i++)
                            <th onclick="changeData(false,{{ $i-1 }})">
                                <strong>{{ $i }}</strong>
                            </th>
                        @endfor
                        <th onclick="changeData(false,'th')">T</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scores as $week=>$score)
                        <tr>
                            <td onclick="changeData({{ $week }})"><strong>{{ $week }}</strong></td>
                            @foreach ($score as $t)
                                <td>{{ $t  }}</td>
                            @endforeach
                            <td>{{ array_sum($score)  }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td onclick="changeData('t')"><strong>T</strong></td>
@php
    foreach ($scores as $week=>$score){ for ($i=0; $i < 12; $i++) { $ret[$i] = (isset($ret[$i])) ? $ret[$i]+$score[$i] : $score[$i]; } }
@endphp
                        @foreach ($ret as $r)
                            <td>{{ $r }}</td>
                        @endforeach
                        <td>{{ array_sum($ret) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-pie mr-1"></i>
                Hole Count
            </div>
            <div class="card-body" style="height: 100%"><canvas id="HoleCountPieChart" width="100%" height="88" ></canvas></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-pie mr-1"></i>
                Par %
            </div>
            <div class="card-body" style="height: 100%"><canvas id="parPercentPieChart" width="100%" height="88" ></canvas></div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area mr-1"></i>
                Score
            </div>
            <div class="card-body">
                <canvas id="myAreaChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area mr-1"></i>
                Range(Yards)
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <canvas id="rangeGraph" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar mr-1"></i>
                Half Comparison
            </div>
            <div class="card-body" style="height: 100%"><canvas id="hcBarChart" width="100%" height="88" ></canvas></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar mr-1"></i>
                Week Comparison
            </div>
            <div class="card-body" style="height: 100%"><canvas id="wcChart" width="100%" height="88" ></canvas></div>
        </div>
    </div>


</div>

    @php
        $t_week = array();$holes=array();
        foreach ($scores as $week=>$score){
            for ($i=0; $i < count($score); $i++) {
                $t_week[$i] = (isset($t_week[$i]))? $t_week[$i] + $score[$i] : $score[$i];
                $holes[$i][] = $score[$i];
            }
            $t_hole[] = array_sum($score);
        }
        foreach ($t_week as $key => $value) {
            $a_week[$key] = $value / count($scores);
        }
        $ret = array(
            "weeks"=> ( $scores + array('t'=>$t_week,'a'=>$a_week) ),
            "holes"=> ( $holes + array('th'=>$t_hole) ),
        );
        $ret = json_encode($ret);
    @endphp
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
        var cdata = {!! json_encode($scoreCount) !!};
        var pdata = {!! json_encode($parAccuracy) !!};
        var tdata = {!! $ret !!};
        var gdata = {!! $ret !!};
        var crdata = {!! json_encode($range) !!};
        var sgheader = {
            'week':[1,2,3,4,5,6,7,8,9,10,11,12],
            'hole':[ @foreach ($scores as $week=>$score)"week {{ $week }}", @endforeach ]
        }
        var crlabels = {!! json_encode(array_keys($range['total']))!!};
</script>
<script src="resources/js/bio.min.js"></script>
@endsection
