<x-app-layout>
  <x-slot name="header">
    <h3 class="text-2xl text-white font-bold">
      {{ __($jobVacancy->title) }} - Apply

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

        <div class="border-b border-white/10 pb-6">
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
                <span class="mx-2 text-gray-400">|</span>
                <p>
                  <span class="font-semibold text-white">${{ number_format($jobVacancy->salary) }} / Year</span>
                </p>
              </div>

            </div>
          </div>

        </div>

        <form action="{{ route('job-vacancies.processApplication', $jobVacancy->id) }}" method="POST"
          enctype="multipart/form-data" class="space-y-6">
          @csrf
          {{-- resume selection --}}
          <div>
            <h3 class="text-xl font-semibold text-white mb-4">Choose Your resume.</h3>
            <div class="mb-6">
              <x-input-label for="resume" value="Select from your Existing Resumes:" />
              @error('resume_option')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
              {{-- list of Resumes --}}
              <div class="space-y-4 mt-2">
                @forelse ($resumes as $resume)
                  <div>
                    <x-input-label for="{{ $resume->id }}" class="flex items-center space-x-3 cursor-pointer">
                      <input type="radio" name="resume_option" value="{{ $resume->id }}" id="{{ $resume->id }}"
                        {{ old('resume_id') == $resume->id ? 'checked' : '' }}
                        @error('resume_option')
                          class="border-red-500"
                        @else
                          class="form-radio text-blue-600 focus:ring-blue-500"
                        @enderror />
                      <span class="text-white">{{ $resume->filename }}</span>
                      <span class="text-gray-400 text-xs text-bottom">Updated
                        {{ $resume->updated_at->diffForHumans() }}</span>
                    </x-input-label>
                  </div>
                @empty
                  <p class="text-gray-400">No resumes found.</p>
                @endforelse
              </div>

            </div>

            {{-- upload new resume --}}
            <div x-data="{ fileName: '', hasError: '{{ $errors->has('resume_file') }}' }" class="mt-4">
              <div class="flex items-center space-x-3 cursor-pointer">
                <input x-ref="newResumeRadio" type="radio" name="resume_option" value="new_resume" id="new_resume"
                  {{ old('resume_option') == 'new_resume' ? 'checked' : '' }}
                  @error('resume_option')
                class="border-red-500"
                @else
                class="form-radio text-blue-600 focus:ring-blue-500"
                @enderror />
                <x-input-label for="new_resume" value="Upload a New Resume:" class="hover:cursor-pointer" />
              </div>

              <div class="flex items-center">
                <div class="flex-1">
                  <label for="new_resume_file" class="block text-white cursor-pointer">
                    <div
                      class="border-2 border-dashed border-gray-600 p-4 mt-5 w-full text-white bg-gray-800 rounded-lg hover:border-blue-500 focus:outline-none"
                      :class="{ 'border-blue-500': fileName, 'border-red-500': hasError }">

                      <input @change="fileName = $event.target.files[0].name; $refs.newResumeRadio.checked = true"
                        type="file" name="resume_file" id="new_resume_file" class="hidden" accept=".pdf" />

                      <div class="text-center">
                        <template x-if="fileName == ''">
                          <div>
                            <p class="text-gray-400">No file selected.</p>
                            <p class="text-gray-400 text-sm mt-1">Click to upload file (Max 5MB).</p>
                          </div>
                        </template>

                        <template x-if="fileName != ''">
                          <div>
                            <p x-text="fileName" class="text-blue-400"></p>
                            <p class="text-gray-400 text-sm mt-1">Click to change File.</p>
                          </div>
                        </template>
                      </div>

                    </div>
                  </label>
                </div>
              </div>


              @error('resume_file')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

          </div>
          {{-- submit btn --}}
          <div class="mt-6 flex justify-end">
            <x-primary-button type="submit">
              Submit Application
            </x-primary-button>
          </div>

        </form>

      </div>
</x-app-layout>
