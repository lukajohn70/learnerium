@extends('layouts.app')
@section('title', $course->title . ' — Learnerium')

@section('content')

{{-- ===== HERO SECTION (dark, like old design) ===== --}}
<section class="bg-gray-900 text-white py-14 px-4">
    <div class="container mx-auto flex flex-col lg:flex-row items-center lg:items-start gap-10">

        {{-- Thumbnail left --}}
        <div class="lg:w-5/12 w-full flex-shrink-0">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}"
                     alt="{{ $course->title }}"
                     class="rounded-xl shadow-2xl w-full object-cover aspect-video">
            @else
                <div class="rounded-xl bg-primary-jlm/60 border border-white/10 w-full aspect-video flex items-center justify-center shadow-2xl">
                    <div class="text-center">
                        <i class="fas fa-graduation-cap text-6xl text-accent-jlm mb-3"></i>
                        <p class="text-white/50 text-sm">{{ $course->title }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Info right --}}
        <div class="lg:w-7/12 w-full">
            @if($course->category)
                <span class="inline-block bg-accent-jlm text-primary-jlm font-black text-xs uppercase tracking-widest px-3.5 py-1 rounded-full mb-3 shadow-sm">
                    {{ $course->category }}
                </span>
            @endif

            <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4">{{ $course->title }}</h1>

            @if($course->description)
                <p class="text-lg text-gray-300 mb-5 leading-relaxed">{{ $course->description }}</p>
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
            <div class="flex items-center gap-2.5 mb-5">
                <img src="https://placehold.co/36x36/e4306d/ffffff?text={{ urlencode(substr($course->instructor?->name ?? 'IN', 0, 2)) }}"
                     alt="{{ $course->instructor?->name }}"
                     class="w-9 h-9 rounded-full border-2 border-secondary-jlm">
                <span class="text-sm text-gray-400">Created by
                    <span class="text-secondary-jlm font-semibold">{{ $course->instructor?->name ?? 'Learnerium Instructor' }}</span>
                </span>
            </div>

            @if($course->published_at)
                <p class="text-gray-500 text-xs mb-5">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Last updated: {{ $course->published_at->format('M Y') }}
                </p>
            @endif

            {{-- ===== PRICE + CURRENCY ===== --}}
            <div class="mb-6">
                @if($course->price > 0)
                    <div class="flex flex-wrap items-end gap-4">
                        {{-- Price display --}}
                        <span id="heroPriceDisplay" class="text-4xl font-extrabold text-accent-jlm">
                            ₦{{ number_format($course->price, 0) }}
                        </span>

                        {{-- Currency dropdown --}}
                        <div class="relative" id="currencyDropdownWrap">
                            <button id="currencyDropdownBtn"
                                    class="flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                                <i class="fas fa-globe-africa text-accent-jlm"></i>
                                <span id="currencyLabel">NGN — Nigerian Naira</span>
                                <i class="fas fa-chevron-down text-xs text-white/60" id="currencyChevron"></i>
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
                    </div>
                @else
                    <span class="text-4xl font-extrabold text-green-400">Free</span>
                @endif
            </div>

            {{-- Enrol button --}}
            @auth
                @php
                    $isEnrolled = auth()->user()->enrolledIn($course->id);
                    $isPaid     = (float) $course->price > 0;
                @endphp
                @if($isEnrolled)
                    @if($course->lessons->count() > 0)
                        <a href="{{ route('lesson.show', [$course, $course->lessons->first()]) }}"
                           class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-8 py-3.5 rounded-full text-lg font-bold transition shadow-xl">
                            <i class="fas fa-play-circle"></i> Continue Learning
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 bg-green-500 text-white px-8 py-3.5 rounded-full text-lg font-bold shadow-xl">
                            <i class="fas fa-check-circle"></i> Enrolled
                        </span>
                    @endif
                @elseif($isPaid)
                    <a href="{{ route('courses.checkout', $course) }}"
                       class="inline-flex items-center gap-2 bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-8 py-3.5 rounded-full text-lg font-bold transition shadow-xl hover:shadow-secondary-jlm/30 hover:scale-105 transform duration-200">
                        <i class="fas fa-lock"></i> Buy Now &mdash; ₦{{ number_format($course->price, 2) }}
                    </a>
                @else
                    <form action="{{ route('courses.enroll', $course) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-8 py-3.5 rounded-full text-lg font-bold transition shadow-xl hover:shadow-secondary-jlm/30 hover:scale-105 transform duration-200">
                            Enrol Free
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login.student') }}"
                   class="inline-flex items-center gap-2 bg-secondary-jlm hover:bg-secondary-jlm/90 text-white px-8 py-3.5 rounded-full text-lg font-bold transition shadow-xl hover:scale-105 transform duration-200">
                    Login to Enrol
                </a>
            @endauth

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

            {{-- What you'll learn (lesson titles as learning points) --}}
            @if($course->lessons->count() > 0)
            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-extrabold text-primary-jlm mb-6">What you'll learn</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-gray-700">
                    @foreach($course->lessons->take(8) as $lesson)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-secondary-jlm mt-0.5 flex-shrink-0"></i>
                            <span class="text-sm leading-relaxed">{{ $lesson->title }}</span>
                        </div>
                    @endforeach
                    @if($course->lessons->count() > 8)
                        <div class="flex items-start gap-3 md:col-span-2">
                            <i class="fas fa-plus-circle text-gray-400 mt-0.5 flex-shrink-0"></i>
                            <span class="text-sm text-gray-500">And {{ $course->lessons->count() - 8 }} more lessons...</span>
                        </div>
                    @endif
                </div>
            </section>
            @endif

            {{-- Course Content Accordion --}}
            @if($course->modules->count() > 0 || $course->lessons->count() > 0)
            <section class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-2xl font-extrabold text-primary-jlm mb-2">Course Curriculum</h2>
                <p class="text-gray-500 text-sm mb-6">
                    @if($course->modules->count() > 0)
                        <span class="font-semibold text-gray-700">{{ $course->modules->count() }}</span> {{ Str::plural('module', $course->modules->count()) }} &nbsp;·&nbsp;
                    @endif
                    <span class="font-semibold text-gray-700">{{ $course->lessons->count() }}</span> {{ Str::plural('lesson', $course->lessons->count()) }}
                </p>

                <div class="space-y-4">
                    @if($course->modules->count() > 0)
                        @foreach($course->modules as $modIndex => $module)
                            <div class="border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-bold uppercase tracking-wider text-secondary-jlm">Module {{ $modIndex + 1 }}</span>
                                        <h3 class="text-base font-extrabold text-gray-900">{{ $module->title }}</h3>
                                        @if($module->description)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $module->description }}</p>
                                        @endif
                                    </div>
                                    <span class="text-xs font-semibold bg-white border border-gray-200 text-gray-600 px-3 py-1 rounded-full">
                                        {{ $module->lessons->count() }} {{ Str::plural('lesson', $module->lessons->count()) }}
                                    </span>
                                </div>
                                <div class="divide-y divide-gray-100 bg-white">
                                    @forelse($module->lessons as $lesson)
                                        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-gray-50/80 transition text-sm">
                                            <div class="flex items-center gap-3">
                                                <i class="fas fa-play-circle text-primary-jlm"></i>
                                                <span class="font-medium text-gray-800">{{ $lesson->title }}</span>
                                            </div>
                                            @if($lesson->is_free)
                                                <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-2.5 py-0.5 rounded-full">Free Preview</span>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="p-4 text-xs text-gray-400 italic">No lessons in this module yet.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    @else
                        @foreach($course->lessons as $lesson)
                            <div class="p-4 border border-gray-200 rounded-xl flex items-center justify-between text-sm">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-play-circle text-primary-jlm"></i>
                                    <span class="font-medium text-gray-800">{{ $lesson->title }}</span>
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
                    <img src="https://placehold.co/120x120/e4306d/ffffff?text={{ urlencode(substr($course->instructor?->name ?? 'IN', 0, 2)) }}"
                         alt="{{ $course->instructor?->name }}"
                         class="rounded-full w-28 h-28 object-cover shadow-lg flex-shrink-0">
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

        {{-- RIGHT SIDEBAR --}}
        <aside class="lg:w-1/3 w-full">
            <div class="bg-white rounded-2xl shadow-xl sticky top-24 border border-gray-100 overflow-hidden">

                {{-- Sidebar price --}}
                <div class="border-b border-gray-100 px-6 py-5 text-center">
                    @if($course->price > 0)
                        <p id="sidebarPrice" class="text-4xl font-extrabold text-primary-jlm mb-0.5">₦{{ number_format($course->price, 0) }}</p>
                        <p id="sidebarCurrencyLabel" class="text-xs text-gray-400">Displayed in Nigerian Naira (NGN)</p>
                    @else
                        <p class="text-4xl font-extrabold text-green-600">Free</p>
                    @endif
                </div>

                <div class="px-6 py-5 space-y-4">
                        {{-- CTA --}}
                    @auth
                        @php
                            $isEnrolled = auth()->user()->enrolledIn($course->id);
                            $isPaid     = (float) $course->price > 0;
                        @endphp
                        @if($isEnrolled)
                            @if($course->lessons->count() > 0)
                                <a href="{{ route('lesson.show', [$course, $course->lessons->first()]) }}"
                                   class="block w-full text-center bg-green-500 hover:bg-green-600 text-white px-6 py-3.5 rounded-xl font-bold transition shadow-md">
                                    <i class="fas fa-play-circle mr-2"></i>Continue Learning
                                </a>
                            @else
                                <div class="block w-full text-center bg-green-500 text-white px-6 py-3.5 rounded-xl font-bold">
                                    <i class="fas fa-check-circle mr-2"></i>You're Enrolled
                                </div>
                            @endif
                        @elseif($isPaid)
                            <a href="{{ route('courses.checkout', $course) }}"
                               class="block w-full text-center bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-6 py-3.5 rounded-xl font-extrabold transition shadow-md text-lg">
                                <i class="fas fa-lock mr-2"></i>Buy &mdash; ₦{{ number_format($course->price, 2) }}
                            </a>
                            <div class="flex items-center gap-2 mt-3">
                                <form action="{{ route('cart.store', $course) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary-jlm hover:bg-primary-jlm-dark text-white py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow">
                                        <i class="fas fa-shopping-cart"></i> Add to Cart
                                    </button>
                                </form>
                                <form action="{{ route('wishlist.toggle', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2.5 border border-pink-200 text-pink-600 hover:bg-pink-50 rounded-xl transition" title="Wishlist">
                                        <i class="fas fa-heart text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-6 py-3.5 rounded-xl font-extrabold transition shadow-md text-lg">
                                    Enrol Free
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login.student') }}"
                           class="block w-full text-center bg-accent-jlm hover:bg-yellow-400 text-primary-jlm px-6 py-3.5 rounded-xl font-extrabold transition shadow-md text-lg">
                            Login to Enrol
                        </a>
                    @endauth

                    {{-- Course Includes --}}
                    <div>
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

// --- Currency dropdown (sticky hover same approach as nav) ---
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
    document.addEventListener('click', e => { if (!wrap.contains(e.target)) { menu.classList.add('hidden'); } });

    // Currency selection
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

            // Update hero price
            const heroPrice = document.getElementById('heroPriceDisplay');
            if (heroPrice) heroPrice.textContent = symbol + formatted;

            // Update sidebar price + label
            const sidebarPrice = document.getElementById('sidebarPrice');
            const sidebarLabel = document.getElementById('sidebarCurrencyLabel');
            if (sidebarPrice) sidebarPrice.textContent = symbol + formatted;
            if (sidebarLabel) sidebarLabel.textContent = 'Displayed in ' + name + ' (' + code + ')';

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
