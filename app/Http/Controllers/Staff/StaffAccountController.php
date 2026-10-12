<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffAccountController extends Controller
{
    /**
     * List all staff/supervisor accounts.
     */
    public function index()
    {
        $accounts = User::orderBy('role')->orderBy('name')->get();

        return view('staff.accounts.index', compact('accounts'));
    }

    /**
     * Show the form to create a new staff or supervisor account.
     */
    public function create()
    {
        return view('staff.accounts.create');
    }

    /**
     * Store a newly created staff/supervisor account.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'role' => 'required|in:staff,supervisor',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'must_change_password' => true,
        ]);

        AuditLog::record('account_created', null, $validated['email'] . ' (' . $validated['role'] . ')');

        return redirect()->route('staff.accounts.index')
            ->with('success', 'Staff account created successfully.');
    }

    /**
     * Supervisor sets a new temporary password for an account (used when
     * a staff member forgets their password). They must change it again
     * at their next login.
     */
    public function resetPassword(Request $request, User $account)
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $account->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => true,
        ]);

        AuditLog::record('password_reset_by_supervisor', null, $account->email . ' (' . $account->role . ')');

        return redirect()->route('staff.accounts.index')
            ->with('success', 'Password reset for ' . $account->name . '. They must set a new one at next login.');
    }

    /**
     * Remove a staff/supervisor account.
     *
     * A supervisor cannot delete their own account while logged in,
     * to avoid accidentally locking themselves out.
     */
    public function destroy(Request $request, User $account)
    {
        if ($account->id === $request->user()->id) {
            return redirect()->route('staff.accounts.index')
                ->with('error', 'You cannot delete your own account while logged in.');
        }

        AuditLog::record('account_removed', null, $account->email . ' (' . $account->role . ')');

        $account->delete();

        return redirect()->route('staff.accounts.index')
            ->with('success', 'Staff account removed.');
    }
}
