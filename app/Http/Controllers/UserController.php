<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing password
            'email_verified_at' => null, // Default NULL
            'api_token' => Str::random(60), // Generate API token
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail user tertentu.
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Menyimpan perubahan data user.
     */
    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'updated_at' => now(),
    ];

    // Update password jika diisi
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // Cek apakah ada gambar baru diupload
    if ($request->hasFile('profile_picture')) {
        // Hapus foto lama jika ada
        if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
            Storage::delete('public/' . $user->profile_picture);
        }

        // Simpan gambar baru
        $file = $request->file('profile_picture');
        $path = $file->store('profile_pictures', 'public');
        $data['profile_picture'] = $path;
    }

    $user->update($data);

    return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
}

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }
}
