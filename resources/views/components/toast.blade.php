{{-- message --}}
<div class="max-w-sm absolute top-10 right-10 mx-auto z-50">

  @if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
      class="relative bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 pr-10">
      {{ session('success') }}
      <span class="cursor-pointer px-4 absolute top-1/2 right-0 -translate-y-1/2 font-bold text-xl hover:text-red-950"
        @click=" show = false">
        x
      </span>
    </div>
  @endif
  @if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
      class="relative bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 pr-10">
      {{ session('error') }}
      <span class="cursor-pointer px-4 absolute top-1/2 right-0 -translate-y-1/2 font-bold text-xl hover:text-red-950"
        @click=" show = false">
        x
      </span>
    </div>
  @endif

</div>
