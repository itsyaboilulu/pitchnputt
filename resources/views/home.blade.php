@extends('layouts/app')
@section('content')
<h1 class="mt-4">Overview</h1>
<div class="card-body">
    <div class="podium">
        @php
            //reorder to postion center outwrds
            $i=1;$ret=array();
            foreach($position as $name=>$pos) {
                $ret = ($i % 2 == 0) ? array($name=>[$pos,$i]) + $ret : $ret + array($name=>[$pos,$i]);
                $i++;
            }
        @endphp
        @foreach ($ret as $name=>$pos)
        <div class="podium-item podium-item-{{ $pos[1] }}">
            <strong>{{ $name }}</strong><br>{{ $pos[0] }}
        </div>
        @endforeach
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        <a class="active-view overflow-label" onclick="changeOverview(0)" id='total-scores-label'>Total Scores</a> | <a onclick="changeOverview(1)" class="overflow-label" id='avg-scores-label'>Average Scores</a>
    </div>
    <div class="card-body">
        <div class="table-responsive" id='total-scores-body'>
            <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th></th>
                        <th onclick="changeView(0)">1</th>
                        <th onclick="changeView(1)">2</th>
                        <th onclick="changeView(2)">3</th>
                        <th onclick="changeView(3)">4</th>
                        <th onclick="changeView(4)">5</th>
                        <th onclick="changeView(5)">6</th>
                        <th onclick="changeView(6)">7</th>
                        <th onclick="changeView(7)">8</th>
                        <th onclick="changeView(8)">9</th>
                        <th onclick="changeView(9)">10</th>
                        <th onclick="changeView(10)">11</th>
                        <th onclick="changeView(11)">12</th>
                        <th onclick="changeView()">T</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scores as $name=>$score)
                        <tr>
                            <td>{{ $name }}</td>
                            @php
                                $total_ret = [];
                                foreach ($score as $wscore) {
                                    for ($i=0; $i < 12; $i++) {
                                        $total_ret[$i] = ( isset($total_ret[$i]) )? $total_ret[$i] + $wscore[$i] : $wscore[$i];
                                    }
                                }
                            @endphp
                            @foreach ($total_ret as $t)
                                <td>{{ $t  }}</td>
                            @endforeach
                            <td>{{ array_sum($total_ret)  }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="table-responsive" id='avg-scores-body' style="display: none">
            <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0" >
                <thead>
                    <tr>
                        <th></th>
                        @for ($i = 0; $i < $settings['hole_number']; $i++)
                            <th onclick="changeView({{ $i }})">{{ ($i + 1) }}</th>
                        @endfor
                        <th onclick="changeView()">T</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scores as $name=>$score)
                        <tr>
                            <td>{{ $name }}</td>
                            @php
                                $total_ret = [];
                                foreach ($score as $wscore) {
                                    for ($i = 0; $i < $settings['hole_number']; $i++) {
                                        $total_ret[$i] = ( isset($total_ret[$i]) )? $total_ret[$i] + $wscore[$i] : $wscore[$i];
                                    }
                                }
                            @endphp
                            @foreach ($total_ret as $t)
                                <td>{{ round($t / count($score),1) }}</td>
                            @endforeach
                            <td>{{ round(array_sum($total_ret) / count($score),1)  }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-line mr-1"></i>
                Score
            </div>
            <div class="card-body">
                <canvas id="scoreChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar mr-1"></i>
                Par %
            </div>
            <div class="card-body" style="height: 100%"><canvas id="parPercentChart" width="100%" height="40" ></canvas></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar mr-1"></i>
                Consistsancy
            </div>
            <div class="card-body">
                <canvas id="consistancyGraph" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-line mr-1"></i>
                Range (score/yards)
            </div>
            <div class="card-body" style="height: 100%"><canvas id="rangeGraph" width="100%" height="40" ></canvas></div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
    var scores              = {!! json_encode($scores) !!};
    var par_accuracy        = {!! json_encode($parAccuracy) !!};
    var consistancy_data    = {!! json_encode($consistant) !!};
    var range_data          = {!! json_encode($range) !!};
</script>
<script src="resources/js/home.min.js"></script>

@endsection
