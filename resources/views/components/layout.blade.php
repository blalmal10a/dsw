<!DOCTYPE html>
<html lang="en">

<head>
    <title>MZU Hostels</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.css">
    <script src="https://cdn.ckeditor.com/4.24.0-lts/standard/ckeditor.js"></script>
</head>


<body>

<br>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Dean Students' Welfare</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="/hostel">Hostels</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admissioncheck">Check admission status</a>
                    </li>
                    @auth
                        @if(auth()->user()->isWarden())
                            @foreach(auth()->user()->user_roles() as $role_user)
                                @if($role_user->type == 'hostel')
                                    <li class="nav-item">
                                        <a class="nav-link" href="/hostel/{{ $role_user->foreign_id }}/">{{ $role_user->hostel()->name }}</a>
                                    </li>
                                @endif
                            @endforeach    
                        @endif
                        @if(auth()->user()->isAdmin() || auth()->user()->isDsw())
                            <li class="nav-item">
                                <a class="nav-link" href="/notification/">Notifications</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/user">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/consolidate">Consolidate</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="/search">Search</a>
                        </li>
                    @endauth
                </ul>
                <!--      <ul class="navbar-nav me-auto"> -->
                @auth
                    <div class="dropdown">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            {{ auth()->user()->username }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/user/changePassword">Change password</a></li>
                            <li><button class="dropdown-item" form="logout-form">Logout</button></li>
                            <form method="post" id="logout-form" action="/logout" type="hidden">
                                @csrf
                            </form>
                        </ul>
                    </div>
                @else
                    <a class="btn btn-outline-secondary" type="button" href="/login">Login</a>
                @endif
                </div>
            </div>
        </nav>

        <div class="mt-5 container-fluid">
            @if (Session::has('message'))
                <x-alert type="{{ session('message')['type'] }}">{{ session('message')['text'] }}</x-alert>
            @endif
            {{ $slot }}
        </div>

    </body>

</html>
