<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get all cart course IDs for current user/guest and keep DB & Session in sync.
     */
    public static function getCartCourseIds(): array
    {
        $sessionIds = session('cart_course_ids', []);
        if (!is_array($sessionIds)) {
            $sessionIds = [];
        }

        if (Auth::check()) {
            $user = Auth::user();
            $dbIds = $user->cart()->pluck('courses.id')->toArray();
            
            // Merge session cart into DB cart
            $newIds = array_diff($sessionIds, $dbIds);
            if (!empty($newIds)) {
                $user->cart()->syncWithoutDetaching($newIds);
                $dbIds = array_unique(array_merge($dbIds, $newIds));
            }
            
            // Keep session synced with DB cart
            session(['cart_course_ids' => $dbIds]);
            return $dbIds;
        }

        return $sessionIds;
    }

    /**
     * Get count of items in cart (for navbar badges).
     */
    public static function getCartCount(): int
    {
        return count(self::getCartCourseIds());
    }

    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $courseIds = self::getCartCourseIds();

        $cartCourses = empty($courseIds)
            ? collect()
            : Course::whereIn('id', $courseIds)->with('instructor')->get();

        $totalPrice = $cartCourses->sum(fn($course) => (float) $course->price);

        return view('student.cart', compact('cartCourses', 'totalPrice'));
    }

    /**
     * Add a course to the cart.
     */
    public function store(Request $request, Course $course)
    {
        if (Auth::check() && Auth::user()->enrolledIn($course->id)) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        $sessionIds = session('cart_course_ids', []);
        if (!is_array($sessionIds)) {
            $sessionIds = [];
        }

        if (!in_array($course->id, $sessionIds)) {
            $sessionIds[] = $course->id;
            session(['cart_course_ids' => array_values(array_unique($sessionIds))]);
        }

        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->inCart($course->id)) {
                $user->cart()->attach($course->id);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Course added to cart!',
                'cart_count' => self::getCartCount(),
            ]);
        }

        return back()->with('status', 'Course added to cart!');
    }

    /**
     * Remove a course from the cart.
     */
    public function destroy(Course $course)
    {
        $sessionIds = session('cart_course_ids', []);
        if (is_array($sessionIds)) {
            $sessionIds = array_diff($sessionIds, [$course->id]);
            session(['cart_course_ids' => array_values($sessionIds)]);
        }

        if (Auth::check()) {
            Auth::user()->cart()->detach($course->id);
        }

        return back()->with('status', 'Course removed from cart.');
    }

    /**
     * Move an item from cart to wishlist.
     */
    public function moveToWishlist(Course $course)
    {
        $this->destroy($course);

        if (Auth::check()) {
            $user = Auth::user();
            if (!$user->inWishlist($course->id)) {
                $user->wishlist()->attach($course->id);
            }
        }

        return back()->with('status', 'Moved to Wishlist!');
    }
}
