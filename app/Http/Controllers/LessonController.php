<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Submission;
use App\Http\Controllers\StudentTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    /**
     * Show lesson player for students
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Verify lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        // Check if user is enrolled (with payment) or is the instructor/admin
        $user = Auth::user();
        $isInstructor = $user->id === $course->instructor_id;
        $isAdmin = $user->role === 'admin';

        if (!$isInstructor && !$isAdmin && !$user->enrolledIn($course->id)) {
            // If paid course, redirect to checkout
            if ((float) $course->price > 0) {
                return redirect()->route('courses.checkout', $course)
                    ->with('info', 'Please complete payment to access this lesson.');
            }
            // Free course — just enroll them
            \App\Models\Enrollment::firstOrCreate(
                ['user_id' => $user->id, 'course_id' => $course->id],
                ['payment_status' => 'paid', 'amount_paid' => 0]
            );
        }

        // Verify module unlock and drip status for student
        if (!$lesson->isUnlockedFor($user)) {
            $dripMsg = $lesson->dripMessageFor($user);
            $errorText = $dripMsg 
                ? "🔒 Drip Content Locked: This lesson {$dripMsg}."
                : "🔒 Module Locked: Complete all lessons in previous modules first to unlock this section.";
            return redirect()->route('course.detail', $course->slug)->with('error', $errorText);
        }

        // Get or create lesson progress for student
        $progress = null;
        if ($user->id !== $course->instructor_id) {
            $progress = LessonProgress::firstOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                ['progress_percentage' => 0, 'completed' => false]
            );
        }

        $lessons = $course->lessons()->with('tasks')->get();


        // Load tasks for this lesson
        $tasks = $lesson->tasks()->orderBy('id')->get();

        // Load user's own submissions keyed by task_id
        $userSubmissions = [];
        $pendingRequiredTask = false;
        $lessonCompleted = false;
        $pendingReviews = [];

        if ($user->id !== $course->instructor_id) {
            // Check lesson completion status
            $lessonProgress = LessonProgress::where('user_id', $user->id)
                ->where('lesson_id', $lesson->id)
                ->first();
            $lessonCompleted = $lessonProgress && $lessonProgress->completed;

            // Submissions by this user for this lesson's tasks
            $taskIds = $tasks->pluck('id');
            $submissions = Submission::whereIn('task_id', $taskIds)
                ->where('user_id', $user->id)
                ->get()
                ->keyBy('task_id');
            $userSubmissions = $submissions->toArray();

            // Convert back to models for easy access in view
            $userSubmissions = $submissions; // keep as collection

            // Check if any required task is not yet approved
            foreach ($tasks as $task) {
                if ($task->is_required) {
                    $sub = $submissions->get($task->id);
                    if (!$sub || $sub->status !== 'approved') {
                        $pendingRequiredTask = true;
                        break;
                    }
                }
            }

            // Load peer review submissions for each peer-review task
            foreach ($tasks->where('peer_review_enabled', true) as $task) {
                $pendingReviews[$task->id] = StudentTaskController::pendingReviewsFor($user->id, $lesson);
            }
        }

        return view('student.lesson', compact(
            'course', 'lesson', 'lessons', 'progress',
            'tasks', 'userSubmissions', 'pendingRequiredTask',
            'lessonCompleted', 'pendingReviews'
        ));
    }

    /**
     * Mark lesson as completed
     */
    public function markComplete(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = Auth::user();
        if (!$user->enrolledIn($course->id)) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Verify module unlock and drip status
        if (!$lesson->isUnlockedFor($user)) {
            return redirect()->back()->with('error', 'This lesson is currently locked.');
        }

        // Verify all required tasks are submitted and approved
        $requiredTaskIds = $lesson->tasks()->where('is_required', true)->pluck('id');
        if ($requiredTaskIds->isNotEmpty()) {
            $approvedCount = Submission::whereIn('task_id', $requiredTaskIds)
                ->where('user_id', $user->id)
                ->where('status', 'approved')
                ->count();
            if ($approvedCount < $requiredTaskIds->count()) {
                return redirect()->back()->with('error', 'You must complete and have all required tasks approved before marking this lesson complete.');
            }
        }

        $progress = LessonProgress::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrCreate(
                [],
                ['progress_percentage' => 100, 'completed' => true, 'completed_at' => now()]
            );

        if (!$progress->completed) {
            $progress->update([
                'completed' => true,
                'progress_percentage' => 100,
                'completed_at' => now(),
            ]);
        }

        // Update enrollment progress after marking lesson complete
        $enrollment = $user->enrollments()->where('course_id', $course->id)->first();
        if ($enrollment) {
            $enrollment->updateProgress();
        }

        return redirect()->back()->with('status', 'Lesson marked as completed! Great work.');
    }

    /**
     * AJAX endpoint to download and import video from Google Drive directly to server storage.
     */
    public function ajaxImportGoogleDrive(Request $request)
    {
        $request->validate([
            'gdrive_url' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user || ($user->role !== 'admin' && $user->role !== 'instructor' && (!method_exists($user, 'isInstructor') || !$user->isInstructor()))) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        $url = trim($request->input('gdrive_url'));
        $importedPath = $this->importVideoFromGoogleDrive($url);

        if ($importedPath) {
            $fullPath = public_path($importedPath);
            $size = file_exists($fullPath) ? round(filesize($fullPath) / (1024 * 1024), 2) . ' MB' : 'Saved';
            return response()->json([
                'success'  => true,
                'path'     => $importedPath,
                'filename' => basename($importedPath),
                'size'     => $size,
                'message'  => "Video ({$size}) downloaded from Google Drive and saved to server storage successfully!",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Could not download video from Google Drive. Please make sure the link is set to "Anyone with the link can view", or upload the file directly.',
        ], 422);
    }

    /**
     * Store a new lesson (instructor only)
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();
        if ($user->id !== $course->instructor_id && $user->role !== 'admin') {
            abort(403, 'You are not the instructor of this course.');
        }

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'module_id'           => 'nullable|exists:modules,id',
            'description'         => 'nullable|string',
            'order'               => 'required|integer|min:0',
            'video_url'           => 'nullable|url',
            'imported_video_path' => 'nullable|string',
            'gdrive_import_url'   => 'nullable|string',
            'video_file'          => 'nullable|file|mimes:mp4,webm,mov,avi,quicktime|max:512000', // 500 MB
            'duration_minutes'    => 'nullable|integer|min:0',
            'content'             => 'nullable|string',
            'drip_date'           => 'nullable|date',
            'drip_days'           => 'nullable|integer|min:0',
        ]);

        // Priority 1: Handle pre-imported Google Drive video path with strict traversal validation
        if ($request->filled('imported_video_path')) {
            $importedPath = $request->input('imported_video_path');
            if (preg_match('/^uploads\/videos\/[a-zA-Z0-9_\.-]+$/', $importedPath)) {
                $validated['video_url'] = $importedPath;
            }
        }
        // Priority 2: Handle direct device file upload
        elseif ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
            $videosDir = public_path('uploads/videos');
            if (!is_dir($videosDir)) {
                @mkdir($videosDir, 0775, true);
            }
            $file = $request->file('video_file');
            $filename = time() . '_' . preg_replace('/[^a-z0-9._-]/i', '_', $file->getClientOriginalName());
            $file->move($videosDir, $filename);
            $validated['video_url'] = 'uploads/videos/' . $filename;
        }
        // Priority 3: Handle Google Drive Direct Import to Server fallback
        elseif ($request->filled('gdrive_import_url')) {
            $importedPath = $this->importVideoFromGoogleDrive($request->input('gdrive_import_url'));
            if ($importedPath) {
                $validated['video_url'] = $importedPath;
            } else {
                $validated['video_url'] = $request->input('gdrive_import_url');
            }
        }

        $validated['drip_date'] = $request->drip_date ?: null;
        $validated['drip_days'] = $request->drip_days !== null && $request->drip_days !== '' ? (int)$request->drip_days : null;

        if (!\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'duration_minutes')) {
            unset($validated['duration_minutes']);
        }

        // Sanitize rich-text HTML content to prevent stored XSS
        if (!empty($validated['content'])) {
            $validated['content'] = $this->sanitizeLessonContent($validated['content']);
        }

        unset($validated['gdrive_import_url'], $validated['imported_video_path']);
        $lesson = $course->lessons()->create($validated);

        // Auto-recalculate course duration
        if (\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'duration_minutes')) {
            $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
            if ($totalMinutes > 0) {
                $course->update(['duration_minutes' => $totalMinutes]);
            }
        }

        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson created successfully!');
    }

    /**
     * Update a lesson (instructor only)
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = Auth::user();
        if ($user->id !== $course->instructor_id && $user->role !== 'admin') {
            abort(403, 'You are not the instructor of this course.');
        }

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'module_id'           => 'nullable|exists:modules,id',
            'description'         => 'nullable|string',
            'order'               => 'required|integer|min:0',
            'video_url'           => 'nullable|url',
            'imported_video_path' => 'nullable|string',
            'gdrive_import_url'   => 'nullable|string',
            'video_file'          => 'nullable|file|mimes:mp4,webm,mov,avi,quicktime|max:512000', // 500 MB
            'duration_minutes'    => 'nullable|integer|min:0',
            'content'             => 'nullable|string',
            'drip_date'           => 'nullable|date',
            'drip_days'           => 'nullable|integer|min:0',
        ]);

        // Priority 1: Handle pre-imported Google Drive video path with strict traversal validation
        if ($request->filled('imported_video_path')) {
            $importedPath = $request->input('imported_video_path');
            if (preg_match('/^uploads\/videos\/[a-zA-Z0-9_\.-]+$/', $importedPath)) {
                if ($lesson->video_url && $lesson->video_url !== $importedPath) {
                    $this->safeDeleteLocalVideo($lesson->video_url);
                }
                $validated['video_url'] = $importedPath;
            }
        }
        // Priority 2: Handle direct device file upload
        elseif ($request->hasFile('video_file') && $request->file('video_file')->isValid()) {
            $this->safeDeleteLocalVideo($lesson->video_url);
            $videosDir = public_path('uploads/videos');
            if (!is_dir($videosDir)) {
                @mkdir($videosDir, 0775, true);
            }
            $file = $request->file('video_file');
            $filename = time() . '_' . preg_replace('/[^a-z0-9._-]/i', '_', $file->getClientOriginalName());
            $file->move($videosDir, $filename);
            $validated['video_url'] = 'uploads/videos/' . $filename;
        }
        // Priority 3: Handle Google Drive Direct Import fallback
        elseif ($request->filled('gdrive_import_url')) {
            $importedPath = $this->importVideoFromGoogleDrive($request->input('gdrive_import_url'));
            if ($importedPath) {
                $this->safeDeleteLocalVideo($lesson->video_url);
                $validated['video_url'] = $importedPath;
            } else {
                $validated['video_url'] = $request->input('gdrive_import_url');
            }
        }

        $validated['drip_date'] = $request->drip_date ?: null;
        $validated['drip_days'] = $request->drip_days !== null && $request->drip_days !== '' ? (int)$request->drip_days : null;

        if (!\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'duration_minutes')) {
            unset($validated['duration_minutes']);
        }

        // Sanitize rich-text HTML content to prevent stored XSS
        if (!empty($validated['content'])) {
            $validated['content'] = $this->sanitizeLessonContent($validated['content']);
        }

        unset($validated['gdrive_import_url'], $validated['imported_video_path']);
        $lesson->update($validated);

        // Auto-recalculate course duration
        if (\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'duration_minutes')) {
            $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
            if ($totalMinutes > 0) {
                $course->update(['duration_minutes' => $totalMinutes]);
            }
        }

        $statusMsg = isset($importedPath) && $importedPath 
            ? 'Lesson updated and Google Drive video successfully imported to server!' 
            : 'Lesson updated successfully!';

        return redirect()->route('instructor.courses.edit', $course)->with('success', $statusMsg);
    }

    /**
     * Delete a lesson (instructor only)
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $user = Auth::user();
        if ($user->id !== $course->instructor_id && $user->role !== 'admin') {
            abort(403, 'You are not the instructor of this course.');
        }

        // Clean up associated local video file
        $this->safeDeleteLocalVideo($lesson->video_url);

        $lesson->delete();

        // Auto-recalculate course duration
        if (\Illuminate\Support\Facades\Schema::hasColumn('lessons', 'duration_minutes')) {
            $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
            $course->update(['duration_minutes' => max(1, $totalMinutes)]);
        }

        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson deleted successfully!');
    }

    /**
     * Safely delete a local uploaded video file, preventing path traversal and orphaned video leaks.
     */
    private function safeDeleteLocalVideo(?string $path): void
    {
        if (!$path || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        // Only allow paths matching uploads/videos/<safe-filename>
        if (!preg_match('/^uploads\/videos\/[a-zA-Z0-9_\.-]+$/', $path)) {
            return;
        }

        $fullPath = public_path($path);
        $videosDir = realpath(public_path('uploads/videos'));

        if ($videosDir && file_exists($fullPath)) {
            $realFile = realpath($fullPath);
            if ($realFile && str_starts_with($realFile, $videosDir . DIRECTORY_SEPARATOR)) {
                @unlink($realFile);
            }
        }
    }

    /**
     * Sanitize rich-text HTML content using strict tag and attribute allowlists.
     * Prevents XSS, entity obfuscation, event handlers, and malicious URIs.
     */
    private function sanitizeLessonContent(string $html): string
    {
        // Safe tags produced by WYSIWYG editors
        $allowedTags = '<p><br><strong><b><em><i><u><s><del><ins><mark><small><sup><sub>'.
                       '<h1><h2><h3><h4><h5><h6>'.
                       '<ul><ol><li><dl><dt><dd>'.
                       '<blockquote><pre><code><kbd><samp>'.
                       '<table><thead><tbody><tfoot><tr><th><td><caption>'.
                       '<a><img><figure><figcaption>'.
                       '<div><span><hr><section><article>';

        $clean = strip_tags($html, $allowedTags);

        // Remove dangerous tags and wrappers that strip_tags may leave behind
        $clean = preg_replace('/<\/?(script|iframe|object|embed|style|link|meta|base|form|input|button|svg|math)[^>]*>/i', '', $clean);

        // Remove all inline event handlers (onclick, onerror, onload, onmouseover, etc.)
        $clean = preg_replace('/\s+on[a-zA-Z]+\s*=\s*(["\']).*?\1/is', '', $clean);
        $clean = preg_replace('/\s+on[a-zA-Z]+\s*=\s*[^\s>]+/i', '', $clean);

        // Disallow dangerous URI schemes (javascript:, data:, vbscript:) including entity encoded variants
        $clean = preg_replace_callback('/(href|src|action)\s*=\s*(["\'])(.*?)\2/is', function($match) {
            $attr = $match[1];
            $url = trim(html_entity_decode($match[3], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            
            // Allow safe protocols or relative paths
            if (preg_match('/^(https?:\/\/|mailto:|\/|#)/i', $url) && !preg_match('/^(javascript|vbscript|data):/i', $url)) {
                return $attr . '="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"';
            }
            return $attr . '="#"';
        }, $clean);

        return $clean;
    }

    /**
     * Download and save a public Google Drive video directly to server storage.
     * Handles Google Drive confirm tokens, uuid parameters, and caps download size at 500 MB.
     *
     * @param string $driveUrl
     * @return string|null Relative video path (e.g. 'uploads/videos/gdrive_123.mp4') or null on failure
     */
    private function importVideoFromGoogleDrive(string $driveUrl): ?string
    {
        @set_time_limit(600);
        @ini_set('max_execution_time', '600');

        // Extract Google Drive File ID
        $fileId = null;
        if (preg_match('/(?:drive\.google\.com\/(?:file\/d\/|open\?id=|uc\?.*id=)|docs\.google\.com\/(?:file\/d\/|open\?id=|uc\?.*id=))([a-zA-Z0-9_-]{20,})/i', $driveUrl, $m)) {
            $fileId = $m[1];
        } elseif (preg_match('/^[a-zA-Z0-9_-]{25,}$/', trim($driveUrl))) {
            $fileId = trim($driveUrl);
        }

        if (!$fileId) {
            \Illuminate\Support\Facades\Log::warning("Google Drive import failed: could not extract File ID from URL '{$driveUrl}'");
            return null;
        }

        $videosDir = public_path('uploads/videos');
        if (!is_dir($videosDir)) {
            @mkdir($videosDir, 0775, true);
        }

        $cookieFile = tempnam(sys_get_temp_dir(), 'gdrive_cookie_');
        $tempTarget = tempnam(sys_get_temp_dir(), 'gdrive_vid_');
        $initUrl = "https://drive.usercontent.google.com/download?id={$fileId}&export=download";

        // Step 1: Probe request to obtain download tokens, cookies, redirects, or virus warning form
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $initUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        $downloadUrl = $initUrl;

        // Check if Google Drive returned a "Large file virus scan warning" confirmation page
        if ($response && str_contains($contentType ?: '', 'text/html')) {
            $formAction = 'https://drive.usercontent.google.com/download';
            if (preg_match('/<form[^>]+action=["\']([^"\']+)["\']/i', $response, $m)) {
                $formAction = html_entity_decode($m[1]);
            }
            preg_match_all('/<input[^>]+type=["\']hidden["\'][^>]*>/i', $response, $inputs);
            $params = [];
            foreach ($inputs[0] as $input) {
                if (preg_match('/name=["\']([^"\']+)["\']/', $input, $n) && preg_match('/value=["\']([^"\']*)["\']/', $input, $v)) {
                    $params[$n[1]] = html_entity_decode($v[1]);
                }
            }

            if (!empty($params)) {
                $downloadUrl = $formAction . '?' . http_build_query($params);
            } else {
                // Fallback token extraction
                $confirmToken = null;
                if (preg_match('/confirm=([a-zA-Z0-9_-]+)/i', $response, $cm)) {
                    $confirmToken = $cm[1];
                }
                if ($confirmToken) {
                    $downloadUrl = "https://drive.usercontent.google.com/download?id={$fileId}&export=download&confirm={$confirmToken}";
                }
            }
        }

        // Step 2: Stream download directly to file with max 500MB size limit
        $maxBytes = 500 * 1024 * 1024; // 500 MB
        $fp = fopen($tempTarget, 'w+');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $downloadUrl);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 600); // 10 minutes max
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function($resource, $downloadSize, $downloaded) use ($maxBytes) {
            if ($downloaded > $maxBytes) {
                return 1; // Abort transfer if exceeding 500 MB
            }
            return 0;
        });

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $downloadedSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
        $finalContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);
        fclose($fp);
        @unlink($cookieFile);

        // Verification: ensure file was downloaded and is not an HTML error
        if ($httpCode >= 200 && $httpCode < 400 && $downloadedSize > 10240 && $downloadedSize <= $maxBytes && !str_contains($finalContentType ?: '', 'text/html')) {
            $filename = 'gdrive_' . time() . '_' . substr($fileId, 0, 10) . '.mp4';
            $finalPath = $videosDir . '/' . $filename;
            if (rename($tempTarget, $finalPath)) {
                @chmod($finalPath, 0644);
                return 'uploads/videos/' . $filename;
            }
        }

        @unlink($tempTarget);
        \Illuminate\Support\Facades\Log::warning("Google Drive download failed. HTTP: {$httpCode}, Size: {$downloadedSize}, Content-Type: {$finalContentType}");
        return null;
    }
}

