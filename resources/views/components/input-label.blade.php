@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#2a684f]']) }}>
    {{ $value ?? $slot }}
</label>
