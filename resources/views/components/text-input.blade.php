@props(['disabled' => false])

<input @disabled($disabled)
  {{ $attributes->merge(['class' => 'bg-transparent  border border-white/10 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm']) }}>
