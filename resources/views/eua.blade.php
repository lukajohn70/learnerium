@extends('layouts.app')

@section('title', 'Terms of Service (End User Agreement) — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero -->
    <div class="bg-gradient-to-br from-secondary-jlm via-pink-700 to-primary-jlm text-white py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-5">
                <i class="fas fa-file-contract text-accent-jlm"></i> Legal Document
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">Terms of Service</h1>
            <p class="text-white/70 text-lg">End User Agreement (EUA)</p>
            <p class="text-white/60 text-sm mt-2">Effective Date: <strong class="text-white">January 1, 2025</strong> &nbsp;|&nbsp; Last Updated: <strong class="text-white">August 2025</strong></p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 py-16">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Important Notice -->
            <div class="bg-amber-50 border-b border-amber-100 px-8 py-5 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-sm text-amber-800 leading-relaxed">
                    <strong>Please read this End User Agreement carefully before using Learnerium.</strong>
                    By creating an account, enrolling in a course, or otherwise using our services, you agree to be bound by these Terms. If you do not agree, please do not use Learnerium.
                </p>
            </div>

            <!-- TOC -->
            <div class="bg-gray-50 border-b border-gray-100 px-8 py-6">
                <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-4"><i class="fas fa-list mr-2 text-primary-jlm"></i>Table of Contents</h2>
                <ol class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-sm text-primary-jlm">
                    @foreach([
                        'Acceptance of Terms',
                        'Eligibility',
                        'Account Registration',
                        'User Roles & Responsibilities',
                        'Course Enrollment & Payments',
                        'Refund Policy',
                        'Intellectual Property',
                        'Prohibited Conduct',
                        'Content Standards',
                        'Termination',
                        'Disclaimers & Limitation of Liability',
                        'Governing Law',
                        'Changes to Terms',
                        'Contact Information',
                    ] as $i => $item)
                    <li><a href="#eua-{{ $i+1 }}" class="hover:underline font-medium">{{ $i+1 }}. {{ $item }}</a></li>
                    @endforeach
                </ol>
            </div>

            <div class="px-8 py-10 space-y-10 text-gray-600 leading-relaxed text-sm">

                <!-- Intro -->
                <p>
                    This End User Agreement ("Agreement" or "Terms") is entered into between you ("User," "you," or "your") and
                    <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="text-primary-jlm font-semibold hover:underline">JLM</a>
                    ("Company," "we," "us," or "our"), operating the <strong>Learnerium</strong> online learning platform. These Terms govern your access to and use of our website, mobile applications, courses, content, and related services (collectively, the "Services").
                </p>

                @php
                $sections = [
                    ['Acceptance of Terms', 'fa-handshake', '
                        <p>By accessing or using Learnerium in any manner — including, without limitation, visiting or browsing the site, registering an account, purchasing or enrolling in a course, or submitting content — you agree to be bound by this Agreement and our <a href="'.route('privacy').'" class="text-primary-jlm hover:underline">Privacy Policy</a>, which is incorporated herein by reference.</p>
                        <p>If you are using Learnerium on behalf of an organisation, you represent that you have the authority to bind that organisation to these Terms.</p>
                    '],
                    ['Eligibility', 'fa-id-card', '
                        <p>You must be at least <strong>13 years of age</strong> to use Learnerium. By using our platform, you represent and warrant that:</p>
                        <ul>
                            <li>You are at least 13 years old.</li>
                            <li>If you are between 13 and 18 years old, your parent or legal guardian has reviewed and agreed to these Terms.</li>
                            <li>You have not been previously suspended or removed from our platform.</li>
                            <li>Your use of Learnerium does not violate any applicable law or regulation.</li>
                        </ul>
                    '],
                    ['Account Registration', 'fa-user-plus', '
                        <p>To access most features of Learnerium, you must create an account. You agree to:</p>
                        <ul>
                            <li>Provide accurate, current, and complete information during registration.</li>
                            <li>Keep your account information up-to-date.</li>
                            <li>Maintain the confidentiality of your password and not share it with any third party.</li>
                            <li>Notify us immediately at <a href="mailto:support@learnerium.com" class="text-primary-jlm hover:underline">support@learnerium.com</a> of any unauthorised use of your account.</li>
                            <li>Be solely responsible for all activity that occurs under your account.</li>
                        </ul>
                        <p>We reserve the right to refuse service, terminate accounts, or cancel orders at our sole discretion.</p>
                    '],
                    ['User Roles & Responsibilities', 'fa-users-cog', '
                        <p><strong>Students:</strong> As a student, you are granted a limited, non-exclusive, non-transferable licence to access and view course content for your personal, non-commercial educational purposes only.</p>
                        <p><strong>Instructors:</strong> Instructors must apply and receive approval before creating courses. By submitting course content, instructors grant Learnerium a non-exclusive, royalty-free licence to host, display, and distribute their content on the platform. Instructors are solely responsible for the accuracy, legality, and quality of their content.</p>
                        <p><strong>Administrators:</strong> Platform administrators manage users, courses, and content moderation in accordance with internal policies.</p>
                    '],
                    ['Course Enrollment & Payments', 'fa-credit-card', '
                        <p><strong>Free Courses:</strong> Certain courses are offered at no charge. These are subject to availability and may be modified or removed at any time.</p>
                        <p><strong>Paid Courses:</strong> Paid courses require completion of payment before access is granted. All prices are displayed in Nigerian Naira (₦) and are subject to change. Payment is processed securely through <a href="https://paystack.com" target="_blank" rel="noopener" class="text-primary-jlm hover:underline">Paystack</a>.</p>
                        <p><strong>Coupon Codes:</strong> Discount coupons may be issued by Learnerium administrators and are subject to expiry dates and terms specified at the time of issuance. Coupons cannot be combined unless explicitly stated.</p>
                        <p><strong>Access:</strong> Upon successful payment, you will be granted lifetime access to the course content, unless the course is removed from the platform.</p>
                    '],
                    ['Refund Policy', 'fa-undo', '
                        <p>We offer a <strong>7-day refund policy</strong> for paid courses, subject to the following conditions:</p>
                        <ul>
                            <li>The refund request is submitted within 7 days of purchase.</li>
                            <li>You have not completed more than <strong>20%</strong> of the course content.</li>
                            <li>The course was not purchased as part of a promotional bundle or discount.</li>
                        </ul>
                        <p>To request a refund, email <a href="mailto:refunds@learnerium.com" class="text-primary-jlm hover:underline">refunds@learnerium.com</a> with your order details. Approved refunds will be processed within 5–10 business days to your original payment method.</p>
                        <p>We reserve the right to deny refunds that do not meet the above criteria.</p>
                    '],
                    ['Intellectual Property', 'fa-copyright', '
                        <p>All content on Learnerium — including course materials, videos, quizzes, graphics, logos, and software — is the property of Learnerium, its instructors, or its licensors, and is protected by applicable copyright, trademark, and intellectual property laws.</p>
                        <p>You may not:</p>
                        <ul>
                            <li>Copy, reproduce, distribute, or publicly display any course content without express written permission.</li>
                            <li>Use course content for commercial purposes.</li>
                            <li>Modify, adapt, translate, or create derivative works from our content.</li>
                            <li>Circumvent any technical measures used to protect our content.</li>
                        </ul>
                    '],
                    ['Prohibited Conduct', 'fa-ban', '
                        <p>You agree not to use Learnerium to:</p>
                        <ul>
                            <li>Violate any applicable law or regulation.</li>
                            <li>Harass, abuse, threaten, or intimidate other users or staff.</li>
                            <li>Post or transmit any content that is defamatory, obscene, offensive, or harmful.</li>
                            <li>Impersonate any person or entity, or misrepresent your affiliation with any person or entity.</li>
                            <li>Engage in any form of academic dishonesty (e.g., cheating on quizzes or tasks).</li>
                            <li>Use automated means (bots, scrapers) to access or collect data from the platform.</li>
                            <li>Attempt to gain unauthorised access to any part of the platform or other accounts.</li>
                            <li>Upload viruses, malware, or any other harmful code.</li>
                        </ul>
                        <p>Violations may result in immediate suspension or termination of your account and potential legal action.</p>
                    '],
                    ['Content Standards', 'fa-check-circle', '
                        <p>All content you submit to Learnerium (including discussion posts, assignment submissions, and profile information) must:</p>
                        <ul>
                            <li>Be accurate and not misleading.</li>
                            <li>Not infringe on any third-party intellectual property rights.</li>
                            <li>Not contain offensive, discriminatory, or inappropriate language.</li>
                            <li>Comply with all applicable laws and regulations.</li>
                        </ul>
                        <p>We reserve the right to remove any content that violates these standards without notice.</p>
                    '],
                    ['Termination', 'fa-times-circle', '
                        <p>We may suspend or terminate your access to Learnerium at any time, with or without notice, for any reason, including:</p>
                        <ul>
                            <li>Violation of this Agreement or our community guidelines.</li>
                            <li>Fraudulent, abusive, or illegal activity.</li>
                            <li>Extended account inactivity.</li>
                        </ul>
                        <p>Upon termination, your right to access the platform ceases immediately. You may also terminate your account at any time by contacting us. Sections of this Agreement that by their nature should survive termination (e.g., Intellectual Property, Limitation of Liability) shall remain in full force.</p>
                    '],
                    ['Disclaimers & Limitation of Liability', 'fa-exclamation-circle', '
                        <p><strong>Disclaimer of Warranties:</strong> Learnerium is provided "as is" and "as available" without any warranties of any kind, express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, or non-infringement.</p>
                        <p><strong>Limitation of Liability:</strong> To the fullest extent permitted by law, JLM and Learnerium shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of the platform, including but not limited to loss of data, lost profits, or business interruption.</p>
                        <p>Our total aggregate liability to you for any claims arising from your use of the Services shall not exceed the amount you paid to us in the 12 months preceding the claim.</p>
                    '],
                    ['Governing Law', 'fa-gavel', '
                        <p>These Terms shall be governed by and construed in accordance with the laws of the <strong>Federal Republic of Nigeria</strong>, without regard to its conflict of law provisions.</p>
                        <p>Any dispute arising out of or relating to these Terms shall first be attempted to be resolved through good-faith negotiation. If unresolved, disputes shall be submitted to the jurisdiction of courts in <strong>Lagos State, Nigeria</strong>.</p>
                    '],
                    ['Changes to Terms', 'fa-sync', '
                        <p>We reserve the right to update these Terms at any time. When we make changes, we will:</p>
                        <ul>
                            <li>Update the "Last Updated" date at the top of this page.</li>
                            <li>Notify registered users via email or a prominent notice on the platform.</li>
                        </ul>
                        <p>Your continued use of Learnerium after the effective date of any changes constitutes your acceptance of the revised Terms.</p>
                    '],
                    ['Contact Information', 'fa-envelope', '
                        <p>If you have any questions about this Agreement, please contact us:</p>
                        <ul>
                            <li><strong>Email:</strong> <a href="mailto:legal@learnerium.com" class="text-primary-jlm hover:underline">legal@learnerium.com</a></li>
                            <li><strong>Support:</strong> <a href="'.route('contact').'" class="text-primary-jlm hover:underline">Contact Form</a></li>
                            <li><strong>Operated by:</strong> <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="text-primary-jlm hover:underline">JLM</a>, Lagos, Nigeria</li>
                        </ul>
                    '],
                ];
                @endphp

                @foreach($sections as $index => $section)
                <div id="eua-{{ $index + 1 }}" class="scroll-mt-20">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-jlm/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $section[1] }} text-primary-jlm"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-gray-800">{{ $index + 1 }}. {{ $section[0] }}</h2>
                    </div>
                    <div class="pl-3 space-y-3">
                        {!! $section[2] !!}
                    </div>
                    @if(!$loop->last)<hr class="border-gray-100 mt-8">@endif
                </div>
                @endforeach
            </div>

            <div class="bg-primary-jlm/5 border-t border-primary-jlm/10 px-8 py-6 text-center">
                <p class="text-sm text-gray-500">
                    By using Learnerium you agree to these Terms of Service.
                    <br class="hidden sm:block">
                    See also our <a href="{{ route('privacy') }}" class="text-primary-jlm font-semibold hover:underline">Privacy Policy</a>.
                    &nbsp;|&nbsp;
                    <a href="{{ route('contact') }}" class="text-primary-jlm font-semibold hover:underline">Contact Us</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
