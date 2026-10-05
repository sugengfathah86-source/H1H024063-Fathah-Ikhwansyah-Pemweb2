<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    private function dataPengguna(User $pengguna): array
    {
        return [
            'id'             => $pengguna->id,
            'nama'           => $pengguna->name,
            'email'          => $pengguna->email,
            'peran'          => $pengguna->peran,
            'terakhir_login' => $pengguna->terakhir_login?->toIso8601String(),
        ];
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['peran'] = 'mahasiswa';

        $pengguna = User::create($data);

        // Dibatasi hanya baca; tanpa daftar ini token otomatis berkemampuan '*'
        $token = $pengguna->createToken('token-perangkat', ['mahasiswa:baca'])->plainTextToken;

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Registrasi berhasil',
            'data'   => [
                'pengguna' => $this->dataPengguna($pengguna),
                'token'    => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $pengguna = User::where('email', $data['email'])->first();

        if ($pengguna === null || Hash::check($data['password'], $pengguna->password) === false) {
            return response()->json([
                'sukses' => false,
                'pesan'  => 'Email atau kata sandi tidak sesuai',
            ], 401);
        }

        // Tugas 3: catat waktu login terakhir
        $pengguna->forceFill(['terakhir_login' => now()])->save();

        $kemampuan = $pengguna->peran === 'admin'
            ? ['mahasiswa:baca', 'mahasiswa:tulis']
            : ['mahasiswa:baca'];

        $token = $pengguna->createToken('token-perangkat', $kemampuan)->plainTextToken;

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Login berhasil',
            'data'   => [
                'pengguna' => $this->dataPengguna($pengguna),
                'token'    => $token,
            ],
        ]);
    }

    public function profil(Request $request): JsonResponse
    {
        $pengguna = $request->user();

        return response()->json([
            'sukses' => true,
            'data'   => $this->dataPengguna($pengguna) + [
                'kemampuan' => $pengguna->currentAccessToken()->abilities,
            ],
        ]);
    }

    // Tugas 1: ubah kata sandi dengan verifikasi kata sandi lama
    public function updatePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $pengguna = $request->user();

        if (Hash::check($data['password_lama'], $pengguna->password) === false) {
            return response()->json([
                'sukses' => false,
                'pesan'  => 'Kata sandi lama tidak sesuai',
            ], 400);
        }

        $pengguna->password = Hash::make($data['password_baru']);
        $pengguna->save();

        // Akhiri sesi di perangkat lain; token yang sedang dipakai tetap berlaku
        $pengguna->tokens()
            ->where('id', '!=', $pengguna->currentAccessToken()->id)
            ->delete();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Kata sandi berhasil diubah',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Logout berhasil',
        ]);
    }

    public function logoutSemua(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'sukses' => true,
            'pesan'  => 'Seluruh sesi perangkat telah diakhiri',
        ]);
    }
}