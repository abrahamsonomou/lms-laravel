@auth
    @if (auth()->user()->isEtudiant())
        <form method="POST" action="{{ route('student.formations.enroll', $formation) }}">
            @csrf
            <button type="submit" class="btn btn-primary {{ $class ?? '' }}">
                <i class="fe fe-user-plus me-1"></i> S'inscrire à cette formation
            </button>
        </form>
    @else
        <a href="{{ route(auth()->user()->homeRouteName()) }}" class="btn btn-outline-primary {{ $class ?? '' }}">
            Accéder à mon espace
        </a>
    @endif
@else
    <a href="{{ route('register') }}" class="btn btn-primary {{ $class ?? '' }}">
        <i class="fe fe-user-plus me-1"></i> S'inscrire
    </a>
@endauth
