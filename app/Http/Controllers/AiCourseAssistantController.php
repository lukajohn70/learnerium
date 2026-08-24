<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCourseAssistantController extends Controller
{
    /**
     * Handle AI generation requests for the course builder.
     * Actions: description | outcomes | requirements | outline | lesson_notes | transcript | task_prompt
     */
    public function generate(Request $request)
    {
        $request->validate([
            'action'       => 'required|in:description,outcomes,requirements,outline,lesson_notes,transcript,task_prompt',
            'title'        => 'required|string|max:300',
            'level'        => 'nullable|string|max:100',
            'category'     => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:2000',
            'lesson_title' => 'nullable|string|max:300',
        ]);

        $apiKey = config('services.gemini.api_key')
            ?: (env('GEMINI_API_KEY')
            ?: (\App\Models\PlatformSetting::get('gemini_api_key')));

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Gemini API key is not configured. Please add GEMINI_API_KEY to your .env file or in Admin Dashboard -> Settings.',
            ], 503);
        }

        $action      = $request->input('action');
        $title       = $request->input('title');
        $level       = $request->input('level', 'Beginner');
        $category    = $request->input('category', 'Online Course');
        $description = $request->input('description', '');
        $lessonTitle = $request->input('lesson_title', '');

        $prompt = match ($action) {
            'description' => "You are an expert e-learning curriculum designer and copywriter. Write a compelling, high-converting, professional course overview/description (3 concise paragraphs, ~150-180 words) for an online course titled: \"{$title}\". Level: {$level}. Category: {$category}. Make it engaging, benefit-driven, and clear. Return ONLY the plain description text with no Markdown headings, no asterisks, no quotes.",

            'outcomes' => "You are an expert instructional designer. List 6-8 specific, actionable learning outcomes for an online course titled: \"{$title}\". Level: {$level}. Category: {$category}." . ($description ? " Overview: {$description}" : "") . " Format: Return ONLY a valid JSON array of strings. Example: [\"Understand core concepts of {$title}\", \"Build real-world projects\", \"Apply best practices\"]. Do not wrap in markdown code blocks.",

            'requirements' => "You are an expert course designer. List 4-6 realistic prerequisites and requirements for students enrolling in: \"{$title}\". Level: {$level}. Category: {$category}. Format: Return ONLY a valid JSON array of strings. Example: [\"Basic computer skills\", \"A stable internet connection\"]. Do not wrap in markdown code blocks.",

            'outline' => "You are an expert instructional designer. Create a structured curriculum outline for: \"{$title}\". Level: {$level}. Category: {$category}." . ($description ? " Overview: {$description}" : "") . " Generate 4-6 modules, each with 3-5 lesson titles. Format: Return ONLY a valid JSON array of module objects. Example: [{\"module\": \"Module 1: Getting Started\", \"lessons\": [\"Lesson 1: Introduction\", \"Lesson 2: Development Setup\"]}]. Do not wrap in markdown code blocks.",

            'lesson_notes' => "You are an expert educator. Write comprehensive, well-structured lesson study notes and summary for a lesson titled: \"" . ($lessonTitle ?: $title) . "\" in the course \"{$title}\". Include an overview, 3-4 key concepts explained clearly with bullet points, and practical takeaways. Format cleanly in HTML (<p>, <h3>, <ul>, <li>, <strong>). Return only clean HTML markup.",

            'transcript' => "You are an expert video lecture presenter. Write an engaging, conversational, word-for-word spoken transcript and lecture script for a lesson titled: \"" . ($lessonTitle ?: $title) . "\" in the course \"{$title}\". Make it instructional, enthusiastic, and paced for high student retention.",

            'task_prompt' => "You are an instructional designer. Create a practical, engaging student assignment for the lesson \"" . ($lessonTitle ?: $title) . "\" in the course \"{$title}\". Include: 1. Objective, 2. Step-by-Step Instructions, 3. Submission Deliverable, 4. Success Criteria.",
        };

        // Active, ultra-fast Google Gemini models (benchmarked < 1.5s response time)
        $modelsToTry = [
            'gemini-3.5-flash-lite',
            'gemini-3.5-flash',
            'gemini-flash-latest',
            'gemini-3.6-flash',
            'gemini-3.7-flash',
        ];

        $lastError = 'Unable to connect to Gemini API.';
        $successResponse = null;

        foreach ($modelsToTry as $tryModel) {
            try {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$tryModel}:generateContent?key={$apiKey}";
                $res = Http::withoutVerifying()
                    ->timeout(12)
                    ->post($endpoint, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature'     => 0.7,
                            'maxOutputTokens' => 1500,
                        ],
                    ]);

                if ($res->successful()) {
                    $successResponse = $res;
                    break;
                } else {
                    $err = $res->json()['error']['message'] ?? ('HTTP ' . $res->status());
                    $lastError = "{$tryModel}: {$err}";
                }
            } catch (\Throwable $ex) {
                $lastError = "{$tryModel} exception: " . $ex->getMessage();
            }
        }

        if (!$successResponse) {
            Log::error("Gemini API generation failed. Last error: {$lastError}");
            return response()->json([
                'success' => false,
                'message' => 'AI generation could not complete: ' . $lastError,
            ], 502);
        }

        $body = $successResponse->json();
        $parts = $body['candidates'][0]['content']['parts'] ?? [];
        $text = '';
        foreach ($parts as $part) {
            if (!empty($part['text'])) {
                $text .= $part['text'];
            }
        }

        if (empty(trim($text))) {
            return response()->json([
                'success' => false,
                'message' => 'AI returned an empty response. Please try again.',
            ], 500);
        }

        // For JSON actions, safely parse and return clean array
        if (in_array($action, ['outcomes', 'requirements', 'outline'])) {
            $cleaned = preg_replace('/^```(?:json)?\s*/m', '', $text);
            $cleaned = preg_replace('/\s*```$/m', '', $cleaned);
            $cleaned = trim($cleaned);

            $parsed = json_decode($cleaned, true);

            // Fallback 1: Try regex extraction if direct parse fails
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($parsed)) {
                if (preg_match('/\[\s*\{.*\}\s*\]/s', $text, $matches)) {
                    $parsed = json_decode($matches[0], true);
                } elseif (preg_match('/\[\s*".*"\s*\]/s', $text, $matches)) {
                    $parsed = json_decode($matches[0], true);
                }
            }

            // Fallback 2 for list items: if Gemini returned line-by-line bullets
            if (!is_array($parsed) && in_array($action, ['outcomes', 'requirements'])) {
                $lines = array_filter(array_map(function ($line) {
                    return trim(preg_replace('/^(\d+\.|\-|\*|•)\s*/', '', trim($line)));
                }, explode("\n", $text)));
                if (!empty($lines)) {
                    $parsed = array_values($lines);
                }
            }

            if (!is_array($parsed) || empty($parsed)) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI returned text that could not be parsed into a structured list. Please try again.',
                    'raw'     => $text,
                ], 422);
            }

            return response()->json([
                'success' => true,
                'action'  => $action,
                'data'    => $parsed,
            ]);
        }

        // For plain text / HTML actions
        return response()->json([
            'success' => true,
            'action'  => $action,
            'data'    => trim($text),
        ]);
    }
}
