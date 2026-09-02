<section class="bg-light py-8 py-lg-10">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @isset($bloc['titre'])
                    <h1 class="display-4 fw-bold mb-3">{{ $bloc['titre'] }}</h1>
                @endisset
                @isset($bloc['sous_titre'])
                    <p class="lead mb-4">{{ $bloc['sous_titre'] }}</p>
                @endisset
                @if (! empty($bloc['bouton_texte']) && ! empty($bloc['bouton_url']))
                    <a href="{{ $bloc['bouton_url'] }}" class="btn btn-primary btn-lg">{{ $bloc['bouton_texte'] }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
