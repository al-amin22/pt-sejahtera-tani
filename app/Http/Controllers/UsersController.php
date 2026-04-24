<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderBy('name')
            ->get();

        return View::make('users.index', compact('users'));
    }

    public function exportCsv()
    {
        $fileName = 'users-' . date('Ymd-His') . '.csv';

        $users = User::query()
            ->orderBy('name')
            ->get(['name', 'email', 'role', 'created_at']);

        $callback = function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama', 'Email', 'Role', 'Dibuat']);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return Response::streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,staff,finance'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return \Illuminate\Support\Facades\Redirect::route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show(User $id)
    {
        return View::make('users.show', [
            'user' => $id,
        ]);
    }

    public function update(Request $request, User $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $id->id],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,staff,finance'],
        ]);

        $id->name = $validated['name'];
        $id->email = $validated['email'];
        $id->role = $validated['role'];

        if (! empty($validated['password'])) {
            $id->password = Hash::make($validated['password']);
        }

        $id->save();

        return \Illuminate\Support\Facades\Redirect::route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $id)
    {
        if (Auth::id() === $id->id) {
            return \Illuminate\Support\Facades\Redirect::route('users.index')
                ->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $id->delete();

        return \Illuminate\Support\Facades\Redirect::route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
