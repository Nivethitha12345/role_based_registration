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
                <div class="row text-center mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6>Total Users</h6>
            <h4><span class="badge bg-primary">{{ $totalUsers }}</span></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6>Approved Users</h6>
            <h4><span class="badge bg-success">{{ $approvedUserCount }}</span></h4>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h6>Pending Approval</h6>
            <h4><span class="badge bg-warning text-dark">{{ $pendingUserCount }}</span></h4>
        </div>
    </div>
</div>

                <ul class="nav nav-pills my-4" id="dashboardTabs" role="tablist">

                    <button class="nav-link active" id="users-tab" data-bs-toggle="pill" data-bs-target="#users"
                        type="button" role="tab" aria-controls="users" aria-selected="true">
                        User List
                    </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending"
                            type="button" role="tab" aria-controls="pending" aria-selected="false">
                            Waiting for Approval
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="upload-tab" data-bs-toggle="pill" data-bs-target="#upload"
                            type="button" role="tab" aria-controls="upload" aria-selected="false">
                            Upload
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="dashboardTabContent">
                    <!-- Approved Users Tab -->
                    <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                        @if($approvedUsers->isEmpty())
                            <div class="alert alert-secondary">No approved users.</div>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedUsers as  $index =>$user)
                                        <tr>
                                            <td>{{ $approvedUsers->firstItem() + $index }}</td> <!-- ✅ Serial number -->
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->role }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.view', $user->id) }}"
                                                    class="btn btn-info btn-sm">View</a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
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

                    <!-- Pending Users Tab -->
                    <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        <form method="GET" class="row g-2 mb-3" action="{{ route('admin.dashboard') }}">
                            <div class="col-md-3">
                                <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                    placeholder="Search by name">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="email" value="{{ request('email') }}" class="form-control"
                                    placeholder="Search by email">
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-select">
                                    <option value="">Filter by role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
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
                                        <th>S.No</th>
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
                                                    <a href="{{ route('admin.users.view', $user->id) }}"
                                                        class="btn btn-info btn-sm">View</a>
                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure?')">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <!-- Upload Tab -->
                    <!-- ✅ Add this button just above the heading -->
                    <div class="tab-pane fade" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                        <button id="toggleUploadForm" class="btn btn-success mb-3">Add New File</button>

                        <div id="uploadedFilesSection">
                            <h2>Uploaded Files</h2>
                            <table border="1" cellpadding="8">

                                <tr>
                                    <th>ID</th>
                                    <th>title</th>
                                    <th>Filename</th>
                                    <th>Action</th>
                                </tr>
                                @forelse ($files as $file)
                                    <tr>
                                        <td>{{ $file->id }}</td>
                                        <td>{{ $file->title }}</td>
                                        <td>{{ $file->filename }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                {{-- -view file --}}
                                                <a href="{{ asset('storage/' . $file->path) }}" target="_blank">
                                                    <button class="btn btn-sm btn-secondary">View</button></a>
                                                {{-- Delete File --}}
                                                <form action="{{ route('file.delete', $file->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure to delete this file?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="hidden" name="file" value="{{ $file }}">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No files uploaded yet.</td>
                                    </tr>
                                @endforelse
                            </table>

                        </div>
                        <div class="d-none" id="uploadfileform">
                            <button type="submit" class="btn btn-primary d-none" id="backlist">Back to List</button>
                            <!-- <div id="uploadFormSection" style="display: none;">
</div> -->
                            <!-- <form action="{{ route('admin.upload') }}" method="POST" enctype="multipart/form-data" -->
                                <!-- class="mt-3"> -->
                                <!-- @csrf -->

                                <!-- Title input -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">File Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <meta name="csrf-token" content="{{ csrf_token() }}">

                                <!-- File input -->
                                <!-- <div class="mb-3">
                                    <label for="uploadFile" class="form-label">Choose File</label>
                                    <input type="file" class="form-control" id="uploadFile" name="file" required>
                                </div> -->

                               <input type="file" id="fileInput" multiple class="form-control mb-2">
<div id="previewContainer" class="mb-3"></div>
                               <button type="button" id="uploadBtn" class="btn btn-primary">Upload Selected Files</button>
                            <!-- </form> -->
                        </div>

                    </div>


                    <div class="d-flex justify-content-center mt-3">
                        {{ $pendingUsers->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Optional: Activate tab from URL hash -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Update URL hash on tab click
            document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(button => {
                button.addEventListener('shown.bs.tab', function (e) {
                    const target = e.target.getAttribute('data-bs-target');
                    history.replaceState(null, null, target);
                });
            });
        });
    </script>


    <script>
        document.getElementById('toggleUploadForm').addEventListener('click', function () {
            const filesSection = document.getElementById('uploadedFilesSection');
            const uploadForm = document.getElementById('toggleUploadForm');
            const backlist = document.getElementById('backlist');
            const uploadfileform = document.getElementById('uploadfileform');



            const upload = document.getElementById('upload');
            upload.classList.remove('d-none');
            filesSection.classList.add('d-none');
            uploadForm.classList.add('d-none');
            backlist.classList.remove('d-none');
            uploadfileform.classList.remove('d-none');
        });

        document.getElementById('backlist').addEventListener('click', function () {
            const filesSection = document.getElementById('uploadedFilesSection');
            const uploadForm = document.getElementById('toggleUploadForm');
            const backlist = document.getElementById('backlist');
            const uploadfileform = document.getElementById('uploadfileform');

            // Don't hide the #upload tab — just toggle sections within it
            filesSection.classList.remove('d-none');
            uploadForm.classList.remove('d-none');
            backlist.classList.add('d-none');
            uploadfileform.classList.add('d-none');
        });

    </script>
<script>
const fileInput = document.getElementById('fileInput');
const previewContainer = document.getElementById('previewContainer');
const uploadBtn = document.getElementById('uploadBtn');

let fileList = []; // stores File objects

// Handle file selection
fileInput.addEventListener('change', () => {
    const newFiles = Array.from(fileInput.files);
    newFiles.forEach(file => {
        const fileId = `${file.name}-${Date.now()}`;

        fileList.push({ id: fileId, file: file, uploaded: false });

        const fileRow = document.createElement('div');
        fileRow.className = 'd-flex align-items-center mb-2';
        fileRow.id = fileId;

        fileRow.innerHTML = `
            <span class="me-3">${file.name}</span>
            <span class="status text-muted me-3">(Not uploaded)</span>
            <button type="button" class="btn btn-sm btn-danger delete-btn">Delete</button>
        `;

        fileRow.querySelector('.delete-btn').addEventListener('click', () => {
            fileRow.remove();
            fileList = fileList.filter(f => f.id !== fileId);
        });

        previewContainer.appendChild(fileRow);
    });

    fileInput.value = ''; // reset so same file can be chosen again
});

// Handle upload click
$(document).ready(function() {
uploadBtn.addEventListener('click', () => {
    if (fileList.length === 0) {
        alert("No files selected.");
        return;
    }

    const formData = new FormData();
    fileList.forEach(item => {
        formData.append('files[]', item.file);
    });
 var title = document.getElementById('title').value;
 console.log(title);
     formData.append('title',title);

    $.ajax({
        url: "{{ route('files.store') }}", // use Blade to get correct route
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function (response) {
            const filesSection = document.getElementById('uploadedFilesSection');
            const uploadForm = document.getElementById('toggleUploadForm');
            const backlist = document.getElementById('backlist');
            const uploadfileform = document.getElementById('uploadfileform');

            // Don't hide the #upload tab — just toggle sections within it
            filesSection.classList.remove('d-none');
            uploadForm.classList.remove('d-none');
            backlist.classList.add('d-none');
            uploadfileform.classList.add('d-none');
              window.location.reload(); // Reloads the current page

            console.log('Uploaded successfully', response);
           
        },
        error: function (xhr) {
            console.error('Upload failed:', xhr.responseText);
            alert('Upload failed: ' + xhr.responseText);
        }
    });
});
});

</script>


</body>

</html>