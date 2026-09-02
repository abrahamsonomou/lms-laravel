@props(['user', 'size' => 'md'])

<div {{ $attributes->merge(['class' => "avatar avatar-{$size}"]) }}>
    @if ($user?->avatarUrl())
        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="rounded-circle" />
    @else
        <span class="avatar-initials rounded-circle bg-primary text-white">{{ $user?->initials() ?? 'U' }}</span>
    @endif
</div>
