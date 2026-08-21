@extends('layouts.app')

@section('title', 'Privacy Policy — Learnerium')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero -->
    <div class="bg-gradient-to-br from-primary-jlm via-primary-jlm-dark to-secondary-jlm text-white py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-5">
                <i class="fas fa-shield-alt text-accent-jlm"></i> Legal Document
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">Privacy Policy</h1>
            <p class="text-white/70 max-w-xl mx-auto">Effective Date: <strong class="text-white">January 1, 2025</strong> &nbsp;|&nbsp; Last Updated: <strong class="text-white">August 2025</strong></p>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 py-16">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- TOC -->
            <div class="bg-gray-50 border-b border-gray-100 px-8 py-6">
                <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wider mb-4"><i class="fas fa-list mr-2 text-primary-jlm"></i>Table of Contents</h2>
                <ol class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-sm text-primary-jlm">
                    @foreach([
                        'Information We Collect',
                        'How We Use Your Information',
                        'Cookies & Tracking',
                        'Sharing of Information',
                        'Data Retention',
                        'Your Rights',
                        'Security',
                        'Third-Party Services',
                        'Children\'s Privacy',
                        'Changes to This Policy',
                        'Contact Us',
                    ] as $i => $item)
                    <li><a href="#section-{{ $i+1 }}" class="hover:underline font-medium">{{ $i+1 }}. {{ $item }}</a></li>
                    @endforeach
                </ol>
            </div>

            <div class="px-8 py-10 space-y-10 prose prose-gray max-w-none">
                <p class="text-gray-600 leading-relaxed">
                    Welcome to <strong>Learnerium</strong>, an online learning platform operated by
                    <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="text-primary-jlm hover:underline font-semibold">JLM</a>
                    ("we," "our," or "us"). This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our platform at <strong>learnerium.com</strong> or any related service. Please read this policy carefully. By using Learnerium, you consent to the practices described herein.
                </p>

                @php
                $sections = [
                    ['Information We Collect', 'fa-database', 'blue', '
                        <p>We collect information that you provide directly to us and information collected automatically when you interact with our platform:</p>
                        <h4>a) Information You Provide</h4>
                        <ul>
                            <li><strong>Account Registration:</strong> Name, email address, password, and role (student/instructor).</li>
                            <li><strong>Profile Information:</strong> Profile picture, bio, and professional details (for instructors).</li>
                            <li><strong>Course Content:</strong> Assignments, quiz responses, discussion posts, and progress data.</li>
                            <li><strong>Payment Information:</strong> Transaction details processed via Paystack (we do not store card numbers; Paystack handles payment security under PCI-DSS standards).</li>
                            <li><strong>Communications:</strong> Support messages, feedback, and email correspondence.</li>
                        </ul>
                        <h4>b) Automatically Collected Information</h4>
                        <ul>
                            <li>IP address, browser type, operating system, and device information.</li>
                            <li>Pages visited, time spent, links clicked, and referring URLs.</li>
                            <li>Cookies and similar tracking technologies.</li>
                        </ul>
                    '],
                    ['How We Use Your Information', 'fa-cog', 'purple', '
                        <p>We use the information we collect to:</p>
                        <ul>
                            <li>Create and manage your Learnerium account.</li>
                            <li>Process enrollments, payments, and issue certificates of completion.</li>
                            <li>Deliver course content, track your learning progress, and personalise your experience.</li>
                            <li>Communicate with you about your account, course updates, and platform news.</li>
                            <li>Respond to your inquiries and provide customer support.</li>
                            <li>Improve our platform through analytics and user feedback.</li>
                            <li>Enforce our Terms of Service and legal obligations.</li>
                            <li>Prevent fraud, abuse, and security threats.</li>
                        </ul>
                    '],
                    ['Cookies & Tracking', 'fa-cookie-bite', 'yellow', '
                        <p>Learnerium uses cookies and similar technologies to enhance your experience:</p>
                        <ul>
                            <li><strong>Session Cookies:</strong> Required for login and security.</li>
                            <li><strong>Preference Cookies:</strong> Remember your settings and language.</li>
                            <li><strong>Analytics Cookies:</strong> Help us understand how you use our platform (e.g., page visit duration).</li>
                        </ul>
                        <p>You can control cookies through your browser settings. Disabling essential cookies may impair platform functionality.</p>
                    '],
                    ['Sharing of Information', 'fa-share-alt', 'pink', '
                        <p>We do not sell your personal data. We may share information with:</p>
                        <ul>
                            <li><strong>Instructors:</strong> If you enroll in a course, the instructor can see your name and progress.</li>
                            <li><strong>Payment Processors:</strong> Paystack receives your payment details to process transactions securely.</li>
                            <li><strong>Service Providers:</strong> Trusted third parties who assist in operating our platform (e.g., hosting, email delivery) under strict confidentiality agreements.</li>
                            <li><strong>Legal Authorities:</strong> When required by law, court order, or to protect the rights and safety of Learnerium or others.</li>
                        </ul>
                    '],
                    ['Data Retention', 'fa-archive', 'green', '
                        <p>We retain your data for as long as your account is active or as needed to provide our services. Upon account deletion, we will delete or anonymise your personal data within <strong>90 days</strong>, except where we are required to retain it by law (e.g., financial records for 7 years as required by Nigerian tax regulations).</p>
                    '],
                    ['Your Rights', 'fa-user-shield', 'indigo', '
                        <p>As a user of Learnerium, you have the following rights:</p>
                        <ul>
                            <li><strong>Access:</strong> Request a copy of the personal data we hold about you.</li>
                            <li><strong>Correction:</strong> Update or correct inaccurate information via your profile settings.</li>
                            <li><strong>Deletion:</strong> Request deletion of your account and associated data.</li>
                            <li><strong>Portability:</strong> Request your data in a structured, machine-readable format.</li>
                            <li><strong>Opt-Out:</strong> Unsubscribe from marketing emails at any time using the unsubscribe link in our emails.</li>
                        </ul>
                        <p>To exercise your rights, email us at <a href="mailto:privacy@learnerium.com" class="text-primary-jlm hover:underline">privacy@learnerium.com</a>.</p>
                    '],
                    ['Security', 'fa-lock', 'red', '
                        <p>We implement industry-standard security measures including:</p>
                        <ul>
                            <li>SSL/TLS encryption for all data in transit.</li>
                            <li>Hashed passwords (never stored in plaintext).</li>
                            <li>Regular security audits and vulnerability assessments.</li>
                            <li>Restricted access to personal data (role-based access control).</li>
                        </ul>
                        <p>Despite our efforts, no method of transmission over the Internet is 100% secure. We encourage you to use a strong, unique password and to never share your credentials.</p>
                    '],
                    ['Third-Party Services', 'fa-external-link-alt', 'orange', '
                        <p>Our platform integrates with the following third-party services, each governed by their own privacy policies:</p>
                        <ul>
                            <li><strong>Paystack</strong> — Payment processing (<a href="https://paystack.com/privacy" target="_blank" rel="noopener" class="text-primary-jlm hover:underline">paystack.com/privacy</a>)</li>
                            <li><strong>Google Fonts</strong> — Typography delivery</li>
                            <li><strong>Font Awesome</strong> — Icon library</li>
                        </ul>
                        <p>We are not responsible for the privacy practices of third-party services. We encourage you to review their policies.</p>
                    '],
                    ['Children\'s Privacy', 'fa-child', 'teal', '
                        <p>Learnerium is not directed to individuals under the age of <strong>13</strong>. We do not knowingly collect personal information from children under 13. If we become aware that a child under 13 has provided us with personal information, we will delete it immediately. If you believe a minor has used our platform, please contact us at <a href="mailto:privacy@learnerium.com" class="text-primary-jlm hover:underline">privacy@learnerium.com</a>.</p>
                    '],
                    ['Changes to This Policy', 'fa-sync-alt', 'gray', '
                        <p>We may update this Privacy Policy from time to time. We will notify you of significant changes by:</p>
                        <ul>
                            <li>Posting a prominent notice on our platform.</li>
                            <li>Sending an email notification to registered users.</li>
                        </ul>
                        <p>Your continued use of Learnerium after the effective date constitutes your acceptance of the updated policy.</p>
                    '],
                    ['Contact Us', 'fa-envelope', 'blue', '
                        <p>If you have any questions, concerns, or requests regarding this Privacy Policy or your personal data, please contact us:</p>
                        <ul>
                            <li><strong>Email:</strong> <a href="mailto:privacy@learnerium.com" class="text-primary-jlm hover:underline">privacy@learnerium.com</a></li>
                            <li><strong>Operated by:</strong> <a href="https://jlm.com.ng" target="_blank" rel="noopener" class="text-primary-jlm hover:underline">JLM</a></li>
                            <li><strong>Address:</strong> Lagos, Nigeria</li>
                        </ul>
                    '],
                ];
                @endphp

                @foreach($sections as $index => $section)
                <div id="section-{{ $index + 1 }}" class="scroll-mt-20">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-jlm/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $section[1] }} text-primary-jlm"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-gray-800">{{ $index + 1 }}. {{ $section[0] }}</h2>
                    </div>
                    <div class="pl-13 text-gray-600 leading-relaxed text-sm space-y-3">
                        {!! $section[3] !!}
                    </div>
                    @if(!$loop->last)<hr class="border-gray-100 mt-8">@endif
                </div>
                @endforeach
            </div>

            <div class="bg-primary-jlm/5 border-t border-primary-jlm/10 px-8 py-6 text-center">
                <p class="text-sm text-gray-500">
                    By using Learnerium, you acknowledge that you have read and understood this Privacy Policy.
                    <br>Questions? <a href="{{ route('contact') }}" class="text-primary-jlm font-semibold hover:underline">Contact Us</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
