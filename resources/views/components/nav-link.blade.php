@props(['active'])

@php
    $baseClasses = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition';
    $activeClasses = 'border-indigo-500 text-gray-900 dark:text-white';
    $inactiveClasses = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-300 dark:hover:text-white dark:hover:border-gray-600';

    $finalClasses = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['class' => $finalClasses]) }}>
    {{ $slot }}
</a>
