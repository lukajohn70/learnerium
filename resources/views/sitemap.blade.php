<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    {{-- Static Pages --}}
    @foreach($staticPages as $page)
    <url>
        <loc>{{ $page['url'] }}</loc>
        <changefreq>{{ $page['changefreq'] }}</changefreq>
        <priority>{{ $page['priority'] }}</priority>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
    </url>
    @endforeach

    {{-- Published Courses --}}
    @foreach($courses as $course)
    <url>
        <loc>{{ url('/courses/' . $course->slug) }}</loc>
        <lastmod>{{ ($course->updated_at ?? $course->published_at)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

</urlset>
