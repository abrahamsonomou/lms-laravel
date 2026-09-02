<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Abonnement\Plan;
use Illuminate\View\View;

class PricingController extends Controller
{
    public function index(): View
    {
        $plans = Plan::query()->where('active', true)->with('devise')->orderBy('prix')->get();

        return view('public.pricing', compact('plans'));
    }
}
