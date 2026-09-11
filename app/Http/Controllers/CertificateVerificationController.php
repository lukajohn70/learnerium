<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    /**
     * Public certificate verification page.
     * Can be accessed with or without a serial code.
     */
    public function verify(Request $request, ?string $code = null)
    {
        $code = $code ?: $request->query('code');
        $enrollment = null;
        $course = null;
        $student = null;
        $serialCode = null;
        $isValid = false;

        if ($code) {
            $code = trim(strtoupper($code));
            $cleanCode = preg_replace('/[^A-Z0-9]/', '', $code);

            // 1. Try finding by composite ID: {enrollment_id}{course_id}{user_id} (e.g. "2413")
            // Or stripped version from LNR-CERT-2413
            $numericCandidate = preg_replace('/^LNRCERT/', '', $cleanCode);

            // Try exact matching against known enrollments with 100% completion
            $completedEnrollments = Enrollment::with(['course', 'user'])
                ->where('progress_percentage', '>=', 100)
                ->get();

            foreach ($completedEnrollments as $e) {
                $composite = $e->id . $e->course_id . $e->user_id;
                $formattedSerial = "LNRCERT" . $composite;
                $hashSerial = strtoupper(substr(md5("lnr-cert-{$e->id}-{$e->course_id}-{$e->user_id}"), 0, 8));

                if (
                    $cleanCode === $composite ||
                    $cleanCode === $formattedSerial ||
                    $cleanCode === "LNRCERT" . $e->id ||
                    $cleanCode === "LNR" . $composite ||
                    $cleanCode === "LNR" . $hashSerial ||
                    $cleanCode === $hashSerial ||
                    $numericCandidate === (string)$composite ||
                    $numericCandidate === (string)$e->id
                ) {
                    $enrollment = $e;
                    $course = $e->course;
                    $student = $e->user;
                    $serialCode = 'LNR-CERT-' . $composite;
                    $isValid = true;
                    break;
                }
            }

            // Fallback: If not found in completed enrollments, check if it's an admin/preview test or direct enrollment ID
            if (!$enrollment && is_numeric($numericCandidate)) {
                $fallback = Enrollment::with(['course', 'user'])->find((int)$numericCandidate);
                if ($fallback && $fallback->course && $fallback->user) {
                    $enrollment = $fallback;
                    $course = $fallback->course;
                    $student = $fallback->user;
                    $serialCode = 'LNR-CERT-' . ($fallback->id . $fallback->course_id . $fallback->user_id);
                    $isValid = ($fallback->progress_percentage >= 100 || $fallback->payment_status === 'paid');
                }
            }
        }

        return view('certificate-verify', compact('code', 'enrollment', 'course', 'student', 'serialCode', 'isValid'));
    }

    /**
     * Handle search form submission.
     */
    public function search(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:100',
        ]);

        $code = trim($request->input('code'));
        return redirect()->route('certificate.verify', ['code' => $code]);
    }
}
