<?php
/**
 * Learnerium — Dynamic Course & Module Generator Script
 * Can be run via Browser: https://learnerium.jlm.com.ng/insert_module.php
 * Or via CLI: php public/insert_module.php
 */

// 1. Bootstrap Laravel if not already in Laravel request lifecycle
if (!class_exists('App\Models\Course')) {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    );
}

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Output styling for browser & CLI
$isCli = (php_sapi_name() === 'cli');
$log = [];

function logMsg(&$log, $msg, $type = 'info') {
    global $isCli;
    $icon = $type === 'success' ? '✅' : ($type === 'warn' ? '⚠️' : ($type === 'error' ? '❌' : 'ℹ️'));
    $log[] = "$icon $msg";
    if ($isCli) {
        echo "$icon $msg\n";
    }
}

try {
    logMsg($log, "Starting Module Insertion Process...");

    DB::beginTransaction();

    // 1. Find or create instructor
    $instructor = User::where('role', 'instructor')->first() 
               ?? User::where('role', 'admin')->first() 
               ?? User::first();

    if (!$instructor) {
        throw new Exception("No user found in the database. Please register an admin or instructor first.");
    }
    logMsg($log, "Using Instructor/Author: {$instructor->name} ({$instructor->email})");

    // 2. Find or create the Course: "Introduction to HTML, CSS and JavaScript"
    $targetTitle = 'Introduction to HTML, CSS and JavaScript';
    $course = Course::where('title', 'LIKE', '%HTML%')
                    ->where('title', 'LIKE', '%CSS%')
                    ->first();

    if (!$course) {
        $course = Course::where('title', $targetTitle)->first();
    }

    if (!$course) {
        $slug = Str::slug($targetTitle);
        $course = Course::create([
            'instructor_id'      => $instructor->id,
            'title'              => $targetTitle,
            'slug'               => $slug,
            'description'        => 'Master the fundamentals of modern front-end web development with HTML5, CSS3, and JavaScript. Learn step-by-step from beginner to building interactive web applications.',
            'price'              => 0.00,
            'level'              => 'Beginner',
            'category'           => 'Web Development',
            'duration_minutes'   => 240,
            'published_at'       => now(),
            'requirements'       => [
                'A computer or laptop with internet access',
                'A free code editor like VS Code or Notepad++',
                'No prior coding experience required'
            ],
            'what_you_will_learn' => [
                'Understand HTML document structure and semantic tags',
                'Style websites using CSS3, Flexbox, and responsive layouts',
                'Write JavaScript for interactivity, DOM manipulation, and event handling',
                'Build and deploy real-world web pages from scratch'
            ]
        ]);
        logMsg($log, "Created Course: '{$course->title}' (ID: {$course->id})", 'success');
    } else {
        logMsg($log, "Found Existing Course: '{$course->title}' (ID: {$course->id})", 'success');
    }

    // 3. Determine module order
    $nextModuleOrder = (Module::where('course_id', $course->id)->max('order') ?? 0) + 1;

    // 4. Create the Module
    $moduleTitle = 'Module ' . $nextModuleOrder . ': Getting Started & Web Fundamentals';
    $module = Module::create([
        'course_id'   => $course->id,
        'title'       => $moduleTitle,
        'description' => 'Comprehensive orientation and foundational concepts of HTML5 semantic structure, CSS3 responsive styling, and modern JavaScript interactivity.',
        'order'       => $nextModuleOrder,
    ]);
    logMsg($log, "Created Module: '{$module->title}' (ID: {$module->id})", 'success');

    // 5. Insert Comprehensive Lessons into the Module
    $lessonsData = [
        [
            'title'       => '1. Platform Orientation & Student Guide',
            'description' => 'A complete walkthrough on how to navigate Learnerium, track lesson progress, submit tasks, and interact with instructors.',
            'video_url'   => 'https://www.youtube.com/watch?v=kUMe1FH4CHE',
            'content'     => "<h2>Welcome to Learnerium!</h2>
<p>In this orientation lesson, you will learn how to maximize your learning experience on Learnerium.</p>
<h3>Key Learning Features</h3>
<ul>
    <li><strong>Curriculum Navigation:</strong> Use the sidebar on your left to explore modules and lessons.</li>
    <li><strong>Watch Progress Tracker:</strong> Watch at least 80% of each lesson video to unlock the <em>Mark as Complete</em> button.</li>
    <li><strong>Practical Tasks:</strong> Some lessons include assignments that require submitting links, code, or files for instructor review.</li>
    <li><strong>Module Assessments:</strong> Test your knowledge at the end of each module with interactive quizzes.</li>
    <li><strong>Discussion & Support:</strong> Post questions in the discussion tab below any lesson to receive guidance from instructors and peers.</li>
</ul>
<p><em>Tip: Complete each lesson in sequence to earn your verified course completion certificate.</em></p>",
            'duration'    => 15
        ],
        [
            'title'       => '2. HTML5 Structure & Semantic Markup',
            'description' => 'Learn the building blocks of every website: elements, tags, document anatomy, headings, paragraphs, links, and semantic tags.',
            'video_url'   => 'https://www.youtube.com/watch?v=UB1O30fR-EE',
            'content'     => "<h2>Anatomy of an HTML5 Document</h2>
<p>HTML (HyperText Markup Language) gives structure and meaning to web content.</p>
<pre><code>&lt;!DOCTYPE html&gt;
&lt;html lang=\"en\"&gt;
&lt;head&gt;
    &lt;meta charset=\"UTF-8\"&gt;
    &lt;title&gt;My First Web Page&lt;/title&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;header&gt;
        &lt;h1&gt;Hello, World!&lt;/h1&gt;
    &lt;/header&gt;
    &lt;main&gt;
        &lt;p&gt;Welcome to web development.&lt;/p&gt;
    &lt;/main&gt;
&lt;/body&gt;
&lt;/html&gt;</code></pre>
<h3>Essential Semantic Elements</h3>
<ul>
    <li><code>&lt;header&gt;</code>, <code>&lt;nav&gt;</code>, <code>&lt;main&gt;</code>, <code>&lt;section&gt;</code>, <code>&lt;article&gt;</code>, <code>&lt;footer&gt;</code></li>
</ul>",
            'duration'    => 25
        ],
        [
            'title'       => '3. CSS3 Styling, Box Model & Responsive Design',
            'description' => 'Master selectors, colors, typography, margin, padding, borders, and modern Flexbox layouts.',
            'video_url'   => 'https://www.youtube.com/watch?v=yfoY53QXEnI',
            'content'     => "<h2>Mastering CSS3</h2>
<p>CSS (Cascading Style Sheets) controls the visual presentation, styling, and layout of your HTML elements.</p>
<h3>The CSS Box Model</h3>
<p>Every element in CSS is represented as a rectangular box consisting of: <strong>Content</strong>, <strong>Padding</strong>, <strong>Border</strong>, and <strong>Margin</strong>.</p>
<pre><code>.card {
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    margin: 16px auto;
    max-width: 600px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}</code></pre>",
            'duration'    => 30
        ],
        [
            'title'       => '4. JavaScript Fundamentals & DOM Manipulation',
            'description' => 'Add dynamic interactivity to your pages using variables, functions, event listeners, and DOM manipulation.',
            'video_url'   => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
            'content'     => "<h2>Introduction to JavaScript</h2>
<p>JavaScript is the programming language of the web. It enables interactivity, user event handling, and dynamic content updates without reloading the page.</p>
<pre><code>document.querySelector('#myButton').addEventListener('click', () => {
    alert('Hello from JavaScript!');
});</code></pre>",
            'duration'    => 35
        ],
    ];

    $lessonOrder = 1;
    $createdLessons = [];

    foreach ($lessonsData as $lData) {
        $lesson = Lesson::create([
            'course_id'        => $course->id,
            'module_id'        => $module->id,
            'title'            => $lData['title'],
            'description'      => $lData['description'],
            'video_url'        => $lData['video_url'],
            'content'          => $lData['content'],
            'order'            => $lessonOrder++,
            'duration_minutes' => $lData['duration'],
        ]);
        $createdLessons[] = $lesson;
        logMsg($log, "Added Lesson {$lesson->order}: '{$lesson->title}' (ID: {$lesson->id})", 'success');
    }

    // 6. Create End-of-Module Assessment Quiz on Lesson 4
    $quizLesson = end($createdLessons);
    $quiz = Quiz::create([
        'lesson_id'          => $quizLesson->id,
        'title'              => 'End of Module Assessment: HTML, CSS & JS Mastery',
        'description'        => 'Test your understanding of the core concepts covered across this module. Passing score is 70%.',
        'is_published'       => 1,
        'time_limit_seconds' => 600, // 10 minutes
    ]);
    logMsg($log, "Created Assessment Quiz: '{$quiz->title}' (ID: {$quiz->id})", 'success');

    // 7. Add Questions with options array
    $questions = [
        [
            'text'    => 'What does HTML stand for?',
            'type'    => 'multiple_choice',
            'options' => [
                'HyperText Markup Language',
                'HighText Machine Language',
                'Hyperlink and Text Management Language',
                'Home Tool Markup Language'
            ],
            'correct' => 'HyperText Markup Language'
        ],
        [
            'text'    => 'Which HTML5 semantic element is used to represent the primary navigation links of a website?',
            'type'    => 'multiple_choice',
            'options' => ['<nav>', '<header>', '<menu>', '<links>'],
            'correct' => '<nav>'
        ],
        [
            'text'    => 'In the CSS Box Model, what is the space directly between the content and the border?',
            'type'    => 'multiple_choice',
            'options' => ['Padding', 'Margin', 'Outline', 'Gap'],
            'correct' => 'Padding'
        ],
        [
            'text'    => 'JavaScript is an interpreted, client-side and server-side programming language.',
            'type'    => 'true_false',
            'options' => ['True', 'False'],
            'correct' => 'True'
        ],
        [
            'text'    => 'Which JavaScript method is used to attach an event listener to an HTML element?',
            'type'    => 'multiple_choice',
            'options' => ['addEventListener()', 'attachEvent()', 'listen()', 'onClick()'],
            'correct' => 'addEventListener()'
        ]
    ];

    $qOrder = 1;
    foreach ($questions as $q) {
        Question::create([
            'quiz_id'        => $quiz->id,
            'question_text'  => $q['text'],
            'type'           => $q['type'],
            'options'        => $q['options'],
            'correct_answer' => $q['correct'],
            'order'          => $qOrder++,
        ]);
    }
    logMsg($log, "Added " . count($questions) . " Questions to Assessment Quiz.", 'success');

    // 8. Create a Practical Task Assignment
    $task = Task::create([
        'lesson_id'    => $quizLesson->id,
        'title'        => 'Hands-on Project: Build a Responsive Personal Portfolio Page',
        'description'  => 'Create an HTML page with semantic tags, CSS styling (colors, flexbox, fonts), and a JavaScript interactive button. Submit your code file or GitHub/CodePen link.',
        'type'         => 'link',
        'is_required'  => true,
    ]);
    logMsg($log, "Created Practical Task: '{$task->title}' (ID: {$task->id})", 'success');

    // Recalculate total course duration
    $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
    $course->update(['duration_minutes' => max(60, $totalMinutes)]);
    logMsg($log, "Updated total course duration: {$totalMinutes} minutes", 'success');

    DB::commit();
    logMsg($log, "🎉 All modules, lessons, quizzes, and tasks inserted successfully!", 'success');

} catch (\Throwable $e) {
    DB::rollBack();
    logMsg($log, "Error: " . $e->getMessage(), 'error');
    logMsg($log, $e->getTraceAsString(), 'error');
}

if (!$isCli): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Module Generator — Learnerium</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-pink-600 flex items-center justify-center text-xl font-bold">L</div>
            <div>
                <h1 class="text-xl font-bold">Learnerium Module Generator</h1>
                <p class="text-xs text-gray-400">Course & Curriculum Insertion Tool</p>
            </div>
        </div>
        <div class="bg-black/50 rounded-xl p-5 font-mono text-sm space-y-2 border border-gray-700/50 max-h-96 overflow-y-auto mb-6">
            <?php foreach($log as $line): ?>
                <div class="<?= str_contains($line, '❌') ? 'text-red-400' : (str_contains($line, '✅') ? 'text-emerald-400' : 'text-gray-300') ?>">
                    <?= htmlspecialchars($line) ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-between items-center">
            <a href="/" class="text-sm text-gray-400 hover:text-white transition">← Return to Homepage</a>
            <a href="/courses" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow">View Courses →</a>
        </div>
    </div>
</body>
</html>
<?php endif; ?>
