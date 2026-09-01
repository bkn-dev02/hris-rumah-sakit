@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#dfeee1] focus:border-[#2a684f] focus:ring-[#dfeee1] rounded-md shadow-sm']) }}>
