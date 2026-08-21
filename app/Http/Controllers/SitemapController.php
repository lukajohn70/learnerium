<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate the XML sitemap for public pages and published courses.
     */
    public function index(): Response
    {
        $courses = Course::whereNotNull('published_at')
            ->select('slug', 'updated_at', 'published_at')
            ->latest('published_at')
            ->get();

        $staticPages = [
            ['url' => url('/'),               'priority' => '1.0',  'changefreq' => 'weekly'],
            ['url' => url('/courses'),         'priority' => '0.9',  'changefreq' => 'daily'],
            ['url' => url('/instructors'),     'priority' => '0.7',  'changefreq' => 'weekly'],
            ['url' => url('/about'),           'priority' => '0.6',  'changefreq' => 'monthly'],
            ['url' => url('/contact'),         'priority' => '0.5',  'changefreq' => 'monthly'],
            ['url' => url('/privacy-policy'),  'priority' => '0.3',  'changefreq' => 'yearly'],
            ['url' => url('/terms-of-service'),'priority' => '0.3',  'changefreq' => 'yearly'],
        ];

        $content = view('sitemap', compact('courses', 'staticPages'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
            'X-Robots-Tag'  => 'noindex',
        ]);
    }
}
