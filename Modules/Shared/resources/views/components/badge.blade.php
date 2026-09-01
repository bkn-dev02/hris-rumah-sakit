@props([
'variant' => 'neutral',
'size' => 'md',
'dot' => false,
])

@php
$variants = [
'primary' => 'bg-[#edf5ee] text-[#1f4d3d]',
'secondary' => 'bg-[#dfeee1] text-[#1f4d3d]',
'success' => 'bg-emerald-50 text-emerald-700',
'danger' => 'bg-red-50 text-red-700',
'warning' => 'bg-amber-50 text-amber-700',
'info' => 'bg-[#edf5ee] text-[#1f4d3d]',
'neutral' => 'bg-slate-100 text-slate-600',
];

$dotColors = [
'primary' => 'bg-[#1f4d3d]',
'secondary' => 'bg-[#2a684f]',
'success' => 'bg-emerald-600',
'danger' => 'bg-red-600',
'warning' => 'bg-amber-600',
'info' => 'bg-[#2a684f]',
'neutral' => 'bg-slate-400',
];

$sizes = [
'sm' => 'px-2 py-0.5 text-[11px] gap-1',
'md' => 'px-2.5 py-1 text-xs gap-1.5',
];
@endphp

<span
    {{ $attributes->merge([
        'class' => "inline-flex items-center rounded-full font-medium
                    {$variants[$variant]} {$sizes[$size]}"
    ]) }}>
    @if($dot)
    <span class="h-1.5 w-1.5 rounded-full {{ $dotColors[$variant] }}"></span>
    @endif
    {{ $slot }}
</span>