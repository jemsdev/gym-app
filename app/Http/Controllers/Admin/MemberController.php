<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $members = User::query()
            ->where('role', User::ROLE_MEMBER)
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.members.index', [
            'members' => $members,
        ]);
    }

    public function create(): View
    {
        return view('admin.members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $plainPassword = Str::random(12);

        $member = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'password' => Hash::make($plainPassword),
            'role' => User::ROLE_MEMBER,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.members.index')
            ->with('success', "Member berhasil dibuat");
    }

    public function edit(User $member): View
    {
        abort_unless($member->role === User::ROLE_MEMBER, 404);

        return view('admin.members.edit', [
            'member' => $member,
        ]);
    }

    public function update(Request $request, User $member): RedirectResponse
    {
        abort_unless($member->role === User::ROLE_MEMBER, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($member->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $update = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];

        $member->update($update);

        return back()->with('success', 'Member berhasil diupdate.');
    }

    public function destroy(User $member): RedirectResponse
    {
        abort_unless($member->role === User::ROLE_MEMBER, 404);

        $member->delete();

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member berhasil dihapus.');
    }
}

