<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
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
     * Dapatkan kunci throttle dan status lockout untuk identitas & IP tertentu.
     */
    private function getThrottleKeys(Request $request): array
    {
        $identifier = strtolower(trim((string) $request->input('username', '')));
        $ip = $request->ip();

        $user = null;
        if (!empty($identifier)) {
            $user = User::where(function ($query) use ($identifier) {
                $query->where('email', $identifier)
                      ->orWhere('npm', $identifier)
                      ->orWhere('username', $identifier);
            })->first();
        }

        // Kunci throttle terikat pada User ID jika ditemukan, atau hash dari input jika tidak
        $accountTag  = $user ? 'user_' . $user->id : 'raw_' . md5($identifier);
        $throttleKey = 'auth_attempt:' . $accountTag . '|' . $ip;
        $lockoutKey  = 'auth_lockout:' . $accountTag . '|' . $ip;

        return [
            'identifier'  => $identifier,
            'user'        => $user,
            'throttleKey' => $throttleKey,
            'lockoutKey'  => $lockoutKey,
            'ip'          => $ip,
        ];
    }

    /**
     * Endpoint API untuk mengecek status percobaan login & lockout akun saat ini
     */
    public function checkLoginStatus(Request $request)
    {
        $throttleData = $this->getThrottleKeys($request);
        $throttleKey  = $throttleData['throttleKey'];
        $lockoutKey   = $throttleData['lockoutKey'];

        $lockoutUntil = Cache::get($lockoutKey);
        $locked       = false;
        $retryAfter   = 0;

        if ($lockoutUntil && now()->timestamp < $lockoutUntil) {
            $locked     = true;
            $retryAfter = $lockoutUntil - now()->timestamp;
        }

        $attempts = (int) Cache::get($throttleKey, 0);

        $roleInput          = strtolower(trim((string) $request->input('role', '')));
        $isAdminOrBendahara = in_array($roleInput, ['admin', 'bendahara']);
        $requireRecaptcha   = $isAdminOrBendahara || ($attempts >= 3);

        return response()->json([
            'success'               => true,
            'attempts'              => $attempts,
            'locked'                => $locked,
            'retry_after'           => $retryAfter,
            'require_recaptcha'     => $requireRecaptcha,
            'is_admin_or_bendahara' => $isAdminOrBendahara,
        ]);
    }

    /**
     * Endpoint API untuk me-reset percobaan login akun (kemudahan demonstrasi & testing)
     */
    public function resetAttempts(Request $request)
    {
        $throttleData = $this->getThrottleKeys($request);
        Cache::forget($throttleData['throttleKey']);
        Cache::forget($throttleData['lockoutKey']);

        return response()->json([
            'success' => true,
            'message' => 'Hitungan percobaan login dan status lockout berhasil di-reset untuk demonstrasi.',
        ]);
    }

    /**
     * Endpoint API Login dengan Login Attempt Limiting dan Account Lockout
     * 
     * Alur:
     * - Percobaan 1 Gagal: Pesan error "NPM/Email atau Password Salah".
     * - Percobaan 2 Gagal: Pesan error "NPM/Email atau Password Salah".
     * - Percobaan 3 Gagal: Sistem memicu penambahan keamanan, mewajibkan reCAPTCHA untuk mahasiswa dan dosen (reCAPTCHA muncul sejak awal untuk Bendahara dan Admin).
     * - Percobaan 4 Gagal: Form login diblokir sementara selama 5 menit (300 detik) untuk semua role.
     * - Percobaan 5 Gagal: Form login diblokir sementara selama 15 menit (900 detik) DAN sistem mengirimkan email peringatan: "Ada aktivitas login mencurigakan pada akun Anda".
     */
    public function login(Request $request)
    {
        // 1. Validasi input dasar (username & password)
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

        $throttleData = $this->getThrottleKeys($request);
        $identifier   = $throttleData['identifier'];
        $throttleKey  = $throttleData['throttleKey'];
        $lockoutKey   = $throttleData['lockoutKey'];
        $user         = $throttleData['user'];
        $ip           = $throttleData['ip'];

        // =======================================================
        // STEP 1: CEK APAKAH AKUN SEDANG DALAM STATUS LOCKOUT
        // =======================================================
        $lockoutUntil = Cache::get($lockoutKey);
        if ($lockoutUntil && now()->timestamp < $lockoutUntil) {
            $secondsRemaining = $lockoutUntil - now()->timestamp;
            $minutesRemaining = ceil($secondsRemaining / 60);
            $currentAttempts  = (int) Cache::get($throttleKey, 4);

            Log::warning('Login ditolak: Akun sedang dalam masa lockout', [
                'identifier' => $identifier,
                'ip'         => $ip,
                'remaining'  => $secondsRemaining . ' detik',
                'attempts'   => $currentAttempts,
            ]);

            return response()->json([
                'success'           => false,
                'locked'            => true,
                'retry_after'       => $secondsRemaining,
                'attempts'          => $currentAttempts,
                'require_recaptcha' => true,
                'message'           => "Form login Anda sedang diblokir sementara. Silakan tunggu {$minutesRemaining} menit ({$secondsRemaining} detik lagi).",
            ], 429);
        }

        // =======================================================
        // STEP 2: CEK KEWAJIBAN reCAPTCHA
        // - Role Bendahara dan Admin: reCAPTCHA aktif SEJAK AWAL
        // - Role Mahasiswa dan Dosen: Wajib reCAPTCHA SETELAH Percobaan 3 Gagal (attempts >= 3)
        // =======================================================
        $roleInput          = strtolower(trim((string) $request->input('role', '')));
        $isAdminOrBendahara = in_array($roleInput, ['admin', 'bendahara']);
        $attemptsBefore     = (int) Cache::get($throttleKey, 0);
        $isRecaptchaRequired = $isAdminOrBendahara || ($attemptsBefore >= 3);

        if ($isRecaptchaRequired) {
            $recaptchaToken = $request->input('g-recaptcha-response');

            if (empty($recaptchaToken)) {
                return response()->json([
                    'success'           => false,
                    'require_recaptcha' => true,
                    'attempts'          => $attemptsBefore,
                    'message'           => 'Verifikasi reCAPTCHA wajib diselesaikan.',
                ], 422);
            }

            $recaptchaValid = $this->verifyRecaptcha($recaptchaToken);
            if (!$recaptchaValid) {
                return response()->json([
                    'success'           => false,
                    'require_recaptcha' => true,
                    'attempts'          => $attemptsBefore,
                    'message'           => 'Verifikasi reCAPTCHA tidak valid. Silakan coba lagi.',
                ], 422);
            }
        }

        // =======================================================
        // STEP 3: VALIDASI KREDENSIAL (User & Hash Password)
        // =======================================================
        $password = $request->input('password');

        // Pastikan pencarian user konsisten dengan role jika role dikirim
        $userQuery = User::where(function ($query) use ($identifier) {
            $query->where('email', $identifier)
                  ->orWhere('npm', $identifier)
                  ->orWhere('username', $identifier);
        });

        if (!empty($roleInput)) {
            $mappedRole = $this->mapRole($roleInput);
            $userQuery->where('role', $mappedRole);
        }

        $matchedUser = $userQuery->first();
        $userPasswordHash = $matchedUser ? ($matchedUser->password_hash ?? $matchedUser->password) : null;
        $isPasswordCorrect = $matchedUser && Hash::check($password, $userPasswordHash);

        // =======================================================
        // STEP 4: JIKA LOGIN GAGAL (KREDENSIAL SALAH)
        // =======================================================
        if (!$isPasswordCorrect) {
            $newAttempts = $attemptsBefore + 1;
            Cache::put($throttleKey, $newAttempts, now()->addHours(2));

            Log::warning('Percobaan login gagal', [
                'identifier' => $identifier,
                'ip'         => $ip,
                'attempt'    => $newAttempts,
            ]);

            // KASUS 1 & 2: Percobaan 1 atau 2 Gagal
            // Sistem hanya menampilkan pesan error "NPM/Email atau Password Salah"
            if ($newAttempts < 3) {
                return response()->json([
                    'success'           => false,
                    'attempts'          => $newAttempts,
                    'require_recaptcha' => $isAdminOrBendahara,
                    'message'           => 'NPM/Email atau Password Salah',
                ], 401);
            }

            // KASUS 3: Percobaan 3 Gagal
            // Sistem memicu penambahan keamanan, mewajibkan pengisian reCAPTCHA untuk mahasiswa dan dosen
            if ($newAttempts === 3) {
                $message = $isAdminOrBendahara
                    ? 'NPM/Email atau Password Salah. Peringatan: 1 kali lagi gagal, form login akan dikunci 5 menit!'
                    : 'NPM/Email atau Password Salah. Sistem mendeteksi 3 kali kegagalan, verifikasi reCAPTCHA kini wajib diisi.';

                return response()->json([
                    'success'           => false,
                    'attempts'          => 3,
                    'require_recaptcha' => true,
                    'message'           => $message,
                ], 401);
            }

            // KASUS 4: Percobaan 4 Gagal
            // System memblokir form login pengguna tersebut sementara selama 5 menit (300 detik)
            if ($newAttempts === 4) {
                $lockDuration = 300; // 5 menit
                Cache::put($lockoutKey, now()->addSeconds($lockDuration)->timestamp, now()->addMinutes(10));

                Log::warning('Akun dikunci 5 menit karena percobaan gagal ke-4', [
                    'identifier' => $identifier,
                    'ip'         => $ip,
                ]);

                return response()->json([
                    'success'           => false,
                    'locked'            => true,
                    'retry_after'       => $lockDuration,
                    'attempts'          => 4,
                    'require_recaptcha' => true,
                    'message'           => 'Percobaan login gagal ke-4. Form login diblokir sementara selama 5 menit demi keamanan akun.',
                ], 429);
            }

            // KASUS 5: Percobaan 5 Gagal (atau lebih)
            // System memblokir form login pengguna tersebut sementara selama 15 menit (900 detik)
            // DAN mengutus sistem untuk mengirimkan email peringatan: "Ada aktivitas login mencurigakan pada akun Anda".
            $lockDuration = 900; // 15 menit
            Cache::put($lockoutKey, now()->addSeconds($lockDuration)->timestamp, now()->addMinutes(30));

            $emailSent = false;
            $targetUser = $matchedUser ?? $user;

            if ($targetUser && !empty($targetUser->email)) {
                try {
                    $emailData = [
                        'name'       => $targetUser->name,
                        'email'      => $targetUser->email,
                        'npm'        => $targetUser->npm ?? '-',
                        'ip'         => $ip,
                        'user_agent' => $request->userAgent() ?? 'Perangkat Tidak Dikenal',
                        'time'       => now()->translatedFormat('d F Y, H:i:s') . ' WIB',
                    ];

                    Mail::send('emails.suspicious_login', $emailData, function ($mail) use ($targetUser) {
                        $mail->to($targetUser->email, $targetUser->name)
                             ->subject('Ada aktivitas login mencurigakan pada akun Anda');
                    });

                    $emailSent = true;
                    Log::info("Email peringatan login mencurigakan berhasil dikirim ke: {$targetUser->email}");
                } catch (\Throwable $e) {
                    Log::error("Gagal mengirim email aktivitas mencurigakan: " . $e->getMessage());
                }
            } else {
                Log::warning("Percobaan ke-5 gagal untuk identifier [{$identifier}], email tidak terdaftar sehingga notifikasi email dilewati.");
            }

            return response()->json([
                'success'           => false,
                'locked'            => true,
                'retry_after'       => $lockDuration,
                'attempts'          => $newAttempts,
                'email_sent'        => $emailSent,
                'require_recaptcha' => true,
                'message'           => 'Percobaan login gagal ke-5. Form login diblokir sementara selama 15 menit. ' .
                                       ($emailSent ? 'Sistem telah mengirimkan email peringatan ke akun Anda.' : 'Ada aktivitas login mencurigakan pada akun Anda.'),
            ], 429);
        }

        // =======================================================
        // STEP 5: LOGIN BERHASIL — Reset Hitungan & Status Lockout
        // =======================================================
        Cache::forget($throttleKey);
        Cache::forget($lockoutKey);

        Auth::login($matchedUser, $request->boolean('remember'));

        Log::info('Login berhasil', [
            'user_id' => $matchedUser->id,
            'email'   => $matchedUser->email,
            'role'    => $matchedUser->role,
            'ip'      => $ip,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil! Selamat datang kembali di SI-AKAD.',
            'user' => [
                'id'       => $matchedUser->id,
                'npm'      => $matchedUser->npm,
                'name'     => $matchedUser->name,
                'email'    => $matchedUser->email,
                'username' => $matchedUser->username,
                'role'     => $matchedUser->role,
                'avatar'   => strtoupper(substr($matchedUser->name, 0, 2)),
            ]
        ]);
    }

    /**
     * Verifikasi token reCAPTCHA v2 ke Google.
     */
    private function verifyRecaptcha(string $token): bool
    {
        // Bypass untuk demonstrasi/testing jika menggunakan token simulasi
        if (config('app.debug') && in_array($token, ['demo_bypass', 'test_token', 'bypass_recaptcha'])) {
            return true;
        }

        $secretKey = config('services.recaptcha.secret_key');

        if (empty($secretKey)) {
            Log::warning('reCAPTCHA secret key tidak dikonfigurasi.');
            return true; // Bypass jika belum dikonfigurasi (development)
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            Log::info('reCAPTCHA verify result', $result ?? []);

            return $result['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            return false;
        }
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
            'prompt'        => 'select_account', // Muncul pilih akun dari device/browser
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
        $picture    = $googleUser['picture'] ?? null;

        if (empty($email)) {
            return redirect('/login?error=google_email_missing');
        }

        // Cari user yang sudah terdaftar di db_sistem_akademik berdasarkan email
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Email tidak terdaftar di sistem — tolak login
            Log::warning("Google OAuth: email [{$email}] tidak terdaftar di database SI-AKAD.");
            return redirect('/login?error=google_not_registered&email=' . urlencode($email));
        }

        // Update google_id jika belum ada
        if (empty($user->google_id)) {
            $user->google_id = $googleId;
            $user->save();
        }

        Auth::login($user, true);

        Log::info("Google OAuth login berhasil: User ID #{$user->id} ({$user->email}) role: {$user->role}");

        // Redirect ke halaman utama dengan flash session
        return redirect('/')->with('google_login_success', [
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ]);
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
