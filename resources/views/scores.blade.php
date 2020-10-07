@extends('layouts/app')
@section('content')
<link href="resources/css/settings.min.css" type="text/css" rel="stylesheet" />
<h1 class="mt-4">Add Score</h1>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Add Scores
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <form method="POST" action="setscores" autocomplete="off" >
                @csrf
                <label class="mr-2">course:<i class="ml-2"  id="new-course-notice"> Register a new corse in settings </i><select name="corse" id="new-course" class="mb-2 custom-select custom-select-sm form-control form-control-sm"> </label>
                    @foreach ($corses as $corse)
                        <option value="{{ $corse->name }}">{{ $corse->name }}</option>
                    @endforeach
                </select>
                <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                        <th></th>
                        @for ($i = 0; $i < $settings['hole_number']; $i++)
                            <td>{{ $i + 1 }}</td>
                        @endfor
                    </tr>
                    @foreach ($players as $player)
                        <tr>
                            <td>{{ $player->name }}</td>
                            @for ($i = 0; $i < $settings['hole_number']; $i++)
                                <td><input type="number" min="1" max="12" name="{{ $player->name }}-{{ $i + 1 }}" required></td>
                            @endfor
                        </tr>
                    @endforeach
                </table>
                <input type="submit" class="btn btn-primary score-btn" value="Save"/>
            </form>
        </div>
    </div>
</div>

<h1 class="mt-4">Edit Old</h1>
@foreach ($weeks as $week)
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            Week {{ $week->weeknumber }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form method="POST" action="updatescores" autocomplete="off" >
                    @csrf
                    <input type="hidden" name="week" value="{{ $week->weeknumber }}" />
                    <label class="mr-2">course:<i class="ml-2"  id="new-course-notice"> Register a new corse in settings </i><select name="corse" id="new-course" class="mb-2 custom-select custom-select-sm form-control form-control-sm"> </label>
                        @foreach ($corses as $corse)
                            <option value="{{ $corse->name }}" @if($corse->id == $week->scorseid) selected="selected" @endif>{{ $corse->name }}</option>
                        @endforeach
                    </select>
                    <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <th></th>
                            @for ($i = 0; $i < $settings['hole_number']; $i++)
                                <td>{{ $i + 1 }}</td>
                            @endfor
                        </tr>
                        @foreach ($oldScores as $name=>$score)
                            <tr>
                                <td>{{ $name }}</td>
                                @for ($i = 0; $i < $settings['hole_number']; $i++)
                                    <td><input type="number" min="1" max="12" name="{{ $name }}-{{ $week->weeknumber }}-{{ $i + 1 }}" value="{{ $score[$week->weeknumber][$i] }}" required></td>
                                @endfor
                        </tr>
                        @endforeach
                    </table>
                    <input type="submit" class="btn btn-primary score-btn" value="Update"/>
                </form>
            </div>
        </div>
    </div>
@endforeach



@endsection
