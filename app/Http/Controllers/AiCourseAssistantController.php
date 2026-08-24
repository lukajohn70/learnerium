<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCourseAssistantController extends Controller
{
    /**
     * Handle AI generation requests for the course builder.
     * Action: description | outcomes | requirements | outline
     */
    public function generate(Request $request)
    {
        $request->validate([
            'action'      => 'required|in:description,outcomes,requirements,outline,lesson_notes,transcript,task_prompt',
            'title'       => 'required|string|max:300',
            'level'       => 'nullable|string|max:100',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'lesson_title'=> 'nullable|string|max:300',
        ]);

        $apiKey = config('services.gemini.api_key') 
            ?: (env('GEMINI_API_KEY') 
            ?: (\App\Models\PlatformSetting::get('gemini_api_key')));
        $model  = config('services.gemini.model', 'gemini-1.5-flash');

        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Gemini API key is not configured. Please add GEMINI_API_KEY to .env or in Admin Dashboard -> Settings.',
            ], 503);
        }

        $action      = $request->input('action');
        $title       = $request->input('title');
        $level       = $request->input('level', 'Beginner');
        $category    = $request->input('category', 'Online Course');
        $description = $request->input('description', '');
        $lessonTitle = $request->input('lesson_title', '');

        $prompt = match ($action) {
            'description' => "You are an expert e-learning content writer. Write a compelling, professional course description (3-4 paragraphs, ~180 words) for an online course titled: \"{$title}\". Level: {$level}. Category: {$category}. Make it enthusiastic, benefit-focused, and SEO-friendly. Return only the description text, no headings.",

            'outcomes' => "You are an expert instructional designer. List exactly 6-8 specific, measurable learning outcomes for an online course titled: \"{$title}\". Level: {$level}. Category: {$category}." . ($description ? " Overview: {$description}" : "") . " Format: Return ONLY a JSON array of strings. Example: [\"Understand X\", \"Build Y\", \"Apply Z\"]. No other text.",

            'requirements' => "You are an expert course designer. List exactly 4-6 realistic prerequisites/requirements for students taking an online course titled: \"{$title}\". Level: {$level}. Category: {$category}. These should be prior knowledge or tools needed. Format: Return ONLY a JSON array of strings. Example: [\"Basic knowledge of HTML\", \"A computer with internet access\"]. No other text.",

            'outline' => "You are an expert instructional designer. Create a structured course outline for: \"{$title}\". Level: {$level}. Category: {$category}." . ($description ? " Overview: {$description}" : "") . " Generate 4-6 modules, each with 3-5 lesson titles. Format: Return ONLY a valid JSON array of module objects. Example: [{\"module\": \"Module 1: Introduction\", \"lessons\": [\"Lesson 1: Welcome\", \"Lesson 2: Setup\"]}, ...]. No other text.",

            'lesson_notes' => "You are an expert educator. Write comprehensive, beautifully structured lesson study notes and summary for a lesson titled: \"" . ($lessonTitle ?: $title) . "\" in the course \"{$title}\". Include an overview, 3-4 key concepts explained clearly, practical takeaways, and a quick recap. Use clear HTML formatting (<p>, <h3>, <ul>, <li>, <strong>). Return only clean HTML markup.",

            'transcript' => "You are an expert video lecture creator. Write a comprehensive, conversational, word-for-word spoken transcript and lecture script for a lesson titled: \"" . ($lessonTitle ?: $title) . "\" in the course \"{$title}\". Make it engaging, instructional, clear, and paced like a top Udemy/Coursera instructor explaining step-by-step. Format with speaker cues and paragraphs.",

            'task_prompt' => "You are an instructional designer. Create a practical, engaging student assignment and task instructions for the lesson \"" . ($lessonTitle ?: $title) . "\" in the course \"{$title}\". Detail: Task Objective, Step-by-Step Instructions, What to Submit, and Evaluation Criteria.",
        };


        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature'     => 0.7,
                        'maxOutputTokens' => 1024,
                    ],
                ]);

            if ($response->failed()) {
                $errMsg = $response->json()['error']['message'] ?? 'Gemini API request failed.';
                Log::error("Gemini API error: {$errMsg}");
                return response()->json(['success' => false, 'message' => $errMsg], 502);
            }

            $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';

            // For JSON actions, parse and return clean array
            if (in_array($action, ['outcomes', 'requirements', 'outline'])) {
                $text = preg_replace('/^```(?:json)?\s*/m', '', $text);
                $text = preg_replace('/\s*```$/m', '', $text);
                $text = trim($text);
                $parsed = json_decode($text, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json(['success' => false, 'message' => 'AI returned invalid JSON. Please try again.'], 422);
                }
                return response()->json(['success' => true, 'action' => $action, 'data' => $parsed]);
            }

            return response()->json(['success' => true, 'action' => $action, 'data' => trim($text)]);

        } catch (\Exception $e) {
            Log::error('Gemini generation exception: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'AI request failed: ' . $e->getMessage()], 500);
        }
    }
}
