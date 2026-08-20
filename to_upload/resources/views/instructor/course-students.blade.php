@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-6">
        <a href="{{ route('instructor.manage.courses') }}" class="text-primary-jlm hover:text-secondary-jlm font-semibold transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Manage Courses
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Course Students</h1>
        <p class="text-gray-500 mt-1"><span class="font-semibold text-primary-jlm">{{ $course->title }}</span> — {{ $students->count() }} enrolled student{{ $students->count() !== 1 ? 's' : '' }}</p>
    </div>

    @if($students->isEmpty())
        <div class="bg-white rounded-2xl shadow-md p-16 text-center">
            <div class="text-6xl text-gray-200 mb-4"><i class="fas fa-users"></i></div>
            <h2 class="text-xl font-bold text-gray-700 mb-2">No students enrolled yet.</h2>
            <p class="text-gray-400">Share your course to start enrolling students.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <div class="hidden sm:grid grid-cols-12 gap-4 px-6 py-3 bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <div class="col-span-4">Student</div>
                <div class="col-span-4">Email</div>
                <div class="col-span-4">Progress</div>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach ($students as $student)
                    @php $prog = $student->pivot->progress_percentage ?? 0; @endphp
                    <div class="px-6 py-4 hover:bg-gray-50 transition flex flex-wrap sm:grid sm:grid-cols-12 gap-4 items-center">
                        <div class="col-span-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-jlm/10 flex items-center justify-center font-bold text-primary-jlm flex-shrink-0">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <span class="font-semibold text-gray-800">{{ $student->name }}</span>
                        </div>
                        <div class="col-span-4 text-sm text-gray-500">{{ $student->email }}</div>
                        <div class="col-span-4 flex items-center gap-3">
                            <div class="flex-grow">
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $prog >= 100 ? 'bg-green-500' : 'bg-gradient-to-r from-primary-jlm to-secondary-jlm' }}"
                                         style="width: {{ $prog }}%"></div>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-700 w-12 text-right flex-shrink-0">{{ $prog }}%</span>
                            @if($prog >= 100)
                                <span class="flex-shrink-0 bg-green-100 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">Done</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
