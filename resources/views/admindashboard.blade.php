<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h4 class="mb-0">Admin Dashboard</h4>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-light">Logout</button>
            </form>
        </div>

        <div class="card-body">
            <h5>Total Users: <span class="badge bg-success">{{ $totalUsers }}</span></h5>

            <ul class="nav nav-pills my-4" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button" role="tab">User List</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending" type="button" role="tab">Waiting for Approval</button>
                </li>
            </ul>

            <div class="tab-content" id="dashboardTabContent">
                <!-- Approved Users Tab -->
                <div class="tab-pane fade show active" id="users" role="tabpanel">
                    @if($approvedUsers->isEmpty())
                        <div class="alert alert-secondary">No approved users.</div>
                    @else
                        <table class="table table-bordered" >
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th> <!-- Add this column header -->
    </tr>
  </thead>
  <tbody>
    @foreach($approvedUsers as $user)
    <tr>
      <td>{{ $user->id }}</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ $user->role }}</td>
      <td>
        <!-- 👇 Add your buttons here -->
        <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-info btn-sm">View</a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<!-- <div class="pagination">
     {{ $approvedUsers->links() }}
</div> -->

<div class="d-flex justify-content-center mt-3">
    {{ $approvedUsers->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>


                       
                    @endif
                </div>

                <!-- Pending Users Tab -->
                <div class="tab-pane fade" id="pending" role="tabpanel">
                    <form method="GET" class="row g-2 mb-3" action="{{ route('admin.dashboard') }}">
    <div class="col-md-3">
        <input type="text" name="name" value="{{ request('name') }}" class="form-control" placeholder="Search by name">
    </div>
    <div class="col-md-3">
        <input type="text" name="email" value="{{ request('email') }}" class="form-control" placeholder="Search by email">
    </div>
    <div class="col-md-3">
        <select name="role" class="form-select">
            <option value="">Filter by role</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            <!-- Add more roles if needed -->
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

                    @if($pendingUsers->isEmpty())
                        <div class="alert alert-secondary">No users awaiting approval.</div>
                    @else
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingUsers as $index => $user)
                                    <tr>
                                        <td>{{ $pendingUsers->firstItem() + $index }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
        <form method="POST" action="{{ route('admin.approve', $user->id) }}">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">Approve</button>
        </form>

        <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-info btn-sm">View</a>

        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>

        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                      <div class="d-flex justify-content-center mt-3">
    {{ $approvedUsers->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>


                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
</body>
</html>
