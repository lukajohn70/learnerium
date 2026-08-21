<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display student wishlist.
     */
    public function index()
    {
        $user = Auth::user();
        $wishlistCourses = $user->wishlist()->with('instructor')->get();

        return view('student.wishlist', compact('wishlistCourses'));
    }

    /**
     * Toggle course in wishlist (add if not present, remove if present).
     */
    public function toggle(Request $request, Course $course)
    {
        $user = Auth::user();

        if ($user->inWishlist($course->id)) {
            $user->wishlist()->detach($course->id);
            $added = false;
            $msg = 'Removed from Wishlist';
        } else {
            $user->wishlist()->attach($course->id);
            $added = true;
            $msg = 'Added to Wishlist';
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $msg,
                'wishlist_count' => $user->wishlist()->count()
            ]);
        }

        return back()->with('status', $msg);
    }

    /**
     * Remove a course from wishlist.
     */
    public function destroy(Course $course)
    {
        $user = Auth::user();
        $user->wishlist()->detach($course->id);

        return back()->with('status', 'Course removed from Wishlist.');
    }

    /**
     * Move an item from wishlist to cart.
     */
    public function moveToCart(Course $course)
    {
        $user = Auth::user();

        $user->wishlist()->detach($course->id);
        if (!$user->inCart($course->id) && !$user->enrolledIn($course->id)) {
            $user->cart()->attach($course->id);
        }

        return back()->with('status', 'Moved to Cart!');
    }
}
