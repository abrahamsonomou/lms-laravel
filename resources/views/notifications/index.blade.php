@extends('layouts.dashboard')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('page-actions')
    <form method="POST" action="{{ route('notifications.readAll') }}">
        @csrf
        <button type="submit" class="btn btn-outline-primary"><i class="fe fe-check-circle me-1"></i> Tout marquer comme lu</button>
    </form>
@endsection

@section('content')
    @php($icones = ['inscription' => 'fe-user-check', 'paiement' => 'fe-credit-card', 'certificat' => 'fe-award'])
    @php($couleurs = ['inscription' => 'primary', 'paiement' => 'success', 'certificat' => 'warning'])

    <div class="card">
        <div class="list-group list-group-flush">
            @forelse ($notifications as $notification)
                <div class="list-group-item d-flex align-items-start {{ $notification->lu ? '' : 'bg-light' }}">
                    <div class="icon-shape icon-md bg-light-{{ $couleurs[$notification->type] ?? 'secondary' }} text-{{ $couleurs[$notification->type] ?? 'secondary' }} rounded-3 me-3">
                        <i class="fe {{ $icones[$notification->type] ?? 'fe-bell' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1 fw-semibold">
                                {{ $notification->titre }}
                                @unless ($notification->lu)
                                    <span class="badge bg-primary ms-1">Nouveau</span>
                                @endunless
                            </h5>
                            <small class="text-muted">{{ $notification->created_at?->diffForHumans() }}</small>
                        </div>
                        <p class="mb-0 text-muted">{{ $notification->message }}</p>
                    </div>
                    @unless ($notification->lu)
                        <form method="POST" action="{{ route('notifications.read', $notification) }}" class="ms-3">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Marquer comme lu"><i class="fe fe-check"></i></button>
                        </form>
                    @endunless
                </div>
            @empty
                <div class="list-group-item text-center py-5 text-muted">
                    <i class="fe fe-bell-off fs-2 d-block mb-2"></i>
                    Vous n'avez aucune notification.
                </div>
            @endforelse
        </div>
        @if ($notifications->hasPages())
            <div class="card-footer">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection
