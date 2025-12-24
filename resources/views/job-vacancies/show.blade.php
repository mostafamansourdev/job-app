<x-app-layout>
  <x-slot name="header">
    <h3 class="text-2xl text-white font-bold">
      {{ __($jobVacancy->title) }}
    </h3>
  </x-slot>
  <div class="py-5 px-2">
    <div class="bg-black shadow rounded p-6 max-w-7xl mx-auto">
      {{-- Content goes here --}}

      {{-- back button --}}
      <div class="mb-6">
        <a href="{{ route('dashboard') }}"
          class="inline-block bg-gray-800 hover:bg-gray-700 mb-5 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          ← Back
        </a>

        <div class="pb-6">
          <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-white mb-2">{{ $jobVacancy->title }}
                <span
                  class="inline-block bg-blue-600 text-white text-xs px-2 py-1 rounded-full">{{ $jobVacancy->type }}</span>
              </h1>

              <p class="text-gray-400 text-sm">
                at
                <span class="font-semibold text-white">{{ $jobVacancy->company->name }}</span>
                -
                <span class="font-semibold text-white">{{ $jobVacancy->location }}</span>
              </p>

              <div class="flex text-sm">
                <p>
                  <span class="font-semibold text-white">{{ $jobVacancy->jobCategory->name }}</span>
                </p>
                <span class="mx-2 text-gray-400">-</span>
                <p>
                  <span class="font-semibold text-white">${{ number_format($jobVacancy->salary) }} / Year</span>
                </p>

              </div>


              <p class="my-4 text-gray-200 max-w-xl">
                {{ $jobVacancy->description }}
              </p>
              <p>
                <span class="text-gray-400">Company Website:</span>
                <a target="_blank" href="{{ $jobVacancy->company->website }}"
                  class="text-blue-500 hover:text-blue-700 hover:underline font-semibold">{{ $jobVacancy->company->website }}</a>
              </p>


              <p class="text-gray-400">
                Posted on: {{ $jobVacancy->created_at->format('F j, Y') }}
              </p>

            </div>

            <div class="mt-6 self-end">
              <a href="{{ route('job-vacancies.apply', $jobVacancy->id) }}"
                class=" bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Apply Now
              </a>
            </div>
          </div>
        </div>

      </div>

    </div>
</x-app-layout>
