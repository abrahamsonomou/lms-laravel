@extends('layouts.dashboard')

@section('title', 'Support')
@section('page-title', 'Support')
@section('page-subtitle', 'Vos échanges avec notre équipe')

@section('page-actions')
    @unless (auth()->user()->isStaff())
        <a href="{{ route('support.create') }}" class="btn btn-primary"><i class="fe fe-plus me-1"></i> Nouvelle demande</a>
    @endunless
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Sujet</th>
                        @if (auth()->user()->isStaff())<th>Client</th>@endif
                        <th>Messages</th>
                        <th>Dernière activité</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conversations as $conversation)
                        <tr>
                            <td class="align-middle fw-semibold">{{ $conversation->titre }}</td>
                            @if (auth()->user()->isStaff())<td class="align-middle">{{ $conversation->creator?->name ?? '—' }}</td>@endif
                            <td class="align-middle"><span class="badge bg-light-secondary text-secondary">{{ $conversation->messages_count }}</span></td>
                            <td class="align-middle">{{ $conversation->updated_at?->diffForHumans() }}</td>
                            <td class="align-middle text-end">
                                <a href="{{ route('support.show', $conversation) }}" class="btn btn-sm btn-outline-primary">Ouvrir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fe fe-message-square fs-2 d-block mb-2"></i>
                                Aucune conversation pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $conversations->links() }}</div>
    </div>
@endsection
