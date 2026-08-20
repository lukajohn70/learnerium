<?php
/**
 * Learnerium - Auto-Importer Script for "Introduction to HTML, CSS and JavaScript"
 * 
 * Run locally via CLI: /Applications/MAMP/bin/php/php8.3.30/bin/php import_gdrive_course.php
 * Or online via browser: https://learnerium.jlm.com.ng/import_gdrive_course.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\ModuleMaterial;
use Illuminate\Support\Str;

echo "<h1>🚀 Learnerium Auto-Importer: Introduction to HTML, CSS and JavaScript</h1>";

// 1. Find or create instructor user
$instructor = User::where('role', 'instructor')->first() ?? User::first();

if (!$instructor) {
    die("❌ Error: No user found in database. Please register a user first.");
}

echo "<p>👤 Assigning Course Instructor: <strong>{$instructor->name}</strong> ({$instructor->email})</p>";

// 2. Create or Update Course
$courseTitle = "Introduction to HTML, CSS and JavaScript";
$courseSlug = Str::slug($courseTitle);

$course = Course::updateOrCreate(
    ['slug' => $courseSlug],
    [
        'instructor_id' => $instructor->id,
        'title' => $courseTitle,
        'description' => "Comprehensive beginner course covering development environment setup, VS Code, Git, GitHub repository workflows, web mechanics, semantic HTML, and building interactive web forms.",
        'category' => "Web Development & Programming",
        'level' => "Beginner",
        'price' => 0.00,
        'duration_minutes' => 180,
        'thumbnail' => "https://images.unsplash.com/photo-1593720213428-28a5b9e94613?w=800&auto=format&fit=crop&q=80",
        'published_at' => now(),
    ]
);

echo "<p>✅ Course Created / Updated: <strong>{$course->title}</strong> (ID: {$course->id})</p>";

// 3. Define Modules, Lessons, and Materials structure from Google Drive
$courseStructure = [
    [
        'title' => '00. Setting Up',
        'order' => 1,
        'materials' => [
            [
                'title' => 'Student Setup Guide (PDF)',
                'type' => 'document',
                'url_or_path' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
            ]
        ],
        'lessons' => [
            [
                'title' => '1. Setting up - Installing VS Code and Live Server',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Learn how to download, install, and configure Visual Studio Code with the Live Server extension for real-time web preview.',
            ],
            [
                'title' => '2. Setting Up - Creating a GitHub Repo',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Step-by-step guide to initializing a GitHub account and creating your first public code repository.',
            ],
            [
                'title' => '3. Setting Up - Setting Up the Workspace',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Organizing project directories, folder layout, and essential workspace preferences for web development.',
            ],
            [
                'title' => '4. Setting Up - Installing Git',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Installing Git version control system on your computer and configuring global username/email credentials.',
            ],
        ]
    ],
    [
        'title' => '01. Week 1: HTML & Web Mechanics',
        'order' => 2,
        'materials' => [
            [
                'title' => 'Week 1 Complete Companion Guide (PDF)',
                'type' => 'document',
                'url_or_path' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
            ]
        ],
        'lessons' => [
            [
                'title' => 'Week 1 - Segment 1.1 - Web Mechanics',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Understanding clients, servers, HTTP requests, responses, and how browsers render HTML web pages.',
            ],
            [
                'title' => 'Week 1 - Segment 1.2 - HTML Boiler Plate',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Writing standard DOCTYPE html, head, body structure and essential meta tag configurations.',
            ],
            [
                'title' => 'Week 1 - Segment 1.3 - Semantic HTML',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Utilizing semantic HTML elements such as header, nav, main, section, article, and footer for accessible web markup.',
            ],
            [
                'title' => 'Week 1 - Segment 1.4 - Contact Form',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Building interactive HTML web forms with input fields, labels, fieldsets, textareas, and submit buttons.',
            ],
            [
                'title' => 'Week 1 - Segment 1.5 - Connecting and pushing to GitHub repo',
                'video_url' => 'https://drive.google.com/file/d/1oa_0kb6l7EE-6TqcNaY9ZFzFc7XoGQfo/view',
                'description' => 'Linking your local project folder to remote GitHub repository using git init, add, commit, and push commands.',
            ],
        ]
    ]
];

// 4. Import Modules, Lessons, and Materials
foreach ($courseStructure as $modData) {
    $module = Module::updateOrCreate(
        [
            'course_id' => $course->id,
            'title' => $modData['title'],
        ],
        [
            'order' => $modData['order'],
        ]
    );

    echo "<h3>📦 Module {$module->order}: {$module->title}</h3>";

    // Import Materials
    foreach ($modData['materials'] as $matData) {
        ModuleMaterial::updateOrCreate(
            [
                'module_id' => $module->id,
                'title' => $matData['title'],
            ],
            [
                'type' => $matData['type'],
                'url_or_path' => $matData['url_or_path'],
            ]
        );
        echo "<p>📄 Attached Material: <em>{$matData['title']}</em></p>";
    }

    // Import Lessons
    foreach ($modData['lessons'] as $index => $lessData) {
        $lessonOrder = $index + 1;
        $lesson = Lesson::updateOrCreate(
            [
                'course_id' => $course->id,
                'title' => $lessData['title'],
            ],
            [
                'module_id' => $module->id,
                'video_url' => $lessData['video_url'],
                'description' => $lessData['description'],
                'content' => "<p>{$lessData['description']}</p>",
                'order' => $lessonOrder,
            ]
        );
        echo "<p>🎬 Lesson {$lessonOrder}: <strong>{$lesson->title}</strong></p>";
    }
}

echo "<hr><h2 style='color: green;'>🎉 Course Import Complete!</h2>";
echo "<p><a href='/courses/{$course->slug}' target='_blank'>👉 Click here to view imported Course on Learnerium</a></p>";
