<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InstructorCouponController extends Controller
{
    /**
     * Display a listing of coupons for courses taught by the instructor.
     */
    public function index()
    {
        $user = Auth::user();
        $myCourseIds = $user->coursesTaught()->pluck('id');
        $myCourses   = $user->coursesTaught()->orderBy('title')->get();

        $coupons = Coupon::whereIn('course_id', $myCourseIds)
            ->with('course')
            ->latest()
            ->get();

        return view('instructor.coupons', compact('coupons', 'myCourses'));
    }

    /**
     * Store a newly created coupon for an instructor course.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $myCourseIds = $user->coursesTaught()->pluck('id')->toArray();

        $request->validate([
            'code'           => 'required|string|max:50|unique:coupons,code',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'course_id'      => 'required|in:' . implode(',', $myCourseIds),
            'max_uses'       => 'nullable|integer|min:1',
            'expires_at'     => 'nullable|date|after:today',
        ]);

        Coupon::create([
            'code'           => strtoupper(trim($request->code)),
            'discount_type'  => $request->discount_type,
            'discount_value' => $request->discount_value,
            'course_id'      => $request->course_id,
            'max_uses'       => $request->max_uses ?: null,
            'used_count'     => 0,
            'active'         => true,
            'expires_at'     => $request->expires_at ?: null,
        ]);

        return back()->with('status', "Coupon \"{$request->code}\" created successfully!");
    }

    /**
     * Delete a coupon.
     */
    public function destroy(Coupon $coupon)
    {
        $user = Auth::user();
        $myCourseIds = $user->coursesTaught()->pluck('id')->toArray();

        if (!in_array($coupon->course_id, $myCourseIds)) {
            abort(403, 'Unauthorized to delete this coupon.');
        }

        $code = $coupon->code;
        $coupon->delete();

        return back()->with('status', "Coupon \"{$code}\" deleted successfully.");
    }
}
