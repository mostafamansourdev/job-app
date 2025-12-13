<x-app-layout>
  <x-slot name="header">
    <h3 class="text-2xl text-white font-bold">
      {{ __('Job Vacancy') }}
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


        {{-- job Vacancy details --}}
        <div class="mb-4">
          <h3 class="text-lg font-semibold mb-2">Job vacancy details</h3>
          <p class="text-gray-200"><strong>Title:</strong> {{ $jobVacancy->title }}</p>
          <p class="text-gray-200"><strong>Description:</strong> {{ $jobVacancy->description }}</p>
          <p class="text-gray-200"><strong>Location:</strong> {{ $jobVacancy->location }}</p>
          <p class="text-gray-200"><strong>Salary:</strong> {{ $jobVacancy->salary }}</p>
          <p class="text-gray-200"><strong>Type:</strong> {{ $jobVacancy->type }}</p>
        </div>

        {{-- show the job Category name --}}
        <div class="mb-4">
          <h3 class="text-lg font-semibold mb-2 inline-block">Job Category: </h3>
          <span>{{ $jobVacancy->jobCategory->name }}</span>
        </div>

        <div class="mb-4">
          <h3 class="text-lg font-semibold mb-2 inline-block">Company Website: </h3>
          <span><a target="_blank" href="{{ $jobVacancy->company->website }}"
              class="text-blue-500 hover:text-blue-700 hover:underline">{{ $jobVacancy->company->website }}</a></span>
        </div>



      </div>

    </div>

  </div>
</x-app-layout>
