@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-sky-600']) }}>
    {{ $value ?? $slot }}
</label>