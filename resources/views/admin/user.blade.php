@extends('layouts.auth') <!-- Extend the main layout -->

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Users</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            {{-- <th>Username</th> --}}
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Home Address</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                {{-- <td>{{ $user->username }}</td> --}}
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>{{ $user->home_address }}</td>
                                <td>
                                    <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('Y-m-d H:i:s a') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- <a href="" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="" method="POST" onsubmit="return confirmDelete(event)"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form> --}}
                                        <form action="{{ route('user.activation', $user->id) }}" method="POST"
                                            onsubmit="return confirmDelete(event)" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ $user->status }}">
                                            <button type="submit" class="btn btn-sm {{ $user->status === 'inactive' ? 'btn-success' : 'btn-danger' }}">
                                                {{ $user->status === 'inactive' ? 'Activate' : 'Deactivate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmDelete(event) {
        if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            event.preventDefault();
        }
    }
</script>
