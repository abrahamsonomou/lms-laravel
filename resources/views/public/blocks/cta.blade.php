<section class="bg-primary py-7">
    <div class="container text-center">
        @isset($bloc['titre'])
            <h2 class="text-white fw-bold mb-4">{{ $bloc['titre'] }}</h2>
        @endisset
        @if (! empty($bloc['bouton_texte']) && ! empty($bloc['bouton_url']))
            <a href="{{ $bloc['bouton_url'] }}" class="btn btn-white btn-lg">{{ $bloc['bouton_texte'] }}</a>
        @endif
    </div>
</section>
