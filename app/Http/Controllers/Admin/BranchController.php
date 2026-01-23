<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::query()
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.branches.index', [
            'branches' => $branches,
        ]);
    }

    public function create(): View
    {
        return view('admin.branches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'open_hours' => ['required', 'string', 'max:255'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $branch = Branch::create($data);

        return redirect()
            ->route('admin.branches.edit', $branch)
            ->with('success', 'Cabang berhasil dibuat.');
    }

    public function edit(Branch $branch): View
    {
        return view('admin.branches.edit', [
            'branch' => $branch,
        ]);
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'open_hours' => ['required', 'string', 'max:255'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $branch->update($data);

        return back()->with('success', 'Cabang berhasil diupdate.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()
            ->route('admin.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}

