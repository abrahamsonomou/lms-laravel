@extends('layouts.dashboard')

@section('title', 'Permissions')
@section('page-title', 'Permissions')
@section('page-subtitle', 'Référentiel des permissions (lecture seule)')

@section('content')
    <div class="row">
        @forelse ($permissions as $module => $modulePermissions)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="mb-0 text-uppercase">{{ $module }}</h4>
                        <span class="badge bg-light-primary text-primary">{{ $modulePermissions->count() }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                @foreach ($modulePermissions as $permission)
                                    <tr>
                                        <td class="align-middle"><code>{{ $permission->code }}</code></td>
                                        <td class="align-middle">{{ $permission->nom }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-4 text-muted">Aucune permission trouvée.</div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
