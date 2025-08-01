<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Upload - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Upload File</h4>
        </div>

        <div class="card-body">
            {{-- Upload Form --}}
            <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                @csrf
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="file" name="upload_file" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Upload File</button>
                    </div>
                </div>
            </form>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success mt-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Uploaded Files List --}}
            @php
                $files = \Illuminate\Support\Facades\Storage::disk('public')->files('uploads');
            @endphp

            @if(!empty($files))
                <h5 class="mt-4">Uploaded Files</h5>
                <ul class="list-group">
                    @foreach($files as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ basename($file) }}
                            <div class="d-flex gap-2">
                                {{-- View Form --}}
                                <form action="{{ route('file.view') }}" method="POST" target="_blank">
                                    @csrf
                                    <input type="hidden" name="file" value="{{ $file }}">
                                    <button type="submit" class="btn btn-sm btn-secondary">View</button>
                                </form>

                                {{-- Delete Form --}}
                                <form action="{{ route('file.delete') }}" method="POST" onsubmit="return confirm('Are you sure to delete this file?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="file" value="{{ $file }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mt-4">No files uploaded yet.</p>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
