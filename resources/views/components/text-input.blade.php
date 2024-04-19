@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-400 bg-transparent text-white focus:border-blue-accent focus:ring-blue-accent rounded-md shadow-sm ']) !!}>

</input>