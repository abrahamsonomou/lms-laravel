@php($user = auth()->user())
<div class="header">
    <nav class="navbar-default navbar navbar-expand-lg">
        <a id="nav-toggle" href="#"><i class="fe fe-menu"></i></a>

        <div class="ms-lg-3 d-none d-md-none d-lg-block">
            <span class="h4 mb-0 fw-bold">{{ config('app.name', 'LMS') }}</span>
        </div>

        <div class="ms-auto d-flex align-items-center">
            <ul class="navbar-nav navbar-right-wrap ms-2 d-flex nav-top-wrap align-items-center">
                @php($notifsRecentes = $user?->appNotifications()->take(6)->get() ?? collect())
                @php($nonLues = $notifsRecentes->where('lu', false)->count())
                <li class="dropdown">
                    <a class="btn btn-light btn-icon rounded-circle {{ $nonLues > 0 ? 'indicator indicator-primary' : '' }}"
                       href="#" role="button" id="dropdownNotif" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fe fe-bell"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg" aria-labelledby="dropdownNotif">
                        <div class="border-bottom px-3 pb-2 pt-2 d-flex justify-content-between align-items-center">
                            <span class="h4 mb-0">Notifications</span>
                            @if ($nonLues > 0)
                                <span class="badge bg-primary">{{ $nonLues }} non lue(s)</span>
                            @endif
                        </div>
                        <ul class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                            @forelse ($notifsRecentes as $notif)
                                <li class="list-group-item {{ $notif->lu ? '' : 'bg-light' }}">
                                    <h5 class="mb-1 fw-semibold">{{ $notif->titre }}</h5>
                                    <p class="mb-1 small text-muted">{{ \Illuminate\Support\Str::limit($notif->message, 80) }}</p>
                                    <span class="fs-6 text-muted">{{ $notif->created_at?->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">Aucune notification.</li>
                            @endforelse
                        </ul>
                        <div class="border-top px-3 pt-2">
                            <a href="{{ route('notifications.index') }}" class="text-link fw-semibold">Voir toutes les notifications</a>
                        </div>
                    </div>
                </li>
                <li class="dropdown ms-2">
                    <a class="rounded-circle" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                        <x-user-avatar :user="$user" size="md" class="avatar-indicators avatar-online" />
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                        <div class="dropdown-item">
                            <div class="d-flex">
                                <x-user-avatar :user="$user" size="md" class="avatar-indicators avatar-online" />
                                <div class="ms-3 lh-1">
                                    <h5 class="mb-1">{{ trim(($user?->prenom ?? '').' '.($user?->nom ?? '')) ?: $user?->name }}</h5>
                                    <p class="mb-0 text-muted">{{ $user?->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled">
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="fe fe-globe me-2"></i> Site public
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fe fe-power me-2"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
