<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $branches = Branch::query()
            ->where('is_active', true)
            ->with(['activePrice'])
            ->orderBy('name')
            ->get();

        return view('public.home', [
            'branches' => $branches,
        ]);
    }
}

