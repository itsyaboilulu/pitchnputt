<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Pitch & Putt</title>
        <link href="resources/css/bootstrap.min.css" rel="stylesheet" />
        <link href="resources/css/app.min.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <button class="btn btn-link " id="sidebarToggle" href="#" style="margin-left: 10px"><i class="fas fa-bars"></i></button>
            <a class="navbar-brand" href="/">Pitch'n'Putt Tracker</a>

            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ $group->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @foreach ($groups as $gs)
                            @if ($gs->id != $group->id)
                                <a class="dropdown-item" href="changegroup?id={{ $gs->id }}">{{ $gs->name }}</a>
                            @endif
                        @endforeach
                        <a class="dropdown-item" onclick="document.getElementById('logout-form').submit()" >Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Core</div>
                            <a class="nav-link" href="/">
                                <div class="sb-nav-link-icon"><i class="fas fa-award"></i></div>
                                Overview
                            </a>
                            @foreach ($weeks as $w)
                                <a class="nav-link" href="week?week={{ $w->weeknumber }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                                    Week {{ $w->weeknumber }}
                                </a>
                            @endforeach
                            <div class="sb-sidenav-menu-heading">Players</div>
                            @foreach ($players as $p)
                                <a class="nav-link" href="player?player={{ $p->name }}">
                                    <div class="sb-nav-link-icon">
                                        @if ($p->admin)
                                            <i class="fas fa-user-cog"></i>
                                        @else
                                            <i class="fas fa-user"></i>
                                        @endif
                                    </div>
                                    {{ $p->name }}
                                </a>
                            @endforeach
                            @if ($isAdmin)
                                <div class="sb-sidenav-menu-heading">Admin</div>
                                <a class="nav-link" href="scores">
                                    <div class="sb-nav-link-icon"><i class="fas fa-plus-square"></i></div>
                                    Add Scores
                                </a>
                                <!--<a class="nav-link" href="/golf"> <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>Settings</a> -->
                            @endif
                        </div>

                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        {{ Auth::user()->name }}
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">

                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="resources/js/bootstrap.js"></script>
    </body>
</html>
