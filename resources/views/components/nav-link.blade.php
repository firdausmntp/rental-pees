@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-primary bg-primary/10 ring-1 ring-primary/20 transition duration-150 ease-in-out'
            : 'inline-flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-base-content/70 hover:text-primary hover:bg-primary/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
