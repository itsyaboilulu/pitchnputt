<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>pitchNputt - Groups</title>
        <link href="resources/css/bootstrap.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <style>
        .join-input{
            text-align: center;
            padding:0.375rem 0.75rem;
            width:100%;
            border-radius: inherit 0px 0px inherit;
        }
    </style>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Waiting for admin to add first scores</h3></div>
                                    <div class="card-body">
                                        @foreach ($members as $member)
                                            @if ($member->admin)
                                                Admin: {{ $member->name }}
                                            @endif
                                        @endforeach
                                        <hr>
                                        Players
                                        <ul>
                                            @foreach ($members as $member)
                                                <li>{{ $member->name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="card-footer text-center">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="resources/js/bootstrap.min.js"></script>
    </body>
</html>
