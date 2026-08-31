<?php
/**
 * Learnerium — Module 3: Introduction to CSS Generator & Updater
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
use App\Models\ModuleMaterial;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
    logMsg($log, "Starting Module 3 Update Process...");

    DB::beginTransaction();

    // 1. Find the Course: "Introduction to HTML, CSS and JavaScript"
    $course = Course::where('title', 'LIKE', '%HTML%')
                    ->where('title', 'LIKE', '%CSS%')
                    ->first();

    if (!$course) {
        $course = Course::first();
    }

    if (!$course) {
        throw new Exception("No Course found in database.");
    }
    logMsg($log, "Target Course: '{$course->title}' (ID: {$course->id})", 'success');

    // 2. Find or Create Module 3: "Module 3: Introduction to CSS"
    $module = Module::where('course_id', $course->id)
                    ->where(function($q) {
                        $q->where('order', 3)
                          ->orWhere('title', 'LIKE', '%Module 3%')
                          ->orWhere('title', 'LIKE', '%Introduction to CSS%')
                          ->orWhere('title', 'LIKE', '%Getting Started%');
                    })
                    ->first();

    $moduleTitle = 'Module 3: Introduction to CSS';
    $moduleDescription = 'Master CSS fundamentals, styling workflows, the Box Model, CSS Custom Properties (Variables), Flexbox navigation layouts, CSS Grid systems, and responsive design.';

    if ($module) {
        $module->update([
            'title'       => $moduleTitle,
            'description' => $moduleDescription,
            'order'       => 3,
        ]);
        logMsg($log, "Updated Existing Module (ID: {$module->id}) to '{$module->title}'", 'success');
    } else {
        $module = Module::create([
            'course_id'   => $course->id,
            'title'       => $moduleTitle,
            'description' => $moduleDescription,
            'order'       => 3,
        ]);
        logMsg($log, "Created New Module: '{$module->title}' (ID: {$module->id})", 'success');
    }

    // 3. Define the 4 Exact Lessons from the Curriculum with Provided Video Links
    $lessonsData = [
        [
            'title'       => 'Week 2.0 - HTML Update',
            'description' => 'Reviewing and preparing our semantic HTML structure for CSS styling, classes, IDs, and responsive component architecture.',
            'video_url'   => 'https://drive.google.com/file/d/1tc7ads1E51QLqia8gid5vJR5dW-o4lIw/view?usp=drive_link',
            'content'     => "<h2>Week 2.0: HTML Semantic Refactoring</h2>
<p>Before applying styling, we review our HTML markup to ensure proper semantic hierarchy, accessible tag usage, and well-structured classes.</p>

<h3>Key Concepts Covered</h3>
<ul>
    <li><strong>Document Structure:</strong> Ensuring proper nesting of <code>&lt;header&gt;</code>, <code>&lt;nav&gt;</code>, <code>&lt;main&gt;</code>, <code>&lt;section&gt;</code>, and <code>&lt;footer&gt;</code>.</li>
    <li><strong>Class & ID Naming Conventions:</strong> Writing clean, scalable CSS class names.</li>
    <li><strong>Linking External Stylesheets:</strong> Connecting your <code>styles.css</code> file in the HTML <code>&lt;head&gt;</code> with <code>&lt;link rel=\"stylesheet\" href=\"styles.css\"&gt;</code>.</li>
</ul>",
            'duration'    => 20
        ],
        [
            'title'       => 'Week 2.1 — The Box Model & CSS Variables',
            'description' => 'Deep dive into Content, Padding, Border, Margin, box-sizing: border-box, and modern CSS Custom Properties (:root variables).',
            'video_url'   => 'https://drive.google.com/file/d/1RJ02YpR994wXDkAB8VGZQMZAD97xMBeB/view?usp=drive_link',
            'content'     => "<h2>Week 2.1: The CSS Box Model & Custom Properties</h2>
<p>Every element in CSS is a rectangular box. Mastering the Box Model is the foundation of professional web layout design.</p>

<h3>1. The 4 Layers of the Box Model</h3>
<ol>
    <li><strong>Content:</strong> The actual text, image, or video.</li>
    <li><strong>Padding:</strong> Space inside the border, clearing area around content.</li>
    <li><strong>Border:</strong> A border that goes around the padding and content.</li>
    <li><strong>Margin:</strong> Space outside the border, separating elements from each other.</li>
</ol>

<h3>2. The Universal Box-Sizing Reset</h3>
<pre><code>*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}</code></pre>

<h3>3. Defining CSS Variables (Custom Properties)</h3>
<pre><code>:root {
    --primary-color: #1b2299;
    --secondary-color: #e4306d;
    --accent-color: #f7de7a;
    --font-main: 'Inter', sans-serif;
    --spacing-md: 16px;
}

.button {
    background-color: var(--primary-color);
    color: #ffffff;
    padding: var(--spacing-md);
    border-radius: 8px;
}</code></pre>",
            'duration'    => 35
        ],
        [
            'title'       => 'Week 2.2 — Flexbox for the Navbar',
            'description' => 'One-dimensional layout mastery: building responsive navigation bars, space distribution, alignment, and mobile layout toggles.',
            'video_url'   => 'https://drive.google.com/file/d/1v73y9QuCRWXNxNbw8p44Bgiq7spKDvSJ/view?usp=drive_link',
            'content'     => "<h2>Week 2.2: Building Responsive Navigation with Flexbox</h2>
<p>CSS Flexbox (Flexible Box Layout) is designed for laying out items in a single dimension — either as a row or a column.</p>

<h3>Essential Flexbox Properties for Navbars</h3>
<ul>
    <li><code>display: flex;</code> — Activates the flex formatting context.</li>
    <li><code>justify-content: space-between;</code> — Pushes brand logo to the left and navigation links to the right.</li>
    <li><code>align-items: center;</code> — Vertically centers all items along the cross axis.</li>
    <li><code>gap: 20px;</code> — Clean spacing between navigation items without margin hacks.</li>
</ul>

<h3>Navbar Implementation Example</h3>
<pre><code>.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 32px;
    background-color: #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.nav-links {
    display: flex;
    gap: 24px;
    list-style: none;
}

.nav-links a {
    text-decoration: none;
    color: #333333;
    font-weight: 600;
    transition: color 0.2s ease;
}

.nav-links a:hover {
    color: #e4306d;
}</code></pre>",
            'duration'    => 30
        ],
        [
            'title'       => 'Week 2.3 — CSS Grid for Content Layout',
            'description' => 'Two-dimensional grid layouts: grid-template-columns, auto-fit, minmax(), fractional units (fr), and responsive card galleries.',
            'video_url'   => 'https://drive.google.com/file/d/1cpc5xoaMjaQ6aokkdKs_MLRF8BPfsUh3/view?usp=drive_link',
            'content'     => "<h2>Week 2.3: Mastering Two-Dimensional CSS Grid</h2>
<p>CSS Grid Layout is the most powerful layout system available in CSS. It handles both columns and rows simultaneously.</p>

<h3>1. Responsive Card Grid without Media Queries</h3>
<p>Using <code>repeat(auto-fit, minmax(280px, 1fr))</code> creates a fluid grid that automatically adjusts column count based on screen width!</p>

<pre><code>.course-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    padding: 24px;
}

.course-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.course-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}</code></pre>

<h3>Flexbox vs. Grid Rule of Thumb</h3>
<ul>
    <li>Use <strong>Flexbox</strong> when you need 1D alignment (navbars, button groups, form inputs).</li>
    <li>Use <strong>CSS Grid</strong> when you need 2D layout control (page structures, card galleries, dashboard layouts).</li>
</ul>",
            'duration'    => 40
        ],
    ];

    // Clean up existing lessons in this module and re-populate accurately
    Lesson::where('module_id', $module->id)->delete();

    $lessonOrder = 1;
    $createdLessons = [];

    foreach ($lessonsData as $lData) {
        $lessonData = [
            'course_id'   => $course->id,
            'module_id'   => $module->id,
            'title'       => $lData['title'],
            'description' => $lData['description'],
            'video_url'   => $lData['video_url'],
            'content'     => $lData['content'],
            'order'       => $lessonOrder++,
        ];

        if (Schema::hasColumn('lessons', 'duration_minutes')) {
            $lessonData['duration_minutes'] = $lData['duration'];
        }

        $lesson = Lesson::create($lessonData);
        $createdLessons[] = $lesson;
        logMsg($log, "Created Lesson: '{$lesson->title}' (ID: {$lesson->id})", 'success');
    }

    // 4. Attach Module Material / PDF Companion Guide
    ModuleMaterial::where('module_id', $module->id)->delete();
    $material = ModuleMaterial::create([
        'module_id'   => $module->id,
        'title'       => 'Week 2 Complete Companion Guide (PDF)',
        'type'        => 'document',
        'url_or_path' => 'https://drive.google.com/file/d/1-ll6SLd3mf-_skJONQmeSqnYPyJOj75C/view?usp=drive_link',
        'file_name'   => 'week2-complete-companion-guide.pdf',
    ]);
    logMsg($log, "Attached Material: '{$material->title}' ({$material->file_name})", 'success');

    // 5. Create End-of-Module Assessment Quiz on Lesson 4
    $lastLesson = end($createdLessons);
    $quiz = Quiz::create([
        'lesson_id'          => $lastLesson->id,
        'title'              => 'Module 3 Assessment: CSS Mastery (Box Model, Flexbox & Grid)',
        'description'        => 'Test your understanding of the CSS Box Model, CSS Variables, Flexbox navigation layouts, and CSS Grid systems. Passing score is 70%.',
        'is_published'       => 1,
        'time_limit_seconds' => 600, // 10 minutes
    ]);
    logMsg($log, "Created Assessment Quiz: '{$quiz->title}' (ID: {$quiz->id})", 'success');

    // 6. Add High-Quality Assessment Questions
    $questions = [
        [
            'text'    => 'Which CSS property configuration ensures padding and border are included in an element\'s total width and height?',
            'type'    => 'multiple_choice',
            'options' => [
                'box-sizing: border-box;',
                'box-sizing: content-box;',
                'display: flex;',
                'margin: 0 auto;'
            ],
            'correct' => 'box-sizing: border-box;'
        ],
        [
            'text'    => 'How do you access a CSS Custom Property (Variable) named --primary-color in a CSS declaration?',
            'type'    => 'multiple_choice',
            'options' => [
                'var(--primary-color)',
                '$primary-color',
                'css(--primary-color)',
                '@primary-color'
            ],
            'correct' => 'var(--primary-color)'
        ],
        [
            'text'    => 'In CSS Flexbox, which property is used to align items along the primary axis (e.g. horizontally across a navbar)?',
            'type'    => 'multiple_choice',
            'options' => ['justify-content', 'align-items', 'flex-direction', 'align-content'],
            'correct' => 'justify-content'
        ],
        [
            'text'    => 'In CSS Grid, repeat(auto-fit, minmax(280px, 1fr)) automatically creates a responsive multi-column layout without media queries.',
            'type'    => 'true_false',
            'options' => ['True', 'False'],
            'correct' => 'True'
        ],
        [
            'text'    => 'What is the primary difference between CSS Flexbox and CSS Grid?',
            'type'    => 'multiple_choice',
            'options' => [
                'Flexbox is 1-dimensional (row or column), whereas CSS Grid is 2-dimensional (rows and columns simultaneously)',
                'Flexbox only works for text, Grid only works for images',
                'Grid requires JavaScript, Flexbox does not',
                'There is no difference'
            ],
            'correct' => 'Flexbox is 1-dimensional (row or column), whereas CSS Grid is 2-dimensional (rows and columns simultaneously)'
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
    logMsg($log, "Added " . count($questions) . " Questions to Module Assessment Quiz.", 'success');

    // 7. Create Practical Project Assignment (Task)
    $task = Task::create([
        'lesson_id'    => $lastLesson->id,
        'title'        => 'Hands-on Project: Build a Responsive Webpage with Flexbox & CSS Grid',
        'description'  => 'Create a web page using CSS Variables for colors, a responsive Flexbox navbar, and a responsive CSS Grid card section. Submit your GitHub repository or CodePen link for peer review.',
        'type'         => 'link',
        'is_required'  => true,
    ]);
    logMsg($log, "Created Practical Task: '{$task->title}' (ID: {$task->id})", 'success');

    // Recalculate total course duration
    $totalMinutes = 180;
    if (Schema::hasColumn('lessons', 'duration_minutes')) {
        $totalMinutes = (int) $course->lessons()->sum('duration_minutes');
    }
    $course->update(['duration_minutes' => max(60, $totalMinutes)]);
    logMsg($log, "Updated total course duration: {$totalMinutes} minutes", 'success');

    DB::commit();
    logMsg($log, "🎉 'Module 3: Introduction to CSS' updated successfully with all 4 lessons, companion PDF, quiz, and project task!", 'success');

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
    <title>Module 3: Introduction to CSS — Learnerium</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-[#1b2299] to-[#e4306d] flex items-center justify-center text-xl font-bold">L</div>
            <div>
                <h1 class="text-xl font-bold">Learnerium Curriculum Updater</h1>
                <p class="text-xs text-gray-400">Module 3: Introduction to CSS</p>
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
            <a href="/courses" class="bg-gradient-to-r from-[#1b2299] to-[#e4306d] text-white text-sm font-bold px-6 py-2.5 rounded-xl transition shadow hover:opacity-90">View Courses →</a>
        </div>
    </div>
</body>
</html>
<?php endif; ?>
