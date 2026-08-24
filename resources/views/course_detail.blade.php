@extends('layouts.app')
@section('title', $course->title . ' — Learnerium')
@section('meta_description', Str::limit(strip_tags($course->description ?? 'Enrol in ' . $course->title . ' on Learnerium. Expert-led online course with interactive lessons and a verified certificate upon completion.'), 155))
@section('og_type', 'article')
@section('og_title', $course->title . ' — Learnerium Course')
@section('og_description', Str::limit(strip_tags($course->description ?? 'Enrol in ' . $course->title . ' — an expert-led online course on Learnerium with interactive lessons and a verified certificate.'), 155))
@section('og_image', $course->thumbnailUrl())
@section('canonical', url('/courses/' . $course->slug))

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Course",
  "name": "{{ addslashes($course->title) }}",
  "description": "{{ addslashes(Str::limit(strip_tags($course->description ?? ''), 250)) }}",
  "url": "{{ url('/courses/' . $course->slug) }}",
  "image": "{{ $course->thumbnailUrl() }}",
  "provider": {
    "@type": "Organization",
    "name": "Learnerium",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('logo-only.png') }}"
  },
  "offers": {
    "@type": "Offer",
    "price": "{{ number_format($course->price, 2) }}",
    "priceCurrency": "NGN",
    "availability": "https://schema.org/InStock",
    "url": "{{ url('/courses/' . $course->slug) }}"
  },
  @if($course->instructor)
  "instructor": {
    "@type": "Person",
    "name": "{{ addslashes($course->instructor->name) }}"
  },
  @endif
  @if($course->level)
  "educationalLevel": "{{ ucfirst($course->level) }}",
  @endif
  @if($course->duration_minutes)
  "timeRequired": "PT{{ floor($course->duration_minutes / 60) }}H{{ $course->duration_minutes % 60 }}M",
  @endif
  "inLanguage": "en"
}
</script>
@endpush

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="bg-gray-900 text-white py-14 px-4">
    <div class="container mx-auto flex flex-col lg:flex-row items-center lg:items-start gap-10">

        {{-- Thumbnail left --}}
        <div class="lg:w-5/12 w-full flex-shrink-0">
            <img src="{{ $course->thumbnailUrl() }}"
                 alt="{{ $course->title }}"
                 class="rounded-xl shadow-2xl w-full object-cover aspect-video"
                 onerror="this.onerror=null;this.src='https://placehold.co/600x400/1b2299/f7de7a?text={{ urlencode($course->title) }}';">
        </div>

        {{-- Info right --}}
        <div class="lg:w-7/12 w-full">
            <div class="flex flex-wrap items-center gap-3 mb-3">
                @if($course->category)
                    <span class="inline-block bg-accent-jlm text-primary-jlm font-black text-xs uppercase tracking-widest px-3.5 py-1 rounded-full shadow-sm">
                        {{ $course->category }}
                    </span>
                @endif

                {{-- Currency converter dropdown in Hero --}}
                @if($course->price > 0)
                    <div class="relative inline-block text-left" id="currencyDropdownWrap">
                        <button id="currencyDropdownBtn"
                                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-full transition shadow-sm">
                            <i class="fas fa-globe-africa text-accent-jlm"></i>
                            <span id="currencyLabel">NGN — Nigerian Naira</span>
                            <i class="fas fa-chevron-down text-[10px] text-white/60" id="currencyChevron"></i>
                        </button>

                        <div id="currencyDropdownMenu"
                             class="hidden absolute left-0 top-full mt-2 w-64 bg-gray-800 border border-white/10 rounded-xl shadow-2xl z-30 py-2 max-h-72 overflow-y-auto">
                            @php
                                $currencies = [
                                    ['code' => 'NGN', 'symbol' => '₦',   'name' => 'Nigerian Naira',     'rate' => 1],
                                    ['code' => 'GHS', 'symbol' => 'GH₵', 'name' => 'Ghanaian Cedi',      'rate' => 0.00495],
                                    ['code' => 'KES', 'symbol' => 'KSh', 'name' => 'Kenyan Shilling',    'rate' => 0.105],
                                    ['code' => 'ZAR', 'symbol' => 'R',   'name' => 'South African Rand', 'rate' => 0.015],
                                    ['code' => 'EGP', 'symbol' => 'E£',  'name' => 'Egyptian Pound',     'rate' => 0.05],
                                    ['code' => 'TZS', 'symbol' => 'TSh', 'name' => 'Tanzanian Shilling', 'rate' => 2.12],
                                    ['code' => 'XOF', 'symbol' => 'CFA', 'name' => 'West African CFA',   'rate' => 0.49],
                                    ['code' => 'USD', 'symbol' => '$',   'name' => 'US Dollar',          'rate' => 0.000625],
                                    ['code' => 'GBP', 'symbol' => '£',   'name' => 'British Pound',      'rate' => 0.000495],
                                    ['code' => 'EUR', 'symbol' => '€',   'name' => 'Euro',               'rate' => 0.00057],
                                    ['code' => 'CAD', 'symbol' => 'C$',  'name' => 'Canadian Dollar',    'rate' => 0.00085],
                                    ['code' => 'AED', 'symbol' => 'د.إ', 'name' => 'UAE Dirham',         'rate' => 0.0023],
                                ];
                            @endphp
                            @foreach($currencies as $c)
                                <button class="currency-option w-full text-left px-4 py-2.5 text-sm hover:bg-white/10 transition flex items-center justify-between group
                                               {{ $c['code'] === 'NGN' ? 'text-accent-jlm font-semibold' : 'text-gray-300' }}"
                                        data-code="{{ $c['code'] }}"
                                        data-symbol="{{ $c['symbol'] }}"
                                        data-name="{{ $c['name'] }}"
                                        data-rate="{{ $c['rate'] }}"
                                        data-base="{{ $course->price }}">
                                    <span>
                                        <span class="font-mono text-xs mr-2 {{ $c['code'] === 'NGN' ? 'text-accent-jlm' : 'text-gray-500 group-hover:text-gray-300' }}">{{ $c['code'] }}</span>
                                        {{ $c['name'] }}
                                    </span>
                                    <span class="text-gray-500 text-xs">{{ $c['symbol'] }}</span>
                                </button>
                            @endforeach
                            <p class="text-xs text-gray-600 px-4 py-2 border-t border-white/5 mt-1">* Approximate rates</p>
                        </div>
                    </div>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4">{{ $course->title }}</h1>

            @if($course->description)
                <p class="text-base md:text-lg text-gray-300 mb-5 leading-relaxed">{{ $course->description }}</p>
            @endif

            {{-- Stats row --}}
            <div class="flex flex-wrap items-center gap-4 mb-4 text-sm">
                @php $totalStudents = $course->enrollments->count(); @endphp
                @if($totalStudents > 0)
                    <span class="flex items-center gap-1.5 text-gray-300">
                        <i class="fas fa-users text-accent-jlm"></i>
                        {{ number_format($totalStudents) }} student{{ $totalStudents !== 1 ? 's' : '' }} enrolled
                    </span>
                @endif
                @if($course->level)
                    <span class="flex items-center gap-1.5 text-gray-300">
                        <i class="fas fa-signal text-accent-jlm"></i>
                        {{ ucfirst($course->level) }}
                    </span>
                @endif
                @if($course->duration_minutes)
                    <span class="flex items-center gap-1.5 text-gray-300">
                        <i class="fas fa-clock text-accent-jlm"></i>
                        {{ floor($course->duration_minutes / 60) }}h {{ $course->duration_minutes % 60 }}m
                    </span>
                @endif
            </div>

            {{-- Instructor line --}}
            <div class="flex items-center gap-2.5 mb-2">
                <img src="{{ $course->instructor ? $course->instructor->avatarUrl() : 'https://ui-avatars.com/api/?name=Instructor' }}"
                     alt="{{ $course->instructor?->name }}"
                     class="w-9 h-9 rounded-full border-2 border-secondary-jlm object-cover">
                <span class="text-sm text-gray-400">Created by
                    <span class="text-secondary-jlm font-semibold">{{ $course->instructor?->name ?? 'Learnerium Instructor' }}</span>
                </span>
            </div>

            @if($course->published_at)
                <p class="text-gray-500 text-xs">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Last updated: {{ $course->published_at->format('M Y') }}
                </p>
            @endif

            @if(session('status'))
                <div class="mt-4 flex items-center gap-2 bg-green-500/20 border border-green-400/30 text-green-300 px-4 py-2.5 rounded-xl text-sm">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ===== MAIN BODY ===== --}}
<main class="py-14 px-4 bg-gray-jlm-light">
    <div class="container mx-auto flex flex-col lg:flex-row gap-12">

        {{-- LEFT COLUMN --}}
        <div class="lg:w-2/3 w-full space-y-10">

            {{-- What you'll learn --}}
            @if(!empty($course->what_you_will_learn) || $course->lessons->count() > 0)
            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-extrabold text-primary-jlm mb-6 flex items-center gap-2">
                    <i class="fas fa-check-circle text-secondary-jlm text-xl"></i> What you'll learn
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 text-gray-700">
                    @if(!empty($course->what_you_will_learn) && count($course->what_you_will_learn) > 0)
                        @foreach($course->what_you_will_learn as $outcome)
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-emerald-500 mt-1 flex-shrink-0 text-xs"></i>
                                <span class="text-sm leading-relaxed">{{ $outcome }}</span>
                            </div>
                        @endforeach
                    @else
                        @foreach($course->lessons->take(8) as $lesson)
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check text-secondary-jlm mt-1 flex-shrink-0 text-xs"></i>
                                <span class="text-sm leading-relaxed">{{ $lesson->title }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
            @endif

            {{-- Requirements / Prerequisites --}}
            @if(!empty($course->requirements) && count($course->requirements) > 0)
            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-extrabold text-primary-jlm mb-4 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-amber-500 text-xl"></i> Requirements & Prerequisites
                </h2>
                <p class="text-xs text-gray-500 mb-4">What you need before taking this course:</p>
                <ul class="space-y-2.5 text-gray-700">
                    @foreach($course->requirements as $req)
                        <li class="flex items-start gap-3 text-sm">
                            <i class="fas fa-arrow-right text-amber-500 text-xs mt-1 flex-shrink-0"></i>
                            <span class="leading-relaxed">{{ $req }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
            @endif


            {{-- Course Content Accordion --}}
            @if($course->lessons->count() > 0)
            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-extrabold text-primary-jlm mb-2">Course Content</h2>
                <p class="text-gray-500 text-sm mb-6">
                    {{ $course->lessons->count() }} {{ Str::plural('lesson', $course->lessons->count()) }}
                    @if($course->duration_minutes)
                        &bull; {{ floor($course->duration_minutes / 60) }}h {{ $course->duration_minutes % 60 }}m total duration
                    @endif
                </p>

                <div class="divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden">
                    @php $grouped = $course->lessons->groupBy('section_title'); @endphp
                    @if($grouped->count() > 1 || $grouped->keys()->first() !== '')
                        @foreach($grouped as $sectionTitle => $sectionLessons)
                            <div class="bg-gray-50">
                                <button onclick="toggleAccordion('sec-{{ $loop->index }}', 'icon-sec-{{ $loop->index }}')"
                                        class="w-full flex items-center justify-between p-4 text-left font-bold text-gray-800 hover:bg-gray-100 transition">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-layer-group text-primary-jlm text-xs"></i>
                                        {{ $sectionTitle ?: 'General Lessons' }}
                                    </span>
                                    <span class="flex items-center gap-3 text-xs text-gray-500">
                                        <span>{{ $sectionLessons->count() }} {{ Str::plural('lesson', $sectionLessons->count()) }}</span>
                                        <i id="icon-sec-{{ $loop->index }}" class="fas fa-chevron-down transition-transform duration-200"></i>
                                    </span>
                                </button>
                                <div id="sec-{{ $loop->index }}" class="bg-white divide-y divide-gray-50">
                                    @foreach($sectionLessons as $lesson)
                                        <div class="px-5 py-3 flex items-center justify-between text-sm flex-wrap gap-2">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-play-circle text-primary-jlm"></i>
                                                <span class="font-medium text-gray-800">{{ $lesson->title }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if(auth()->check() && $lesson->isDripLockedFor(auth()->user()))
                                                    <span class="text-[11px] bg-indigo-50 text-indigo-700 font-bold px-2.5 py-0.5 rounded-full border border-indigo-100 flex items-center gap-1">
                                                        <i class="fas fa-lock text-[10px]"></i> {{ $lesson->dripMessageFor(auth()->user()) }}
                                                    </span>
                                                @elseif($lesson->drip_date && $lesson->drip_date->isFuture())
                                                    <span class="text-[11px] bg-indigo-50 text-indigo-700 font-bold px-2.5 py-0.5 rounded-full border border-indigo-100 flex items-center gap-1">
                                                        <i class="fas fa-clock text-[10px]"></i> Releases {{ $lesson->drip_date->format('d M Y') }}
                                                    </span>
                                                @endif
                                                @if($lesson->duration_minutes)
                                                    <span class="text-xs text-gray-400">{{ $lesson->duration_minutes }} min</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($course->lessons as $lesson)
                            <div class="p-4 flex items-center justify-between text-sm bg-white hover:bg-gray-50 transition flex-wrap gap-2">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-play-circle text-primary-jlm"></i>
                                    <span class="font-medium text-gray-800">{{ $lesson->title }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if(auth()->check() && $lesson->isDripLockedFor(auth()->user()))
                                        <span class="text-[11px] bg-indigo-50 text-indigo-700 font-bold px-2.5 py-0.5 rounded-full border border-indigo-100 flex items-center gap-1">
                                            <i class="fas fa-lock text-[10px]"></i> {{ $lesson->dripMessageFor(auth()->user()) }}
                                        </span>
                                    @elseif($lesson->drip_date && $lesson->drip_date->isFuture())
                                        <span class="text-[11px] bg-indigo-50 text-indigo-700 font-bold px-2.5 py-0.5 rounded-full border border-indigo-100 flex items-center gap-1">
                                            <i class="fas fa-clock text-[10px]"></i> Releases {{ $lesson->drip_date->format('d M Y') }}
                                        </span>
                                    @endif
                                    @if($lesson->duration_minutes)
                                        <span class="text-xs text-gray-400">{{ $lesson->duration_minutes }} min</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </section>
            @endif


            {{-- About the Instructor --}}
            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-extrabold text-primary-jlm mb-6">About the Instructor</h2>
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                    <img src="{{ $course->instructor ? $course->instructor->avatarUrl() : 'https://ui-avatars.com/api/?name=Instructor' }}"
                         alt="{{ $course->instructor?->name }}"
                         class="rounded-full w-28 h-28 object-cover shadow-lg flex-shrink-0 border-4 border-secondary-jlm">
                    <div class="text-center sm:text-left">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $course->instructor?->name ?? 'Learnerium Instructor' }}</h3>
                        <p class="text-secondary-jlm font-semibold text-sm mb-3">Course Instructor</p>

                        <div class="flex flex-wrap items-center sm:justify-start justify-center gap-4 text-gray-500 text-sm mb-4">
                            @php
                                $instructorCourseCount   = $course->instructor?->coursesTaught()->count() ?? 0;
                                $instructorStudentCount  = $course->instructor
                                    ? \App\Models\Enrollment::whereIn('course_id', $course->instructor->coursesTaught()->pluck('id'))->count()
                                    : 0;
                            @endphp
                            @if($instructorCourseCount > 0)
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-book text-primary-jlm"></i>
                                    {{ $instructorCourseCount }} {{ Str::plural('Course', $instructorCourseCount) }}
                                </span>
                            @endif
                            @if($instructorStudentCount > 0)
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-users text-primary-jlm"></i>
                                    {{ number_format($instructorStudentCount) }} {{ Str::plural('Student', $instructorStudentCount) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

        </div>

        {{-- RIGHT SIDEBAR CARD (Single Authoritative Price + CTA) --}}
        <aside class="lg:w-1/3 w-full">
            <div class="bg-white rounded-2xl shadow-xl sticky top-24 border border-gray-100 overflow-hidden">

                {{-- Sidebar price --}}
                <div class="border-b border-gray-100 px-6 py-6 text-center bg-gray-50/50">
                    @if($course->price > 0)
                        <p id="sidebarPrice" class="text-4xl font-extrabold text-primary-jlm mb-1">₦{{ number_format($course->price, 2) }}</p>
                        <p id="sidebarCurrencyLabel" class="text-xs text-gray-500 font-medium">Displayed in Nigerian Naira (NGN)</p>
                    @else
                        <p class="text-4xl font-extrabold text-emerald-600">Free</p>
                    @endif
                </div>

                <div class="px-6 py-6 space-y-5">
                    {{-- CTA --}}
                    @auth
                        @php
                            $isEnrolled = auth()->user()->enrolledIn($course->id);
                            $isPaid     = (float) $course->price > 0;
                        @endphp
                        @if($isEnrolled)
                            @if($course->lessons->count() > 0)
                                <a href="{{ route('lesson.show', [$course, $course->lessons->first()]) }}"
                                   class="block w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-4 rounded-xl font-bold transition shadow-md">
                                    <i class="fas fa-play-circle mr-2"></i>Continue Learning
                                </a>
                            @else
                                <div class="block w-full text-center bg-emerald-500 text-white px-6 py-4 rounded-xl font-bold">
                                    <i class="fas fa-check-circle mr-2"></i>You're Enrolled
                                </div>
                            @endif
                        @elseif($isPaid)
                            <a href="{{ route('courses.checkout', $course) }}"
                               class="block w-full text-center bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-6 py-4 rounded-xl font-extrabold transition shadow-md text-base tracking-wide">
                                <i class="fas fa-lock mr-2"></i>Buy Now &mdash; <span class="buy-btn-price">₦{{ number_format($course->price, 2) }}</span>
                            </a>
                            <div class="flex items-center gap-2 mt-3">
                                <form action="{{ route('cart.store', $course) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary-jlm hover:bg-primary-jlm-dark text-white py-3 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                                <form action="{{ route('wishlist.toggle', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-3 border border-pink-200 text-pink-600 hover:bg-pink-50 rounded-xl transition" title="Wishlist">
                                        <i class="fas fa-heart text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-4 rounded-xl font-extrabold transition shadow-md text-base">
                                    Enrol Free
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login.student') }}"
                           class="block w-full text-center bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-6 py-4 rounded-xl font-extrabold transition shadow-md text-base">
                            Login to Enrol
                        </a>
                    @endauth

                    {{-- Course Includes Checklist --}}
                    <div class="pt-2 border-t border-gray-100">
                        <h3 class="font-bold text-gray-800 text-sm mb-3">Course Includes:</h3>
                        <ul class="space-y-3 text-gray-600 text-sm">
                            @if($course->lessons->count() > 0)
                                <li class="flex items-center gap-3">
                                    <i class="fas fa-book-open text-secondary-jlm w-5 text-center"></i>
                                    {{ $course->lessons->count() }} {{ Str::plural('lesson', $course->lessons->count()) }}
                                </li>
                            @endif
                            @if($course->duration_minutes)
                                <li class="flex items-center gap-3">
                                    <i class="fas fa-clock text-secondary-jlm w-5 text-center"></i>
                                    {{ floor($course->duration_minutes / 60) }}h {{ $course->duration_minutes % 60 }}m of on-demand content
                                </li>
                            @endif
                            @if($course->level)
                                <li class="flex items-center gap-3">
                                    <i class="fas fa-signal text-secondary-jlm w-5 text-center"></i>
                                    {{ ucfirst($course->level) }} level
                                </li>
                            @endif
                            <li class="flex items-center gap-3">
                                <i class="fas fa-infinity text-secondary-jlm w-5 text-center"></i>
                                Full lifetime access
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-certificate text-secondary-jlm w-5 text-center"></i>
                                Certificate of completion
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

{{-- ===== SCRIPTS ===== --}}
<script>
// --- Lesson accordion ---
function toggleAccordion(contentId, iconId) {
    const el   = document.getElementById(contentId);
    const icon = document.getElementById(iconId);
    if (!el) return;
    el.classList.toggle('hidden');
    if (icon) icon.classList.toggle('rotate-180');
}

// --- Currency dropdown script ---
(function() {
    const wrap  = document.getElementById('currencyDropdownWrap');
    const btn   = document.getElementById('currencyDropdownBtn');
    const menu  = document.getElementById('currencyDropdownMenu');
    if (!wrap || !btn || !menu) return;

    let timer = null;
    function open()  { clearTimeout(timer); menu.classList.remove('hidden'); document.getElementById('currencyChevron')?.classList.add('rotate-180'); }
    function close() { timer = setTimeout(() => { menu.classList.add('hidden'); document.getElementById('currencyChevron')?.classList.remove('rotate-180'); }, 150); }

    btn.addEventListener('mouseenter', open);
    btn.addEventListener('mouseleave', close);
    menu.addEventListener('mouseenter', open);
    menu.addEventListener('mouseleave', close);
    btn.addEventListener('click', (e) => { e.stopPropagation(); menu.classList.toggle('hidden'); });
    document.addEventListener('click', e => { if (!wrap.contains(e.target)) { menu.classList.add('hidden'); } });

    // Currency selection listener
    document.querySelectorAll('.currency-option').forEach(opt => {
        opt.addEventListener('click', () => {
            const rate   = parseFloat(opt.dataset.rate);
            const symbol = opt.dataset.symbol;
            const name   = opt.dataset.name;
            const code   = opt.dataset.code;
            const base   = parseFloat(opt.dataset.base);

            const converted = base * rate;
            const formatted = converted < 100
                ? converted.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                : Math.round(converted).toLocaleString();

            const fullPriceText = symbol + formatted;

            // Update sidebar price + currency label
            const sidebarPrice = document.getElementById('sidebarPrice');
            const sidebarLabel = document.getElementById('sidebarCurrencyLabel');
            if (sidebarPrice) sidebarPrice.textContent = fullPriceText;
            if (sidebarLabel) sidebarLabel.textContent = 'Displayed in ' + name + ' (' + code + ')';

            // Update all Buy button price tags dynamically
            document.querySelectorAll('.buy-btn-price').forEach(el => {
                el.textContent = fullPriceText;
            });

            // Update dropdown button label
            const label = document.getElementById('currencyLabel');
            if (label) label.textContent = code + ' — ' + name;

            // Highlight selected option
            document.querySelectorAll('.currency-option').forEach(o => {
                o.classList.remove('text-accent-jlm', 'font-semibold');
                o.classList.add('text-gray-300');
            });
            opt.classList.add('text-accent-jlm', 'font-semibold');
            opt.classList.remove('text-gray-300');

            // Close menu
            menu.classList.add('hidden');
        });
    });
})();
</script>

@endsection
