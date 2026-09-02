@component('mail::message')
# {{ $titre }}

{{ $intro }}

@if (! empty($lignes))
@foreach ($lignes as $ligne)
- {{ $ligne }}
@endforeach
@endif

@if ($actionUrl)
@component('mail::button', ['url' => $actionUrl])
{{ $actionText ?? 'Voir' }}
@endcomponent
@endif

Merci,<br>
{{ config('app.name') }}
@endcomponent
