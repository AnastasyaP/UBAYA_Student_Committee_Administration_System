<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Status Pendaftaran Kepanitiaan</title>
</head>

<body style="
    margin:0;
    padding:0;
    background-color:#f4f6f9;
    font-family:Arial, Helvetica, sans-serif;
">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="
                    background-color:#ffffff;
                    border-radius:12px;
                    overflow:hidden;
                    box-shadow:0 4px 12px rgba(0,0,0,0.08);
                ">

                    <!-- HEADER -->
                    <tr>
                        <td style="
                            background-color:#2c3e50;
                            padding:30px;
                            text-align:center;
                            color:white;
                        ">

                            <h1 style="
                                margin:0;
                                font-size:28px;
                                font-weight:bold;
                            ">
                                Status Pendaftaran
                            </h1>

                            <p style="
                                margin-top:10px;
                                font-size:14px;
                                color:#dfe6e9;
                            ">
                                Sistem Administrasi Kepanitiaan Mahasiswa
                            </p>

                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="
                            padding:40px;
                            color:#2d3436;
                        ">

                            <p style="
                                font-size:16px;
                                margin-bottom:24px;
                            ">
                                Halo {{ $name }},
                            </p>

                            @if($status == 'diterima')

                                <p style="
                                    font-size:15px;
                                    line-height:1.8;
                                    margin-bottom:28px;
                                ">
                                    Selamat! Pendaftaran Anda telah
                                    <strong>DITERIMA</strong>
                                    pada kepanitiaan berikut:
                                </p>

                            @else

                                <p style="
                                    font-size:15px;
                                    line-height:1.8;
                                    margin-bottom:28px;
                                ">
                                    Terima kasih telah berpartisipasi dalam proses
                                    pendaftaran kepanitiaan. Setelah melalui proses
                                    seleksi, kami memberitahukan bahwa pendaftaran
                                    Anda saat ini
                                    <strong>DITOLAK</strong>.
                                </p>

                            @endif

                            <!-- DETAIL BOX -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="
                                background-color:#f8f9fa;
                                border-radius:10px;
                                padding:20px;
                                margin-bottom:30px;
                                border:1px solid #e9ecef;
                            ">

                                <tr>
                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                        width:35%;
                                    ">
                                        <strong>Kepanitiaan</strong>
                                    </td>

                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        {{ $committee }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        <strong>Divisi</strong>
                                    </td>

                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        {{ $division }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        <strong>Status</strong>
                                    </td>

                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                        font-weight:bold;
                                        color:
                                            {{ $status == 'diterima'
                                                ? '#27ae60'
                                                : '#e74c3c' }};
                                    ">
                                        {{ strtoupper($status) }}
                                    </td>
                                </tr>

                            </table>

                            <p style="
                                font-size:15px;
                                line-height:1.8;
                                margin-bottom:24px;
                            ">
                                Anda dapat melihat detail status pendaftaran
                                melalui website Sistem Administrasi
                                Kepanitiaan Mahasiswa.
                            </p>

                            <!-- <div style="
                                text-align:center;
                                margin:35px 0;
                            ">
                                <a href="{{ url('/') }}" style="
                                    background-color:#2c3e50;
                                    color:white;
                                    text-decoration:none;
                                    padding:14px 28px;
                                    border-radius:8px;
                                    font-size:15px;
                                    font-weight:bold;
                                    display:inline-block;
                                ">
                                    Buka Website Sistem
                                </a>
                            </div> -->

                            @if($status == 'diterima')

                                <p style="
                                    font-size:15px;
                                    line-height:1.8;
                                ">
                                    Kami berharap Anda dapat berpartisipasi
                                    secara aktif dan bekerja sama dengan seluruh
                                    anggota panitia demi menyukseskan kegiatan ini.
                                </p>

                            @else

                                <p style="
                                    font-size:15px;
                                    line-height:1.8;
                                ">
                                    Jangan berkecil hati dan tetap semangat untuk
                                    mengikuti kesempatan kepanitiaan lainnya di
                                    masa mendatang.
                                </p>

                            @endif

                            <p style="
                                font-size:15px;
                                line-height:1.8;
                                margin-top:32px;
                            ">
                                Hormat kami,
                                <br><br>
                                <strong>Sistem Administrasi Kepanitiaan Mahasiswa</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="
                            background-color:#f1f3f5;
                            padding:20px;
                            text-align:center;
                            font-size:12px;
                            color:#636e72;
                        ">
                            Email ini dikirim secara otomatis oleh Sistem
                            Administrasi Kepanitiaan Mahasiswa.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
