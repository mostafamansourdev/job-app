<x-app-layout>
  <x-slot name="header">
    <h3 class="text-2xl text-white font-bold">
      {{ __('Welcome Back,') }} {{ Auth::user()->name }}!
    </h3>
  </x-slot>

  <div class="py-5 px-2">
    <div class="bg-black shadow rounded p-6 max-w-7xl mx-auto">

      {{-- Search and Filter --}}
      <div class="flex flex-col space-y-6 md:flex-row md:space-y-0 md:space-x-8 items-center justify-between">


        {{-- Search Bar --}}
        <form method="GET" action="{{ route('dashboard') }}" class=" w-full max-w-md flex">
          <input type="text" name="search" placeholder="Search job vacancies..."
            class="w-full px-4 py-2 rounded-l-lg bg-gray-900 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            value="{{ request('search') }}">
          <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Search
          </button>
          @if (request('search'))
            <a href="{{ route('dashboard', ['type' => request('type')]) }}"
              class="px-4 py-2 text-white rounded-lg hover:text-gray-300 focus:outline-none  ">
              clear
            </a>
          @endif
          @if (request('type'))
            <input type="hidden" name="type" value="{{ request('type') }}">
          @endif
        </form>


        {{-- Filters --}}
        <div
          class="flex space-x-0 border border-white rounded-lg text-xs *:px-2 *:py-2 mb-4 sm:mb-0 *:w-max md:*:px-3 md:*:py-2 md:text-sm">
          <a href="{{ route('dashboard', ['search' => request('search')]) }}"
            class="hover:bg-white/20 border-l-0 rounded-l-lg {{ !request('type') ? 'bg-blue-500' : '' }}">
            All
          </a>
          <a href="{{ route('dashboard', ['type' => 'Full-Time', 'search' => request('search')]) }}"
            class="hover:bg-white/20 border-l {{ request('type') == 'Full-Time' ? 'bg-blue-500' : '' }}">
            Full Time
          </a>
          <a href="{{ route('dashboard', ['type' => 'Remote', 'search' => request('search')]) }}"
            class="hover:bg-white/20 border-l {{ request('type') == 'Remote' ? 'bg-blue-500' : '' }}">
            Remote
          </a>
          <a href="{{ route('dashboard', ['type' => 'Hybrid', 'search' => request('search')]) }}"
            class="hover:bg-white/20 border-l {{ request('type') == 'Hybrid' ? 'bg-blue-500' : '' }}">
            Hybrid
          </a>
          <a href="{{ route('dashboard', ['type' => 'Contract', 'search' => request('search')]) }}"
            class="hover:bg-white/20 border-l rounded-r-lg {{ request('type') == 'Contract' ? 'bg-blue-500' : '' }}">
            Contract
          </a>
        </div>
      </div>


      {{-- job list --}}
      <div class="mt-12 ">
        @forelse ($jobs as $job)
          <div class="relative w-full my-6 *:leading-6 border-b border-white/10 pb-2">
            <a href="{{ route('job-vacancies.show', $job->id) }}"
              class="text-2xl font-extrabold text-blue-500 hover:text-blue-700">
              {{ $job->title }}
              <span class="relative mt-2 text-xs py-0 px-0 text-white/60 font-normal md:hidden">
                -
              </span>
              <span
                class="relative mt-2 text-xs py-0 px-0 text-white/60 font-normal md:absolute md:top-0 md:right-0 md:py-1 md:px-2 md:rounded-lg md:bg-blue-500 md:text-sm md:text-white">
                {{ $job->type == 'Full-Time' ? 'Full Time' : $job->type }}
              </span>
            </a>

            <p class="text-md">{{ $job->company->name }} - {{ $job->location }} </p>

            <p class="text-sm text-white/60">${{ number_format($job->salary) }} / Year</p>

          </div>
        @empty
          <p class="text-white/60">No job vacancies found.</p>
        @endforelse

        {{ $jobs->links() }}

        {{-- <p class="text-white/60">No job vacancies found.</p> --}}
      </div>

    </div>
  </div>
</x-app-layout>
