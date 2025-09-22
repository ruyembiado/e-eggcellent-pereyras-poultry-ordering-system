<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'criteria.service_speed' => 'required|integer|min:1|max:5',
            'criteria.egg_quality' => 'required|integer|min:1|max:5',
            'criteria.egg_size_accuracy' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $existingRating = Rating::where('order_id', $request->order_id)->first();
        if ($existingRating) {
            return back()->with('error', 'You have already rated this order.');
        }

        Rating::create([
            'order_id' => $request->order_id,
            'service_speed' => $request->criteria['service_speed'],
            'egg_quality' => $request->criteria['egg_quality'],
            'egg_size_accuracy' => $request->criteria['egg_size_accuracy'],
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Rating submitted successfully.');
    }

    public function getTestimonials()
    {
        $testimonials = Rating::with('order.user')
            // ->orderBy('created_at', 'DESC')
            ->get()
            ->groupBy(fn($rating) => $rating->order->user->id ?? 0);

        return view('testimonial', compact('testimonials'));
    }
}
