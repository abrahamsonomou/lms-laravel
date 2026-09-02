@extends('layouts.dashboard')

@section('title', $formation->titre)
@section('page-title', $formation->titre)

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('student.formations.evaluations.index', $formation) }}" class="btn btn-outline-info">
            <i class="fe fe-help-circle me-1"></i> Évaluations
        </a>
        <a href="{{ route('student.formations.index') }}" class="btn btn-outline-secondary">
            <i class="fe fe-arrow-left me-1"></i> Retour à mes formations
        </a>
    </div>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-semibold">Progression globale</span>
                <span class="fw-semibold">{{ number_format($progression, 0) }}% complété</span>
            </div>
            <div class="progress" style="height: 10px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $progression }}%;"
                     aria-valuenow="{{ $progression }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            @if ($progression >= 100)
                <div class="alert alert-success d-flex justify-content-between align-items-center mt-3 mb-0">
                    <span><i class="fe fe-award me-1"></i> Formation terminée ! Vous pouvez obtenir votre certificat.</span>
                    <form method="POST" action="{{ route('student.certificats.store', $formation) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Obtenir mon certificat</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Sommaire --}}
        <div class="col-lg-4 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="mb-0">Sommaire</h4>
                </div>
                <div class="card-body p-0">
                    @php($aDesLecons = false)
                    @forelse ($formation->cours ?? [] as $cours)
                        <div class="px-3 pt-3">
                            <span class="text-uppercase fw-bold text-muted small">{{ $cours->titre }}</span>
                        </div>
                        @forelse ($cours->modules ?? [] as $module)
                            <div class="px-3 pt-2">
                                <span class="text-muted small fw-semibold">{{ $module->titre }}</span>
                            </div>
                            @forelse ($module->chapitres ?? [] as $chapitre)
                                <div class="px-4 pt-1">
                                    <span class="text-muted small">{{ $chapitre->titre }}</span>
                                </div>
                                <div class="list-group list-group-flush mb-2">
                                    @forelse ($chapitre->lecons ?? [] as $lecon)
                                        @php($aDesLecons = true)
                                        @php($estTerminee = in_array($lecon->id, $completedIds))
                                        @php($estActive = $leconCourante && $leconCourante->id === $lecon->id)
                                        <a href="{{ route('student.formations.show', ['formation' => $formation, 'lecon' => $lecon->id]) }}"
                                           class="list-group-item list-group-item-action d-flex align-items-center {{ $estActive ? 'active bg-light-primary text-primary fw-semibold' : '' }}">
                                            <i class="fe {{ $estTerminee ? 'fe-check-circle text-success' : 'fe-circle text-muted' }} me-2"></i>
                                            <span>{{ $lecon->titre }}</span>
                                        </a>
                                    @empty
                                    @endforelse
                                </div>
                            @empty
                            @endforelse
                        @empty
                        @endforelse
                    @empty
                    @endforelse

                    @unless ($aDesLecons)
                        <div class="p-3 text-muted">Aucune leçon disponible pour le moment.</div>
                    @endunless
                </div>
            </div>
        </div>

        {{-- Contenu --}}
        <div class="col-lg-8 col-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    @if ($leconCourante === null)
                        <div class="text-center py-6 text-muted">
                            <i class="fe fe-book-open fs-2 mb-2 d-block"></i>
                            Cette formation n'a pas encore de leçon disponible.
                        </div>
                    @else
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h3 class="mb-0">{{ $leconCourante->titre }}</h3>
                            @if ($leconCourante->type)
                                <span class="badge bg-light-info text-info">{{ $leconCourante->type }}</span>
                            @endif
                        </div>

                        @if (!empty($leconCourante->description))
                            <p class="text-muted">{!! nl2br(e($leconCourante->description)) !!}</p>
                        @endif

                        @forelse ($leconCourante->contenus ?? [] as $contenu)
                            <div class="mb-3">
                                @if (!empty($contenu->url))
                                    @php($url = $contenu->url)
                                    @php($estEmbed = $contenu->type === 'VIDEO' && \Illuminate\Support\Str::contains($url, ['youtube.com', 'youtu.be', 'vimeo.com']))
                                    @if ($estEmbed)
                                        @php($embedUrl = \Illuminate\Support\Str::of($url)
                                            ->replace('watch?v=', 'embed/')
                                            ->replace('youtu.be/', 'youtube.com/embed/')
                                            ->replace('vimeo.com/', 'player.vimeo.com/video/'))
                                        <div class="ratio ratio-16x9">
                                            <iframe src="{{ $embedUrl }}" title="{{ $leconCourante->titre }}"
                                                    allowfullscreen loading="lazy"></iframe>
                                        </div>
                                    @else
                                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                                            <i class="fe fe-external-link me-1"></i> Ouvrir la ressource
                                        </a>
                                    @endif
                                @elseif (!empty($contenu->fichier))
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($contenu->fichier) }}"
                                       target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary">
                                        <i class="fe fe-download me-1"></i> Télécharger
                                    </a>
                                @endif
                            </div>
                        @empty
                        @endforelse

                        <div class="border-top pt-3 mt-3">
                            @if (in_array($leconCourante->id, $completedIds))
                                <span class="badge bg-light-success text-success">
                                    <i class="fe fe-check me-1"></i> Terminé
                                </span>
                            @else
                                <form method="POST" action="{{ route('student.lecons.complete', $leconCourante) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="fe fe-check-circle me-1"></i> Marquer comme terminé
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
