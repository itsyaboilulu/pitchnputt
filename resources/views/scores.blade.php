@extends('layouts/app')
@section('content')
<link href="resources/css/scores.min.css" rel="stylesheet" />
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
                <label class="mr-2">Corse:<i class="ml-2"  id="new-Corse-notice" style="display: none">Can be changed in Settings later</i><select name="corse" id="new-Corse" class="mb-2 custom-select custom-select-sm form-control form-control-sm"> </label>
                    @foreach ($corses as $corse)
                        <option value="{{ $corse->name }}">{{ $corse->name }}</option>
                    @endforeach
                    <option value="new">New Corse</option>
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
                <input type="submit" class="btn btn-primary score-btn" value="save"/>
            </form>
        </div>
    </div>
</div>

<h1 class="mt-4">Edit Old</h1>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        Total Scores
    </div>
    <div class="card-body">

    </div>
</div>

<script>
    document.getElementById('new-Corse').addEventListener('change',function(){
        document.getElementById('new-Corse-notice').style.display = ( (this.value == 'new') ? '' : 'none' );
    });

</script>

@endsection
