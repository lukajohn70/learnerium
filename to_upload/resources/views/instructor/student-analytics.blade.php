@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Student Analytics</h1>
        <p class="text-gray-500 mt-1">Overview of student engagement and performance across your courses.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-16 text-center">
        <div class="text-6xl text-primary-jlm/20 mb-4"><i class="fas fa-chart-bar"></i></div>
        <h2 class="text-xl font-bold text-gray-700 mb-2">Detailed Analytics Coming Soon</h2>
        <p class="text-gray-400 max-w-md mx-auto mb-6">
            We're building advanced analytics tools for instructors. For now, you can view per-quiz analytics from the quiz management pages.
        </p>
        <div class="flex justify-center gap-3">
            <a href="{{ route('instructor.dashboard') }}" class="bg-primary-jlm text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-jlm-dark transition shadow text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
            <a href="{{ route('instructor.manage.courses') }}" class="border border-gray-200 text-gray-600 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-50 transition text-sm">
                Manage Courses
            </a>
        </div>
    </div>
</div>
@endsection
