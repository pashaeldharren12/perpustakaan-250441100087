@extends('layouts.perpus')
@section('title', 'Kelola Pengguna')
@section('content')
    <h2 class="page-title">Kelola Pengguna &amp; Role</h2>

    @if ($errors->any()) <div class="flash flash-error">{{ $errors->first() }}</div> @endif

    <h3 style="margin-bottom:.5rem">Tambah Pengguna</h3>
    <form method="POST" action="{{ route('users.store') }}" class="grid-form">
        @csrf
        <input name="name" placeholder="Nama" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password (min 8)" required>
        <input name="password_confirmation" type="password" placeholder="Ulangi password" required>
        <select name="role">
            @foreach ($roles as $r) <option value="{{ $r }}">{{ ucfirst($r) }}</option> @endforeach
        </select>
        <button>Simpan</button>
    </form>

    <table class="tabel">
        <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Ubah Role</th><th>Hapus</th></tr></thead>
        <tbody>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->roles->pluck('name')->join(', ') }}</td>
                <td>
                    @can('kelola-role-pengguna')
                        <form method="POST" action="{{ route('users.role', $u) }}" class="inline-form">
                            @csrf @method('PATCH')
                            <select name="role">
                                @foreach ($roles as $r)
                                    <option value="{{ $r }}" @selected($u->hasRole($r))>{{ ucfirst($r) }}</option>
                                @endforeach
                            </select>
                            <button>Simpan</button>
                        </form>
                    @endcan
                </td>
                <td>
                    @can('kelola-data-pengguna')
                        <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline-form" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf @method('DELETE')<button>Hapus</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $users->links() }}
@endsection
