@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-700 bg-gray-900 text-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm w-full placeholder-gray-500 transition duration-200']) !!}>