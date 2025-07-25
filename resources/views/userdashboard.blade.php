<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">User Dashboard</a>
            <form action="{{ route('logout') }}" method="POST" class="d-flex ms-auto">
                @csrf
                <button class="btn btn-outline-light" type="submit">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow p-4">
            <h3 class="text-success">Welcome, {{ Auth::user()->name }}!</h3>
            <p>You are logged in as <strong>{{ Auth::user()->role }}</strong>.</p>

            @if (session('message'))
                <div class="alert alert-success mt-3">
                    {{ session('message') }}
                </div>
            @endif

            <hr>

            <p>This is your personal dashboard. You can customize this section with your own features.</p>
        </div>
    </div>

</body>
</html>
