<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BranchPriceController extends Controller
{
    public function index(Branch $branch): View
    {
        $prices = BranchPrice::query()
            ->where('branch_id', $branch->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.branches.prices.index', [
            'branch' => $branch,
            'prices' => $prices,
        ]);
    }

    public function create(Branch $branch): View
    {
        return view('admin.branches.prices.create', [
            'branch' => $branch,
        ]);
    }

    public function store(Request $request, Branch $branch): RedirectResponse
    {
        $data = $request->validate([
            'daily_price' => ['required', 'numeric', 'min:0'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        DB::transaction(function () use ($branch, $data) {
            if ($data['is_active']) {
                BranchPrice::query()
                    ->where('branch_id', $branch->id)
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->update(['is_active' => false]);
            }

            BranchPrice::create([
                'branch_id' => $branch->id,
                'daily_price' => $data['daily_price'],
                'monthly_price' => $data['monthly_price'],
                'is_active' => $data['is_active'],
            ]);
        });

        return redirect()
            ->route('admin.branches.prices.index', $branch)
            ->with('success', 'Harga cabang berhasil disimpan.');
    }

    public function edit(Branch $branch, BranchPrice $price): View
    {
        abort_unless($price->branch_id === $branch->id, 404);

        return view('admin.branches.prices.edit', [
            'branch' => $branch,
            'price' => $price,
        ]);
    }

    public function update(Request $request, Branch $branch, BranchPrice $price): RedirectResponse
    {
        abort_unless($price->branch_id === $branch->id, 404);

        $data = $request->validate([
            'daily_price' => ['required', 'numeric', 'min:0'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        DB::transaction(function () use ($branch, $price, $data) {
            if ($data['is_active']) {
                BranchPrice::query()
                    ->where('branch_id', $branch->id)
                    ->where('is_active', true)
                    ->where('id', '!=', $price->id)
                    ->lockForUpdate()
                    ->update(['is_active' => false]);
            }

            $price->update([
                'daily_price' => $data['daily_price'],
                'monthly_price' => $data['monthly_price'],
                'is_active' => $data['is_active'],
            ]);
        });

        return back()->with('success', 'Harga cabang berhasil diupdate.');
    }

    public function activate(Branch $branch, BranchPrice $price): RedirectResponse
    {
        abort_unless($price->branch_id === $branch->id, 404);

        DB::transaction(function () use ($branch, $price) {
            BranchPrice::query()
                ->where('branch_id', $branch->id)
                ->where('is_active', true)
                ->lockForUpdate()
                ->update(['is_active' => false]);

            $price->update(['is_active' => true]);
        });

        return back()->with('success', 'Harga berhasil diaktifkan.');
    }
}

