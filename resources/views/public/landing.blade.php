<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
</head>
<body>
    <main>
        @forelse ($page->contenu_json ?? [] as $bloc)
            @php($type = $bloc['type'] ?? null)
            @if (in_array($type, ['hero', 'texte', 'cta'], true))
                @include('public.blocks.'.$type, ['bloc' => $bloc])
            @endif
        @empty
            <section class="container py-10 text-center">
                <p class="text-muted">Cette page est vide.</p>
            </section>
        @endforelse
    </main>

    @include('layouts.partials.scripts')
</body>
</html>
