@props(['variant' => 'default', 'title' => ''])

@php
    $variants = [
        'default' => 'bg-white text-gray-900 border-gray-200',
        'destructive' => 'border-red-500/50 text-red-600 dark:border-red-500 [&>svg]:text-red-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'relative w-full rounded-lg border p-4 [&>svg~*]:pl-7 [&>svg]:absolute [&>svg]:left-4 [&>svg]:top-4 [&>svg]:text-foreground ' . $variants[$variant]]) }}
    role="alert">
    {{ $slot }}
    @if ($title)
        <h5 class="mb-1 font-medium leading-none tracking-tight">{{ $title }}</h5>
    @endif
    <div class="text-sm [&_p]:leading-relaxed">
        {{ $content ?? '' }}
    </div>
</div>
