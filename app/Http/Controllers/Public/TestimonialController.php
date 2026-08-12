<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\SeoService;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::active()->ordered()->paginate(9);

        $seo = SeoService::forPage([
            'title' => 'Testimoni',
            'description' => 'Baca pengalaman dan ulasan pelanggan yang telah merasakan layanan perawatan tubuh dan kecantikan di Dian Mustika.',
            'robots' => 'index, follow',
            'schema' => [
                SeoService::breadcrumbs([
                    ['label' => 'Beranda', 'url' => route('home')],
                    ['label' => 'Testimoni', 'url' => route('testimonials.index')],
                ]),
            ],
        ]);

        return view('pages.testimonials.index', compact('testimonials', 'seo'));
    }
}
