@php
  $startIndex = ($jobApplications->currentPage() - 1) * $jobApplications->perPage();
  $statusColors = [
      'pending' => 'bg-yellow-600',
      'accepted' => 'bg-green-600',
      'rejected' => 'bg-red-600',
  ];
@endphp
<x-app-layout>
  <x-slot name="header">
    <h3 class="text-2xl text-white font-bold">
      {{ __('My Applications') }}
    </h3>
  </x-slot>

  <!-- validate session -->
  @include('components.toast')

  <div class="w-full max-w-7xl m-auto p-6 rounded-lg shadow-md">
    @forelse ($jobApplications as $index => $jobApplication)
      <div class="bg-black/30 p-6 rounded-lg shadow-md my-4">
        {{-- job Vacancy details --}}
        <div class="pb-6">
          <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-white mb-2">{{ $jobApplication->jobVacancy->title }}</h1>

              <p class="text-gray-400 text-sm">
                at
                <span class="font-semibold text-white">{{ $jobApplication->jobVacancy->company->name }}</span>
                -
                <span class="font-semibold text-white">{{ $jobApplication->jobVacancy->location }}</span>
              </p>

              <div class="flex text-sm">
                <p>
                  <span class="font-semibold text-white">{{ $jobApplication->jobVacancy->jobCategory->name }}</span>
                </p>
                <span class="mx-2 text-gray-400">-</span>
                <p>
                  <span class="font-semibold text-white">${{ number_format($jobApplication->jobVacancy->salary) }} /
                    Year</span>
                </p>
              </div>

              <p class="inline-bloc text-white font-semibold">Type: {{ $jobApplication->jobVacancy->type }}</p>


              <div class="my-4">

                <p class="mb-2">
                  <span class="font-semibold text-white bg-blue-600 py-1 px-2 rounded-lg">Score:
                    {{ $jobApplication->aiGeneratedScore }}</span>
                  <span
                    class="font-semibold text-white py-1 px-2 rounded-lg {{ $statusColors[$jobApplication->status] }} capitalize">{{ $jobApplication->status }}</span>

                </p>

                <p class="text-gray-200 max-w-xl">
                  <span class="font-semibold">Feedback:</span>
                  {{ $jobApplication->aiGeneratedFeedback ? $jobApplication->aiGeneratedFeedback : 'No feedback provided.' }}
                </p>

              </div>

              <p>
                Applied With: {{ $jobApplication->resume->filename }}
                <a href="{{ Storage::disk('cloud')->download($jobApplication->resume->fileUri, $jobApplication->resume->filename) }}"
                  target="_blank" class="mx-2 text-blue-400">
                  View Resume
                </a>
              </p>



              <p class="text-gray-400">
                Applied at: {{ $jobApplication->created_at->format('F j, Y') }}
              </p>

            </div>


          </div>
        </div>
      </div>

    @empty
      <tr>
        <td colspan="4" class="text-center py-4 text-gray-500"> No Job Vacancy found.</td>
      </tr>
    @endforelse



    <div class="mt-4">
      {{ $jobApplications->withQueryString()->links() }}
    </div>
  </div>


</x-app-layout>
