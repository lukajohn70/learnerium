@extends('layouts.app')

@section('title'){{ $lesson->title }} — {{ $course->title }}@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Top breadcrumb bar -->
    <div class="mb-6 flex flex-wrap items-center gap-3">
        <a href="{{ route('course.detail', $course->slug) }}" class="text-sm text-primary-jlm hover:text-secondary-jlm font-semibold transition flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> {{ $course->title }}
        </a>
        @if($progress)
            <div class="flex items-center gap-2 ml-auto">
                <span class="text-xs text-gray-400 font-medium">Overall Progress</span>
                <div class="w-32 bg-gray-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-primary-jlm to-secondary-jlm h-2 rounded-full" style="width: {{ $progress->progress_percentage }}%"></div>
                </div>
                <span class="text-xs font-bold text-primary-jlm">{{ $progress->progress_percentage }}%</span>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">

                <!-- Smart Video/Media Player -->
                @if($lesson->video_url)
                    @php
                        $embedUrl = null;
                        $isIframe = false;
                        $rawUrl = trim($lesson->video_url);

                        if (preg_match('/drive\.google\.com\/(?:file\/d\/|open\?id=)([^\/\?\&]+)/i', $rawUrl, $matches)) {
                            $embedUrl = 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
                            $isIframe = true;
                        } elseif (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $rawUrl, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
                            $isIframe = true;
                        } elseif (preg_match('/vimeo\.com\/(\d+)/i', $rawUrl, $matches)) {
                            $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
                            $isIframe = true;
                        } elseif (strpos($rawUrl, 'youtube.com/embed/') !== false) {
                            $embedUrl = $rawUrl;
                            $isIframe = true;
                        } else {
                            $embedUrl = $rawUrl;
                            $isIframe = false;
                        }
                    @endphp

                    <div class="bg-black">
                        @if($isIframe)
                            <div class="aspect-video w-full">
                                <iframe class="w-full h-full border-0" src="{{ $embedUrl }}"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        @else
                            <video class="w-full max-h-[480px]" controls>
                                <source src="{{ $embedUrl }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                    </div>
                @endif

                <!-- Lesson Content -->
                <div class="p-6 md:p-8">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">{{ $lesson->title }}</h1>
                    @if($lesson->description)
                        <p class="text-gray-500 mb-6 text-base leading-relaxed">{{ $lesson->description }}</p>
                    @endif

                    @if($lesson->content)
                        <div class="prose max-w-none mb-8 text-gray-700 leading-relaxed border-t border-gray-100 pt-6">
                            {!! $lesson->content !!}
                        </div>
                    @endif

                    <!-- Completion -->
                    @if(auth()->user()->id !== $course->instructor_id)
                        <div class="border-t border-gray-100 pt-6">
                            @if($progress && $progress->completed)
                                <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-5 py-4 text-green-800">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                    <div>
                                        <p class="font-bold">Lesson Completed!</p>
                                        <p class="text-sm text-green-600">{{ $progress->completed_at?->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('lesson.complete', [$course, $lesson]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-primary-jlm text-white px-8 py-3 rounded-xl font-bold hover:bg-primary-jlm-dark transition shadow-md flex items-center gap-2">
                                        <i class="fas fa-check"></i>Mark as Complete
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <div class="border-t border-gray-100 pt-5">
                            <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 text-blue-800">
                                <i class="fas fa-chalkboard-teacher text-blue-400 text-xl"></i>
                                <span class="font-medium">You are viewing this lesson as the course instructor.</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar: Lesson List -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-md p-5 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-list-ul text-primary-jlm"></i>Course Lessons
                </h3>
                <div class="space-y-1.5 max-h-[60vh] overflow-y-auto pr-1">
                    @forelse($lessons as $item)
                        <a href="{{ route('lesson.show', [$course, $item]) }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ $item->id === $lesson->id ? 'bg-primary-jlm text-white' : 'hover:bg-gray-50 text-gray-700' }}">
                            <span class="text-xs font-bold w-5 h-5 flex-shrink-0 rounded-full flex items-center justify-center {{ $item->id === $lesson->id ? 'bg-white/20' : 'bg-gray-100' }}">
                                {{ $item->order + 1 }}
                            </span>
                            <div class="flex-grow min-w-0">
                                <p class="text-xs font-semibold truncate">{{ $item->title }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-400 text-sm text-center py-4">No lessons yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
