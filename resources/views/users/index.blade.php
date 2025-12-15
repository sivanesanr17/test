@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ __('Users') }}</span>
                            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                                {{ __('Create User') }}
                            </a>
                        </div>

                        <input type="text" id="search" class="form-control" placeholder="Search by name or email">
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="user-table-body">
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->roles->isNotEmpty())
                                                    @foreach ($user->roles as $role)
                                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No role</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <a href="{{ route('users.show', $user) }}"
                                                    class="btn btn-info btn-sm">Show</a>
                                                <a href="{{ route('users.edit', $user) }}"
                                                    class="btn btn-warning btn-sm">Edit</a>
                                                <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No users found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div id="paginationLinks">
                            {{ $users->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        let timeout;
        document.getElementById('search').addEventListener('keyup', function() {
            clearTimeout(timeout);
            let keyword = this.value.trim();

            timeout = setTimeout(() => {
                axios.get("{{ route('users.index') }}", {
                        params: {
                            search: keyword
                        },
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json"
                        }
                    })
                    .then(response => {
                        const users = response.data.users;
                        const tbody = document.getElementById('user-table-body');
                        tbody.innerHTML = '';

                        if (!users.length) {
                            tbody.innerHTML =
                                `<tr><td colspan="6" class="text-center">No users found.</td></tr>`;
                            return;
                        }

                        users.forEach(user => {
                            let roles = user.roles.length ?
                                user.roles.map(r =>
                                    `<span class="badge bg-primary me-1">${r.name}</span>`)
                                .join('') :
                                `<span class="text-muted">No role</span>`;

                            tbody.innerHTML += `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.first_name} ${user.last_name ?? ''}</td>
                        <td>${user.email}</td>
                        <td>${roles}</td>
                        <td>${user.created_at}</td>
                        <td>
                            <a href="/users/${user.id}" class="btn btn-info btn-sm">Show</a>
                            <a href="/users/${user.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>`;
                        });

                        document.getElementById('paginationLinks').style.display = keyword ? 'none' :
                            'block';
                    })
                    .catch(err => console.error(err));
            }, 300);
        });
    </script>

@endsection
