<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Keamanan Akun SI-AKAD</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .email-header {
            background: linear-gradient(135deg, #0e2954 0%, #1e3a8a 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .email-header h1 {
            margin: 12px 0 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .email-header p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #93c5fd;
        }
        .email-body {
            padding: 32px;
        }
        .alert-box {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 16px;
            border-radius: 6px;
            margin-bottom: 24px;
        }
        .alert-title {
            font-size: 15px;
            font-weight: 700;
            color: #991b1b;
            margin: 0 0 4px;
        }
        .alert-desc {
            font-size: 13.5px;
            color: #b91c1c;
            margin: 0;
            line-height: 1.5;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #f8fafc;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .details-table td {
            padding: 12px 16px;
            font-size: 13.5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-table tr:last-child td {
            border-bottom: none;
        }
        .details-table .label {
            font-weight: 600;
            color: #475569;
            width: 38%;
        }
        .details-table .value {
            color: #0f172a;
            font-weight: 500;
        }
        .badge-lockout {
            display: inline-block;
            background: #dc2626;
            color: #ffffff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }
        .recommendations {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 18px 20px;
            border-radius: 8px;
            margin: 24px 0;
        }
        .recommendations h4 {
            margin: 0 0 10px;
            color: #92400e;
            font-size: 14px;
            font-weight: 700;
        }
        .recommendations ul {
            margin: 0;
            padding-left: 20px;
            color: #78350f;
            font-size: 13px;
            line-height: 1.6;
        }
        .email-footer {
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <h1>Ada aktivitas login mencurigakan pada akun Anda</h1>
            <p>Pemberitahuan Keamanan Sistem Informasi Akademik (SI-AKAD)</p>
        </div>

        <!-- Body Content -->
        <div class="email-body">
            <p style="font-size: 15px; margin-top: 0;">Halo, <strong>{{ $name }}</strong>,</p>

            <div class="alert-box">
                <div class="alert-title">⚠️ Terdeteksi 5 Percobaan Login Gagal Berturut-turut</div>
                <div class="alert-desc">
                    Sistem mendeteksi adanya aktivitas login mencurigakan ke akun Anda menggunakan kata sandi yang salah secara berulang. Demi keamanan, formulir login pada akun Anda telah <strong>diblokir sementara selama 15 menit</strong>.
                </div>
            </div>

            <p style="font-size: 13.5px; line-height: 1.6; color: #334155;">
                Berikut adalah rincian aktivitas percobaan login yang tercatat pada sistem kami:
            </p>

            <table class="details-table">
                <tr>
                    <td class="label">Alamat Email</td>
                    <td class="value">{{ $email }}</td>
                </tr>
                @if(!empty($npm) && $npm !== '-')
                <tr>
                    <td class="label">Nomor Identitas (NPM/NIP)</td>
                    <td class="value">{{ $npm }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Waktu Kejadian</td>
                    <td class="value">{{ $time }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat IP Pelaku</td>
                    <td class="value"><code>{{ $ip }}</code></td>
                </tr>
                <tr>
                    <td class="label">Perangkat / Browser</td>
                    <td class="value" style="font-size: 12px; word-break: break-all;">{{ $user_agent }}</td>
                </tr>
                <tr>
                    <td class="label">Status Akun</td>
                    <td class="value"><span class="badge-lockout">DIBLOKIR 15 MENIT</span></td>
                </tr>
            </table>

            <div class="recommendations">
                <h4>🛡️ Apa yang harus Anda lakukan?</h4>
                <ul>
                    <li><strong>Jika ini adalah Anda:</strong> Mohon tunggu hingga masa blokir 15 menit selesai sebelum mencoba masuk kembali. Pastikan kata sandi yang Anda masukkan benar.</li>
                    <li><strong>Jika ini BUKAN Anda:</strong> Seseorang mungkin mencoba menebak kata sandi akun Anda. Sangat disarankan untuk segera melakukan reset kata sandi menggunakan fitur <strong>"Lupa Password"</strong> setelah masa blokir berakhir.</li>
                    <li>Jangan pernah membagikan kode OTP atau kata sandi Anda kepada siapa pun, termasuk pihak yang mengatasnamakan administrator kampus.</li>
                </ul>
            </div>

            <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin-bottom: 0;">
                Email ini dikirimkan secara otomatis oleh sistem keamanan SI-AKAD untuk melindungi akun dan data akademik Anda.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            &copy; {{ date('Y') }} SI-AKAD &bull; Institut Informatika &amp; Komputer &bull; Tim Keamanan Siber &amp; IT Kampus
        </div>
    </div>
</body>
</html>
