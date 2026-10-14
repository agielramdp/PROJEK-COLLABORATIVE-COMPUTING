<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Endpoint API Login dengan validasi hash password.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'role'     => 'nullable|string',
        ], [
            'username.required' => 'Nomor identitas atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        $identifier = trim($request->input('username'));
        $password   = $request->input('password');
        $role       = $request->input('role');

        // Cari user berdasarkan email atau nomor identitas (NIM, NIDN, NIP, ID Staf)
        $userQuery = User::where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                  ->orWhere('identity_number', $identifier);
        });

        if (!empty($role)) {
            $userQuery->where('role', $role);
        }

        $user = $userQuery->first();

        // Validasi user & hash password
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor identitas/email atau kata sandi tidak cocok. Silakan periksa kembali.'
            ], 401);
        }

        // Simpan sesi login
        Auth::login($user, $request->boolean('remember'));

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil! Selamat datang kembali di SI-AKAD.',
            'user' => [
                'id'              => $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'role'            => $user->role,
                'identity_number' => $user->identity_number,
                'avatar'          => $user->avatar ?? strtoupper(substr($user->name, 0, 2)),
            ]
        ]);
    }

    /**
     * Endpoint Login menggunakan Akun Google.
     */
    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'name'      => 'nullable|string',
            'google_id' => 'nullable|string',
        ], [
            'email.required' => 'Email akun Google wajib disertakan.',
            'email.email'    => 'Format email Google tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email    = strtolower(trim($request->input('email')));
        $name     = $request->input('name', 'Pengguna Google');
        $googleId = $request->input('google_id', 'google_' . md5($email));

        // Cari user atau buat baru sebagai mahasiswa jika belum ada
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name'            => $name,
                'email'           => $email,
                'role'            => 'mahasiswa',
                'identity_number' => '220101' . rand(1000, 9999),
                'avatar'          => strtoupper(substr($name, 0, 1)),
                'google_id'       => $googleId,
                'password'        => Hash::make('GoogleAuth_' . bin2hex(random_bytes(8))),
            ]);
        } else {
            if (empty($user->google_id)) {
                $user->google_id = $googleId;
                $user->save();
            }
        }

        Auth::login($user, true);

        return response()->json([
            'success' => true,
            'message' => 'Login dengan akun Google berhasil!',
            'user' => [
                'id'              => $user->id,
                'name'            => $user->name,
                'email'           => $user->email,
                'role'            => $user->role,
                'identity_number' => $user->identity_number,
                'avatar'          => $user->avatar ?? 'G',
            ]
        ]);
    }

    /**
     * Redirect ke halaman otorisasi Google OAuth 2.0 resmi.
     */
    public function googleRedirect()
    {
        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');

        if (empty($clientId)) {
            return redirect('/login?error=google_not_configured');
        }

        $query = http_build_query([
            'client_id'     => $clientId,
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid profile email',
            'access_type'   => 'offline',
            'prompt'        => 'select_account',
        ]);

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . $query);
    }

    /**
     * Callback Google OAuth 2.0 setelah pengguna memilih akun Google.
     */
    public function googleCallback(Request $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return redirect('/login?error=google_auth_failed');
        }

        $clientId     = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri  = config('services.google.redirect');

        // Tukar authorization code dengan access token
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'grant_type'    => 'authorization_code',
        ]);

        if (!$response->successful()) {
            Log::error('Google OAuth Token Exchange Failed', ['body' => $response->body()]);
            return redirect('/login?error=google_token_failed');
        }

        $tokenData   = $response->json();
        $accessToken = $tokenData['access_token'] ?? null;

        // Ambil data profil pengguna dari Google
        $userResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (!$userResponse->successful()) {
            return redirect('/login?error=google_user_failed');
        }

        $googleUser = $userResponse->json();
        $email      = strtolower($googleUser['email'] ?? '');
        $name       = $googleUser['name'] ?? 'Pengguna Google';
        $googleId   = $googleUser['sub'] ?? null;

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name'            => $name,
                'email'           => $email,
                'role'            => 'mahasiswa',
                'identity_number' => '220101' . rand(1000, 9999),
                'avatar'          => strtoupper(substr($name, 0, 1)),
                'google_id'       => $googleId,
                'password'        => Hash::make('GoogleOAuth_' . bin2hex(random_bytes(8))),
            ]);
        } else {
            if (empty($user->google_id)) {
                $user->google_id = $googleId;
                $user->save();
            }
        }

        Auth::login($user, true);

        return redirect('/?logged_in=google&name=' . urlencode($user->name) . '&email=' . urlencode($user->email));
    }

    /**
     * Endpoint Kirim Kode OTP Lupa Password.
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = strtolower(trim($request->input('email')));
        $user  = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email tidak terdaftar dalam pangkalan data SI-AKAD.'
            ], 404);
        }

        // Generate 6 digit OTP acak
        $otpCode = (string) mt_rand(100000, 999999);
        $user->otp_code = $otpCode;
        $user->otp_expires_at = now()->addMinutes(15);
        $user->save();

        // Catat ke log sistem
        Log::info("OTP Reset Password dikirim ke [{$user->email}]: {$otpCode}");

        return response()->json([
            'success'  => true,
            'message'  => "Kode OTP telah berhasil dikirim ke {$user->email}.",
            'otp_demo' => $otpCode, // Disertakan untuk kemudahan testing interaktif
            'expires_in_minutes' => 15,
        ]);
    }

    /**
     * Endpoint Verifikasi Kode OTP.
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'otp.required'   => 'Kode OTP 6 digit wajib diisi.',
            'otp.size'       => 'Kode OTP harus tepat 6 digit angka.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = strtolower(trim($request->input('email')));
        $otp   = trim($request->input('otp'));

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun dengan email ini tidak ditemukan.'
            ], 404);
        }

        if (empty($user->otp_code) || $user->otp_code !== $otp) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP yang Anda masukkan keliru. Silakan coba lagi.'
            ], 422);
        }

        if (empty($user->otp_expires_at) || now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP telah kedaluwarsa. Silakan minta kode OTP baru.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi. Silakan masukkan kata sandi baru Anda.'
        ]);
    }

    /**
     * Endpoint Reset Password Baru dengan OTP.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email',
            'otp'                   => 'required|string|size:6',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string',
        ], [
            'email.required'        => 'Email wajib diisi.',
            'otp.required'          => 'Kode OTP wajib diisi.',
            'password.required'     => 'Kata sandi baru wajib diisi.',
            'password.min'          => 'Kata sandi baru minimal harus 6 karakter.',
            'password.confirmed'    => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        $email    = strtolower(trim($request->input('email')));
        $otp      = trim($request->input('otp'));
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan.'
            ], 404);
        }

        if (empty($user->otp_code) || $user->otp_code !== $otp || now()->isAfter($user->otp_expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau telah kedaluwarsa.'
            ], 422);
        }

        // Hash kata sandi baru secara aman menggunakan Bcrypt
        $user->password = Hash::make($password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui dengan aman! Silakan login kembali dengan kata sandi baru.'
        ]);
    }

    /**
     * Endpoint Logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Anda telah berhasil keluar dari sistem.'
        ]);
    }
}
