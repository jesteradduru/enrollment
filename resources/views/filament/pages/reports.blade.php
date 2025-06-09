<x-filament-panels::page>
    <form wire:submit.prevent="generateReport" class="space-y-4 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="school_year_id" class="block font-medium">School Year</label>
                <select required wire:model="school_year_id" id="school_year_id" class="w-full rounded border-gray-300">
                    <option value="">Select School Year</option>
                    @foreach($this->schoolYears as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            @if(auth()->user()->role == 'admin')
            {{-- <div>
                <label for="faculty_id" class="block font-medium">Faculty</label>
                <select wire:model="faculty_id" id="faculty_id" class="w-full rounded border-gray-300">
                    <option value="">All</option>
                    @foreach($this->faculties as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div> --}}
            <div>
                <label for="grade_id" class="block font-medium">Grade</label>
                <select wire:model="grade_id" id="grade_id" class="w-full rounded border-gray-300">
                    <option value="">All</option>
                    @foreach($this->grades as $id => $level)
                        <option value="{{ $id }}">{{ $level }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            
        </div>
        <button type="submit" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded">Generate Report</button>
    </form>

    @if($showReport)
        <div class="mb-4 p-4 border rounded bg-white">
            <div class="text-center mb-2">
                <div class="text-lg font-bold">ABARIONGAN UNEG ELEMENTARY SCHOOL</div>
                <div class="font-semibold">LIST OF ENROLLEES</div>
                <div class="font-semibold">{{ $this->schoolYears[$school_year_id] ?? '' }}</div>
            </div>
            <table class="min-w-full border mt-4">
                <thead>
                    <tr>
                        <th class="border px-2 py-1">#</th>
                        <th class="border px-2 py-1">Learner's ID</th>
                        <th class="border px-2 py-1">Code</th>
                        <th class="border px-2 py-1">Student Name</th>
                        <th class="border px-2 py-1">Level</th>
                        <th class="border px-2 py-1">Grade</th>
                        <th class="border px-2 py-1">Section</th>
                        <th class="border px-2 py-1">Sex</th>
                        <th class="border px-2 py-1">Birthdate <br> <span style="font-weight: normal; font-size: 12px">mm-dd-yyyy</span></th>
                        <th class="border px-2 py-1">Age</th>
                        <th class="border px-2 py-1">Date enrolled<br> <span style="font-weight: normal; font-size: 12px">mm-dd-yyyy</span></th>
                        <th class="border px-2 py-1">Type</th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollees as $i => $student)
                        <tr>
                            <td class="border px-2 py-1">{{ $i + 1 }}</td>
                            <td class="border px-2 py-1">{{ $student->school_id }}</td>
                            <td class="border px-2 py-1">{{ $student->level }}</td>
                            <td class="border px-2 py-1">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name}} {{ $student->extension_name }} </td>
                            <td class="border px-2 py-1">{{ $student->level_type }}</td>
                            <td class="border px-2 py-1">{{ $student->grade }}</td>
                            <td class="border px-2 py-1">{{ $student->classroom_name }}</td>
                            <td class="border px-2 py-1">{{ Str::ucfirst($student->gender[0]) }}</td>
                            <td class="border px-2 py-1">{{  $this->getDateOfBirth($student->date_of_birth) }}</td>
                            <td class="border px-2 py-1">{{ $this->getAge($student->date_of_birth) }}</td>
                            <td class="border px-2 py-1">{{ $this->getDateOfBirth($student->enrolled_at) }}</td>
                            <td class="border px-2 py-1">{{ $student->type }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-2">No enrollees found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <br>
            <button wire:click="exportPdf" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded">Export PDF</button>
        </div>
    @endif
</x-filament-panels::page>
