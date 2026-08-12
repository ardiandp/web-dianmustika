<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::active()->ordered()->paginate(9);

        return view('pages.testimonials.index', compact('testimonials'));
    }
}
