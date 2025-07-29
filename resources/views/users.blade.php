@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">User Data ({{ $users->total() }} records)</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Birthday</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->contact_number }}</td>
                    <td>{{ $user->address }}</td>
                <td>{{ $user->birthday->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
</div>
@endsection
