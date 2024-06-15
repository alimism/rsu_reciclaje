@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-browncustom focus:ring-browncustom rounded-md shadow-sm']) !!}>
