@props([
'variant' => 'primary',
'size' => 'md',
'icon' => null,
'iconPosition' => 'left',
'loading' => false,
'type' => 'button',
])

@php
$variants = [
'primary' => 'bg-sky-900 hover:bg-sky-950 text-sky-200',
'secondary' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
'outline' => 'border border-sky-600 text-sky-900 hover:bg-sky-100',
'ghost' => 'text-slate-600 hover:bg-slate-100',
'danger' => 'bg-red-600 text-white hover:bg-red-700',
'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
];

$sizes = [
'sm' => 'px-3 py-1.5 text-xs gap-1.5',
'md' => 'px-4 py-2 text-sm gap-2',
'lg' => 'px-5 py-2.5 text-base gap-2.5',
];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => "inline-flex items-center justify-center rounded-full font-medium
                    transition duration-200 translate-y-0 hover:-translate-y-1 hover:shadow-lg
                    focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                    disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:shadow-none
                    {$variants[$variant]} {$sizes[$size]} cursor-pointer"
    ]) }}
    @if($loading) disabled @endif>
    @if($loading)
    <i class="fa-solid fa-circle-notch fa-spin"></i>
    @elseif($icon && $iconPosition === 'left')
    <i class="{{ $icon }} text-sm"></i>
    @endif

    <span class="{{ $variant === 'primary' ? 'text-gray-100' : '' }}">{{ $slot }}</span>

    @if(!$loading && $icon && $iconPosition === 'right')
    <i class="{{ $icon }} text-sm"></i>
    @endif
</button>