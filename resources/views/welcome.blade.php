<x-main-layout>

  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
      x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
      class="max-w-7xl mx-auto ">
      <h4 class=" font-bold text-white/60 rounded-full bg-white/10 w-fit m-auto p-1 mb-2">Welcome to the Shaghalni.</h4>
    </div>
  </div>

  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
      x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
      class="max-w-7xl mx-auto ">
      <h1 class="font-bold text-4xl sm:text-6xl md:text-8xl tracking-tighter">
        <span class="text-white">Find Your</span>
        <br />
        <span class="text-white/60 font-serif italic">Dream Job.</span>
      </h1>
    </div>
  </div>

  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
      x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
      class="max-w-7xl mx-auto ">
      <p class="text-white/60 text-lg mt-2">Connect with top employers, and find Exiting opportunities.</p>
    </div>
  </div>

  <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
    <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-700"
      x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
      class="max-w-7xl mx-auto ">
      <div class="mt-4 flex space-x-4 items-center justify-center width-full">
        <a class="inline-flex items-center px-3 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-wide bg-white/5 hover:bg-white/10 focus:bg-white/10 active:bg-white/10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          href="{{ route('login') }}">
          Sign In
        </a>
        <a class="inline-flex items-center px-3 py-2 border border-transparent rounded-lg font-semibold text-xs text-black uppercase tracking-wide bg-white hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          href="{{ route('register') }}">
          Create an Account
        </a>
      </div>
    </div>
  </div>
</x-main-layout>
