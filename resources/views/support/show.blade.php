@extends('layouts.dashboard')

@section('title', $conversation->titre)
@section('page-title', $conversation->titre)

@section('page-actions')
    <a href="{{ route('support.index') }}" class="btn btn-outline-secondary"><i class="fe fe-arrow-left me-1"></i> Retour</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @foreach ($conversation->messages as $message)
                        @php($estMoi = $message->user_id === auth()->id())
                        <div class="d-flex mb-3 {{ $estMoi ? 'justify-content-end' : '' }}">
                            <div class="{{ $estMoi ? 'text-end' : '' }}" style="max-width: 75%;">
                                <div class="p-3 rounded-3 {{ $estMoi ? 'bg-primary text-white' : 'bg-light' }}">
                                    {!! nl2br(e($message->contenu)) !!}
                                </div>
                                <small class="text-muted">
                                    {{ $message->user?->name ?? 'Utilisateur' }} · {{ $message->date_envoi?->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('support.reply', $conversation) }}">
                        @csrf
                        <div class="input-group">
                            <textarea name="contenu" rows="2" class="form-control @error('contenu') is-invalid @enderror"
                                      placeholder="Écrire un message…" required>{{ old('contenu') }}</textarea>
                            <button type="submit" class="btn btn-primary"><i class="fe fe-send"></i></button>
                            @error('contenu')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
