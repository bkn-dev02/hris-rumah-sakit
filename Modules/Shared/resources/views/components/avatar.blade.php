@props([
'src' => null,
'name' => null,
'size' => 'md',
'status' => null,
])

@php
$sizes = [
'xs' => 'h-6 w-6 text-[10px]',
'sm' => 'h-8 w-8 text-xs',
'md' => 'h-10 w-10 text-sm',
'lg' => 'h-12 w-12 text-base',
'xl' => 'h-16 w-16 text-lg',
];

$statusColors = [
'online' => 'bg-emerald-500',
'offline' => 'bg-slate-400',
'away' => 'bg-amber-500',
'busy' => 'bg-red-500',
];

$initials = $name
? collect(explode(' ', trim($name)))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('')
: '?';
@endphp

<span {{ $attributes->merge(['class' => 'relative inline-flex shrink-0']) }}>
    @if($src)
    <img
        src="{{ $src }}"
        alt="{{ $name ?? 'Avatar' }}"
        class="{{ $sizes[$size] }} rounded-full object-cover">
    @else
    <span class="{{ $sizes[$size] }} flex items-center justify-center rounded-full bg-blue-100 font-semibold uppercase text-blue-700">
        {{ $initials }}
    </span>
    @endif

    @if($status)
    <span class="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full border-2 border-white {{ $statusColors[$status] ?? 'bg-slate-400' }}"></span>
    @endif
</span>