<button
  {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-3 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest bg-white hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
  {{ $slot }}
</button>
