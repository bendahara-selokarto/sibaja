@props(['readonly' => true])

<input @readonly($readonly) {{ $attributes->merge(['class' => 'border-gray-300 bg-gray-300 rounded-md shadow-sm']) }}>
