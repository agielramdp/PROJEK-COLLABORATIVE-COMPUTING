<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
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
     * Map request role parameter to db_sistem_akademik ENUM role
     */
    private function mapRole($roleKey)
    {
        $map = [
            'admin'          => 'Admin',
            'mahasiswa'      => 'Mahasiswa',
            'dosen_pengajar' => 'Dosen Pengajar',
            'dosen_wali'     => 'Dosen Wali',
            'bendahara'      => 'Bendahara',
        ];

        return $map[strtolower(trim($roleKey))] ?? $roleKey;
    }

    /**
     * Endpoint API Login dengan validasi hash password di db_sistem_akademik.users
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'role'     => 'nullable|string',
        ], [
            'username.required' => 'Nomor identitas (NPM/NIDN/NIP) atau email wajib diisi.',
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
        $roleInput  = $request->input('role');

        // Cari user di db_sistem_akademik berdasarkan email, npm, atau username
        $userQuery = User::where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                  ->orWhere('npm', $identifier)
                  ->orWhere('username', $identifier);
        });

        if (!empty($roleInput)) {
            $mappedRole = $this->mapRole($roleInput);
            $userQuery->where('role', $mappedRole);
        }

        $user = $userQuery->first();

        // Validasi user & hash password (password_hash)
        if (!$user || !Hash::check($password, $user->password_hash)) {
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
                'id'       => $user->id,
                'npm'      => $user->npm,
                'name'     => $user->name,
                'email'    => $user->email,
                'username' => $user->username,
                'role'     => $user->role,
                'avatar'   => strtoupper(substr($user->name, 0, 2)),
            ]
        ]);
    }

    /**
     * Endpoint Login menggunakan Akun Google (Simulasi/API).
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

        // Cari user atau buat baru di db_sistem_akademik.users
        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'npm'           => '220101' . rand(1000, 9999),
                'name'          => $name,
                'email'         => $email,
                'username'      => explode('@', $email)[0],
                'role'          => 'Mahasiswa',
                'google_id'     => $googleId,
                'password_hash' => Hash::make('GoogleAuth_' . bin2hex(random_bytes(8))),
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
                'id'       => $user->id,
                'npm'      => $user->npm,
                'name'     => $user->name,
                'email'    => $user->email,
                'role'     => $user->role,
                'avatar'   => 'G',
            ]
        ]);
    }

    /**
     * Redirect ke Google OAuth 2.0 resmi
     */
    public function googleRedirect()
    {
        $clientId    = config('services.google.client_id');
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
     * Callback Google OAuth 2.0 resmi
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
                'npm'           => '220101' . rand(1000, 9999),
                'name'          => $name,
                'email'         => $email,
                'username'      => explode('@', $email)[0],
                'role'          => 'Mahasiswa',
                'google_id'     => $googleId,
                'password_hash' => Hash::make('GoogleOAuth_' . bin2hex(random_bytes(8))),
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
     * Endpoint Kirim Kode OTP Lupa Password Sekali Pakai (Tabel otp_codes di db_sistem_akademik)
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

        // Validasi apakah email terdaftar pada tabel users di db_sistem_akademik
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email tidak terdaftar pada akun users di database SI-AKAD.'
            ], 404);
        }

        // Tandai/nonaktifkan OTP lama yang belum terpakai untuk user ini (agar hanya ada 1 OTP aktif)
        OtpCode::where('user_id', $user->id)
            ->where('is_used', 0)
            ->update(['is_used' => 1]);

        // Hasilkan kode 6 digit OTP acak
        $otpCode = str_pad((string) mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan ke tabel otp_codes di db_sistem_akademik dengan masa berlaku 15 menit dan status is_used = 0 (sekali pakai)
        $otpRecord = OtpCode::create([
            'user_id'    => $user->id,
            'otp_code'   => $otpCode,
            'expires_at' => now()->addMinutes(15),
            'is_used'    => 0,
            'created_at' => now(),
        ]);

        // Catat pengiriman OTP ke log sistem (simulasi pengiriman email ke email terdaftar)
        Log::info("OTP Sekali Pakai dikirim ke email terdaftar [{$user->email}] untuk User ID #{$user->id}: {$otpCode}");

        return response()->json([
            'success'  => true,
            'message'  => "Kode OTP sekali pakai telah berhasil dikirimkan ke email terdaftar: {$user->email}.",
            'otp_demo' => $otpCode, // Disertakan untuk kemudahan demonstrasi & testing langsung
            'expires_in_minutes' => 15,
        ]);
    }

    /**
     * Endpoint Verifikasi Kode OTP dari tabel otp_codes (db_sistem_akademik)
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
                'message' => 'Akun dengan email ini tidak ditemukan di database.'
            ], 404);
        }

        // Cari record OTP yang cocok di tabel otp_codes
        $otpRecord = OtpCode::where('user_id', $user->id)
            ->where('otp_code', $otp)
            ->latest('id')
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP yang Anda masukkan keliru.'
            ], 422);
        }

        if ($otpRecord->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP ini sudah pernah digunakan (hanya berlaku sekali pakai). Silakan minta kode baru.'
            ], 422);
        }

        if (now()->isAfter($otpRecord->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP telah kedaluwarsa. Silakan minta kode OTP baru.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP valid. Silakan masukkan kata sandi baru Anda.'
        ]);
    }

    /**
     * Endpoint Reset Password Baru & Mengubah OTP Menjadi Sudah Terpakai (Sekali Pakai)
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
                'message' => 'Akun tidak ditemukan di database.'
            ], 404);
        }

        // Cari kode OTP di tabel otp_codes
        $otpRecord = OtpCode::where('user_id', $user->id)
            ->where('otp_code', $otp)
            ->latest('id')
            ->first();

        if (!$otpRecord || $otpRecord->is_used || now()->isAfter($otpRecord->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid, sudah pernah dipakai, atau telah kedaluwarsa.'
            ], 422);
        }

        // 1. Simpan kata sandi baru dengan hash Bcrypt ke kolom password_hash
        $user->password_hash = Hash::make($password);
        $user->save();

        // 2. Kunci status OTP menjadi is_used = 1 (SEKALI PAKAI)
        $otpRecord->is_used = 1;
        $otpRecord->save();

        Log::info("Password untuk User ID #{$user->id} ({$user->email}) berhasil diperbarui dan OTP {$otp} dinonaktifkan (is_used=1).");

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
