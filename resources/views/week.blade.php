@extends('layouts/app')
@section('content')
<h1 class="mt-4">Week {{ $week }}</h1>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Scores
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0">

                <tbody>
                    @foreach ($scores as $name=>$score)
                        <tr>
                            <td onclick="document.location.href = 'player?player={{ $name }}'">{{ $name }}</td>
                            @foreach ($score as $s)
                                <td>{{ $s }}</td>
                            @endforeach
                            <td>{{ array_sum($score)   }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <thead>
                    <tr>
                        <th onclick="updateData()"></th>
                        @for ($i = 0; $i < count($score); $i++)
                            <th onclick="updateData({{ $i }})">{{ $i +1 }}</th>
                        @endfor
                        <th onclick="updateData()">T</th>
                    </tr>
                </thead>
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
    var score_data = {!! json_encode($scores) !!},
    par_accuracy = {!! json_encode($parAccuracy) !!},
    consistancy_data = {!! json_encode($consistant) !!},
    range_data = {!! json_encode($range) !!};
</script>
<script src="resources/js/week.min.js"></script>

@endsection
