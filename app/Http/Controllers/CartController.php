<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $user = Auth::user();
        $cartCourses = $user->cart()->with('instructor')->get();

        $totalPrice = $cartCourses->sum(fn($course) => (float) $course->price);

        return view('student.cart', compact('cartCourses', 'totalPrice'));
    }

    /**
     * Add a course to the cart.
     */
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();

        if ($user->enrolledIn($course->id)) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        if ($user->inCart($course->id)) {
            return back()->with('info', 'Course is already in your cart.');
        }

        $user->cart()->attach($course->id);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Course added to cart!',
                'cart_count' => $user->cart()->count()
            ]);
        }

        return back()->with('status', 'Course added to cart!');
    }

    /**
     * Remove a course from the cart.
     */
    public function destroy(Course $course)
    {
        $user = Auth::user();
        $user->cart()->detach($course->id);

        return back()->with('status', 'Course removed from cart.');
    }

    /**
     * Move an item from cart to wishlist.
     */
    public function moveToWishlist(Course $course)
    {
        $user = Auth::user();

        $user->cart()->detach($course->id);
        if (!$user->inWishlist($course->id)) {
            $user->wishlist()->attach($course->id);
        }

        return back()->with('status', 'Moved to Wishlist!');
    }
}
