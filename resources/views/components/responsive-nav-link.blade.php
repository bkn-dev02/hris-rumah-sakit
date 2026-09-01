@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#2a684f] dark:border-[#1f4d3d] text-start text-base font-medium text-[#1f4d3d] dark:text-[#dfeee1] bg-[#edf5ee] dark:bg-[#173f34]/50 focus:outline-none focus:text-[#1f4d3d] dark:focus:text-[#dfeee1] focus:bg-[#edf5ee] dark:focus:bg-[#173f34] focus:border-[#1f4d3d] dark:focus:border-[#1f4d3d] transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

