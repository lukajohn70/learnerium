@extends('layouts.app')

@section('title'){{ $course->title }} — Learnerium@endsection

@section('content')

{{-- ===== HERO BANNER ===== --}}
<section class="bg-gradient-to-br from-gray-900 via-primary-jlm to-gray-900 text-white py-14 px-4">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center lg:items-start gap-10">

        {{-- Thumbnail --}}
        <div class="lg:w-5/12 w-full flex-shrink-0">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}"
                     alt="{{ $course->title }}"
                     class="rounded-2xl shadow-2xl w-full object-cover aspect-video">
            @else
                <div class="rounded-2xl bg-white/10 border border-white/20 w-full aspect-video flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-6xl text-white/30"></i>
                </div>
            @endif
        </div>

        {{-- Course Meta --}}
        <div class="lg:w-7/12 w-full">

            {{-- Breadcrumb --}}
            <p class="text-sm text-white/60 mb-3">
                <a href="{{ route('courses') }}" class="hover:text-white transition">All Courses</a>
                <span class="mx-2">›</span>
                <span class="text-white/80">{{ $course->title }}</span>
            </p>

            <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4">{{ $course->title }}</h1>

            @if($course->description)
                <p class="text-lg text-white/80 mb-6 leading-relaxed">{{ $course->description }}</p>
            @endif

            {{-- Instructor --}}
            <div class="flex items-center gap-3 mb-5">
                <img src="https://placehold.co/40x40/e4306d/ffffff?text={{ urlencode(substr($course->instructor?->name ?? 'IN', 0, 2)) }}"
                     alt="{{ $course->instructor?->name }}"
                     class="w-10 h-10 rounded-full border-2 border-secondary-jlm">
                <div>
                    <p class="text-xs text-white/50 uppercase tracking-wide font-semibold">Instructor</p>
                    <p class="text-sm font-bold text-white">{{ $course->instructor?->name ?? 'Learnerium Instructor' }}</p>
                </div>
            </div>

            {{-- Badges row --}}
            <div class="flex flex-wrap gap-3 mb-6 text-sm">
                @if($course->level)
                    <span class="bg-white/10 border border-white/20 text-white px-3 py-1 rounded-full font-medium capitalize">
                        <i class="fas fa-signal mr-1"></i> {{ ucfirst($course->level) }}
                    </span>
                @endif
                @if($course->duration_minutes)
                    <span class="bg-white/10 border border-white/20 text-white px-3 py-1 rounded-full font-medium">
                        <i class="fas fa-clock mr-1"></i> {{ floor($course->duration_minutes / 60) }}h {{ $course->duration_minutes % 60 }}m
                    </span>
                @endif
                @php $totalLessons = $course->lessons->count(); @endphp
                @if($totalLessons > 0)
                    <span class="bg-white/10 border border-white/20 text-white px-3 py-1 rounded-full font-medium">
                        <i class="fas fa-book-open mr-1"></i> {{ $totalLessons }} {{ Str::plural('Lesson', $totalLessons) }}
                    </span>
                @endif
                @php $totalStudents = $course->enrollments->count(); @endphp
                @if($totalStudents > 0)
                    <span class="bg-white/10 border border-white/20 text-white px-3 py-1 rounded-full font-medium">
                        <i class="fas fa-users mr-1"></i> {{ number_format($totalStudents) }} enrolled
                    </span>
                @endif
                @if($course->published_at)
                    <span class="bg-white/10 border border-white/20 text-white px-3 py-1 rounded-full font-medium">
                        <i class="fas fa-calendar mr-1"></i> Updated {{ $course->published_at->format('M Y') }}
                    </span>
                @endif
            </div>

            {{-- ===== PRICING + ENROLL ===== --}}
            <div class="flex flex-wrap items-center gap-4">

                {{-- Price display block --}}
                <div>
                    @if($course->price > 0)
                        {{-- Primary: Naira --}}
                        <div class="flex items-baseline gap-3 flex-wrap">
                            <span id="priceDisplay" class="text-4xl font-extrabold text-accent-jlm">
                                ₦{{ number_format($course->price, 0) }}
                            </span>
                        </div>

                        {{-- Currency Switcher --}}
                        <div class="mt-2 flex items-center gap-2 flex-wrap">
                            <span class="text-xs text-white/50">View in:</span>
                            @php
                                // Approximate live rates vs 1 NGN (baked-in fallback rates)
                                // NGN = base. All rates are per 1 NGN.
                                $rates = [
                                    'NGN' => ['rate' => 1,           'symbol' => '₦',  'name' => 'NGN'],
                                    'GHS' => ['rate' => 0.00495,     'symbol' => 'GH₵','name' => 'GHS'],
                                    'KES' => ['rate' => 0.105,       'symbol' => 'KSh','name' => 'KES'],
                                    'ZAR' => ['rate' => 0.015,       'symbol' => 'R',  'name' => 'ZAR'],
                                    'EGP' => ['rate' => 0.05,        'symbol' => 'E£', 'name' => 'EGP'],
                                    'TZS' => ['rate' => 2.12,        'symbol' => 'TSh','name' => 'TZS'],
                                    'XOF' => ['rate' => 0.49,        'symbol' => 'F',  'name' => 'XOF'],
                                    'USD' => ['rate' => 0.000625,    'symbol' => '$',  'name' => 'USD'],
                                    'GBP' => ['rate' => 0.000495,    'symbol' => '£',  'name' => 'GBP'],
                                    'EUR' => ['rate' => 0.00057,     'symbol' => '€',  'name' => 'EUR'],
                                    'CAD' => ['rate' => 0.00085,     'symbol' => 'C$', 'name' => 'CAD'],
                                    'AED' => ['rate' => 0.0023,      'symbol' => 'د.إ','name' => 'AED'],
                                ];
                            @endphp
                            @foreach($rates as $code => $info)
                                <button
                                    class="currency-btn text-xs px-2 py-0.5 rounded-full border font-semibold transition
                                           {{ $code === 'NGN' ? 'bg-accent-jlm text-primary-jlm border-accent-jlm' : 'border-white/30 text-white/70 hover:border-white hover:text-white' }}"
                                    data-rate="{{ $info['rate'] }}"
                                    data-symbol="{{ $info['symbol'] }}"
                                    data-base="{{ $course->price }}"
                                    data-code="{{ $code }}">
                                    {{ $code }}
                                </button>
                            @endforeach
                        </div>
                        <p class="text-xs text-white/40 mt-1">* Conversion rates are approximate</p>
                    @else
                        <span class="text-4xl font-extrabold text-green-400">Free</span>
                    @endif
                </div>

                {{-- Enroll / Enrolled button --}}
                <div class="ml-0 lg:ml-4">
                    @auth
                        @php $isEnrolled = auth()->user()->coursesEnrolled->contains($course->id); @endphp
                        @if($isEnrolled)
                            <span class="inline-flex items-center gap-2 bg-green-500 text-white px-7 py-3 rounded-full text-lg font-bold shadow-lg">
                                <i class="fas fa-check-circle"></i> Enrolled
                            </span>
                        @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        class="bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-8 py-3.5 rounded-full text-lg font-bold transition shadow-xl hover:shadow-secondary-jlm/40 hover:scale-105 transform duration-200">
                                    Enrol Now
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login.student') }}"
                           class="inline-block bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-8 py-3.5 rounded-full text-lg font-bold transition shadow-xl hover:scale-105 transform duration-200">
                            Login to Enrol
                        </a>
                    @endauth
                </div>
            </div>

            @if(session('status'))
                <div class="mt-4 flex items-center gap-2 bg-green-500/20 border border-green-400/40 text-green-300 px-4 py-2 rounded-xl text-sm">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ===== MAIN CONTENT AREA ===== --}}
<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-10">

        {{-- LEFT: Main info --}}
        <div class="lg:w-2/3 w-full space-y-8">

            {{-- Course Curriculum --}}
            @if($course->lessons->count() > 0)
            <section class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                    <i class="fas fa-list-ul text-primary-jlm"></i> Course Content
                </h2>

                <p class="text-gray-500 text-sm mb-5">
                    <span class="font-semibold text-gray-700">{{ $course->lessons->count() }}</span> {{ Str::plural('lesson', $course->lessons->count()) }}
                    @if($course->duration_minutes)
                        · <span class="font-semibold text-gray-700">{{ floor($course->duration_minutes / 60) }}h {{ $course->duration_minutes % 60 }}m</span> total length
                    @endif
                </p>

                <div class="space-y-2" id="curriculum">
                    @foreach($course->lessons as $lesson)
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <button onclick="toggleLesson('lesson-{{ $lesson->id }}')"
                                class="flex items-center justify-between w-full text-left px-5 py-3.5 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-primary-jlm/10 text-primary-jlm text-xs font-bold flex items-center justify-center flex-shrink-0">
                                    {{ $loop->iteration }}
                                </span>
                                <span class="font-semibold text-gray-800 text-sm">{{ $lesson->title }}</span>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0 ml-4">
                                @if($lesson->video_url)
                                    <span class="text-xs text-blue-500 font-medium"><i class="fas fa-play-circle mr-1"></i>Video</span>
                                @endif
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" id="icon-lesson-{{ $lesson->id }}"></i>
                            </div>
                        </button>
                        <div id="lesson-{{ $lesson->id }}" class="hidden bg-gray-50 px-5 py-3 border-t border-gray-100">
                            @if($lesson->description)
                                <p class="text-sm text-gray-600 mb-2">{{ $lesson->description }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic">No description provided.</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- About the Instructor --}}
            <section class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6 flex items-center gap-3">
                    <i class="fas fa-chalkboard-teacher text-primary-jlm"></i> About the Instructor
                </h2>
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <img src="https://placehold.co/100x100/1b2299/f7de7a?text={{ urlencode(substr($course->instructor?->name ?? 'IN', 0, 2)) }}"
                         alt="{{ $course->instructor?->name }}"
                         class="w-24 h-24 rounded-full border-4 border-primary-jlm/20 shadow-md flex-shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $course->instructor?->name ?? 'Learnerium Instructor' }}</h3>
                        <p class="text-secondary-jlm font-semibold text-sm mb-3">Course Instructor</p>
                        @php $instructorCourseCount = $course->instructor ? $course->instructor->coursesTaught()->count() : 0; @endphp
                        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                            @if($instructorCourseCount > 0)
                                <span><i class="fas fa-book mr-1 text-primary-jlm"></i> {{ $instructorCourseCount }} {{ Str::plural('Course', $instructorCourseCount) }}</span>
                            @endif
                            @php $instructorStudents = $course->instructor ? \App\Models\Enrollment::whereIn('course_id', $course->instructor->coursesTaught()->pluck('id'))->count() : 0; @endphp
                            @if($instructorStudents > 0)
                                <span><i class="fas fa-users mr-1 text-primary-jlm"></i> {{ number_format($instructorStudents) }} Students</span>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

        </div>

        {{-- RIGHT: Sticky sidebar --}}
        <aside class="lg:w-1/3 w-full">
            <div class="bg-white rounded-2xl shadow-xl sticky top-24 border border-gray-100 overflow-hidden">

                {{-- Sidebar price header --}}
                <div class="bg-gradient-to-r from-primary-jlm to-primary-jlm-dark px-6 py-5 text-white">
                    @if($course->price > 0)
                        <p class="text-3xl font-extrabold" id="sidebarPrice">₦{{ number_format($course->price, 0) }}</p>
                        <p class="text-xs text-white/60 mt-0.5">Displayed in Naira (NGN)</p>
                    @else
                        <p class="text-3xl font-extrabold text-green-300">Free</p>
                    @endif
                </div>

                <div class="p-6">
                    {{-- Enrol button --}}
                    @auth
                        @php $isEnrolled = auth()->user()->coursesEnrolled->contains($course->id); @endphp
                        @if($isEnrolled)
                            <a href="{{ route('lesson.show', [$course, $course->lessons->first()]) }}"
                               class="block w-full text-center bg-green-500 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-green-600 transition shadow-md mb-4">
                                <i class="fas fa-play-circle mr-2"></i>Continue Learning
                            </a>
                        @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-secondary-jlm text-white px-6 py-3.5 rounded-xl font-bold hover:bg-secondary-jlm/90 transition shadow-md mb-4">
                                    Enrol Now
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login.student') }}"
                           class="block w-full text-center bg-secondary-jlm text-white px-6 py-3.5 rounded-xl font-bold hover:bg-secondary-jlm/90 transition shadow-md mb-4">
                            Login to Enrol
                        </a>
                    @endauth

                    {{-- Course includes --}}
                    <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wide mb-3">This course includes:</h3>
                    <ul class="space-y-2.5 text-sm text-gray-600">
                        @if($course->lessons->count() > 0)
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-book-open text-primary-jlm w-4 text-center"></i>
                                {{ $course->lessons->count() }} {{ Str::plural('lesson', $course->lessons->count()) }}
                            </li>
                        @endif
                        @if($course->duration_minutes)
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-clock text-primary-jlm w-4 text-center"></i>
                                {{ floor($course->duration_minutes / 60) }}h {{ $course->duration_minutes % 60 }}m of content
                            </li>
                        @endif
                        @if($course->level)
                            <li class="flex items-center gap-2.5">
                                <i class="fas fa-signal text-primary-jlm w-4 text-center"></i>
                                {{ ucfirst($course->level) }} level
                            </li>
                        @endif
                        <li class="flex items-center gap-2.5">
                            <i class="fas fa-infinity text-primary-jlm w-4 text-center"></i>
                            Lifetime access
                        </li>
                        <li class="flex items-center gap-2.5">
                            <i class="fas fa-certificate text-primary-jlm w-4 text-center"></i>
                            Certificate of completion
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
    </div>
</div>

{{-- ===== CURRENCY SWITCHER SCRIPT ===== --}}
@if($course->price > 0)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const btns = document.querySelectorAll('.currency-btn');
    const priceDisplay   = document.getElementById('priceDisplay');
    const sidebarPrice   = document.getElementById('sidebarPrice');

    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            const rate   = parseFloat(btn.dataset.rate);
            const symbol = btn.dataset.symbol;
            const base   = parseFloat(btn.dataset.base);
            const code   = btn.dataset.code;
            const converted = base * rate;

            // Format: show 2 decimal for small values, 0 for large
            const formatted = converted < 100
                ? converted.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})
                : Math.round(converted).toLocaleString();

            if (priceDisplay)  priceDisplay.textContent  = symbol + formatted;
            if (sidebarPrice)  sidebarPrice.textContent  = symbol + formatted;

            // Highlight active button
            btns.forEach(b => {
                b.classList.remove('bg-accent-jlm', 'text-primary-jlm', 'border-accent-jlm');
                b.classList.add('border-white/30', 'text-white/70');
            });
            btn.classList.add('bg-accent-jlm', 'text-primary-jlm', 'border-accent-jlm');
            btn.classList.remove('border-white/30', 'text-white/70');
        });
    });
});
</script>
@endif

{{-- ===== LESSON ACCORDION SCRIPT ===== --}}
<script>
function toggleLesson(id) {
    const el   = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    if (!el) return;
    el.classList.toggle('hidden');
    if (icon) icon.classList.toggle('rotate-180');
}
</script>

@endsection
