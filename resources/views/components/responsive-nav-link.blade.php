@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex w-full items-center rounded-lg bg-primary/10 px-4 py-3 text-base font-semibold text-primary transition duration-150 ease-in-out'
            : 'flex w-full items-center rounded-lg px-4 py-3 text-base font-medium text-base-content/70 hover:bg-primary/10 hover:text-primary transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
