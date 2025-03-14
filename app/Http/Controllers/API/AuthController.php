<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // **Register User**
public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'role' => 'nullable|in:user,admin,stafkeuangan,owner',
        'status' => 'nullable|boolean',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        // Simpan gambar profil jika tersedia
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Buat user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_picture' => $profilePicturePath,
            'role' => $request->role ?? 'user', // Default role user jika tidak diisi
            'status' => $request->status ?? true, // Default status aktif jika tidak diisi
        ]);

        // Generate token untuk user baru
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi berhasil!',
            'user' => $user,
            'token' => $token
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat registrasi',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // **Login User**
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Hapus token lama sebelum membuat token baru
        $user->tokens()->delete();
        $token = $user->createToken('API Token')->plainTextToken;

        // Simpan token baru di database
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil!',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    // **Logout User**
    public function logout(Request $request)
{
    if ($request->user()) {
        // Hapus token di Laravel Sanctum
        $request->user()->tokens()->delete();

        // Hapus token API dari database
        $request->user()->api_token = null;
        $request->user()->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil!'
        ], 200);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Anda tidak sedang login'
    ], 401);
}

    // **Get User Info**
    public function profil(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'User ditemukan!',
            'user' => $request->user()
        ], 200);
    }
    
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
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'updated_at' => now(),
        ];

        // Update password hanya jika diisi
        if ($request->password) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
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
