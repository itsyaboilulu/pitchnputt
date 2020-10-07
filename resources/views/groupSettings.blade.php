@extends('layouts/app')
@section('content')
<link href="resources/css/settings.min.css" type="text/css" rel="stylesheet" />
<h1>Settings</h1>
<!--
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        General Settings
    </div>
    <div class="card-body">
        <div class="table-responsive">

        </div>
    </div>
</div>
-->
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table mr-1"></i>
        courses
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <ul class="corse-list">
                @foreach ($corses as $corse)
                    <li>
                        <div class="corse-list-header" onclick="corseDropDown('{{ $corse->name }}',this)"><i class="fas fa-chevron-down"></i>{{ $corse->name }}</div>
                        <div class="corse-list-body" style="display: none" id='corse-list-body-{{ $corse->name }}'>
                            <form action="updatecourse" method="POST">
                                @csrf
                                <input type="hidden" name="id" value='{{ $corse->id }}' />
                                <input type="text" class="mb-2 mt-1" name='name' value='{{ $corse->name }}' placeholder="Course Name" />
                                <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0">
                                    <tr>
                                        <th></th>
                                        @for($i=1;$i!=($settings['hole_number']+1);$i++)
                                            <th>{{ $i }}</th>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td>Yards</td>
                                        @foreach( $holes[ $corse->name ] as $h)
                                            <td> <input type="number" min="0" name="yards-{{ $h->hole }}" value="{{ $h->range }}" /> </td>
                                        @endforeach
                                    </tr>
                                </table>
                                <input type="submit" class="btn btn-primary score-btn" value="Update" />
                            </form>
                        </div>
                    </li>
                @endforeach
                <li>
                    <div class="corse-list-header" onclick="corseDropDown('new',this)" ><i class="fas fa-plus"></i>Add New course</div>
                    <div class="corse-list-body" style="display: none"  id='corse-list-body-new'>
                        <form action="setcourse" method="POST">
                            @csrf
                            <input type="text" name='name' value='' class="mb-2 mt-1" placeholder="course Name" required/>
                            <table class="table table-bordered score-table" id="dataTable" width="100%" cellspacing="0">
                                <tr>
                                    <th></th>
                                    @for($i=1;$i!=($settings['hole_number']+1);$i++)
                                        <th>{{ $i }}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    <td>Yards</td>
                                    @for($i=1;$i!=($settings['hole_number']+1);$i++)
                                        <td> <input type="number" min="0" name="yards-{{ $i }}" /> </td>
                                    @endfor
                                </tr>
                            </table>
                            <input type="submit" class="btn btn-primary score-btn" value="Add" />
                            </form>
                        </div>
                    </li>
            </ul>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area mr-1"></i>
                Remove Players
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="del-table">
                        @foreach ($players as $player)
                            @if ( $player->name !=  Auth::user()->name)

                                    <tr>
                                        <td>{{ $player->name }}</td>
                                        <td>
                                            <input type="checkbox" id='{{ $player->name }}' name='{{ $player->name }}' class="check-del" onclick="unblock('{{ $player->name }}',this);">
                                            <label for="{{ $player->name }}">Remove Player</label>
                                        </td>
                                        <td>
                                            <form id='del-form-{{ $player->name }}' method="POST" action="removeplayer" >
                                                @csrf
                                                <input type="hidden" name="name" value="{{ $player->name }}">
                                                <input type="submit" id='del-btn-{{ $player->name }}' class="btn btn-primary remove-btn" value="Remove" disabled/>
                                            </form>
                                        </td>
                                    </tr>

                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar mr-1"></i>
                Invite Players
            </div>
            <div class="card-body" style="height: 100%">
                invite code @todo
            </div>
        </div>
    </div>
</div>


<script>
    function unblock($name,$this){
        btn = document.getElementById('del-btn-'+$name);
        if ($this.checked){
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }
    function corseDropDown($name,$this){
        var body = document.getElementById('corse-list-body-'+$name);
        if (body.style.display){
            body.style.display = '';
            if ($name != 'new'){
                $this.childNodes[0].classList.add('fa-chevron-up');
            }
        } else {
            body.style.display = 'none';
            if ($name != 'new'){
                $this.childNodes[0].classList.add('fa-chevron-down');
            }
        }
    }
</script>

@endsection
