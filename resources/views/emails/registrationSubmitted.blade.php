<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Kepanitiaan</title>
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
                                Pendaftaran Berhasil Dikirim
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

                            <p style="
                                font-size:15px;
                                line-height:1.8;
                                margin-bottom:28px;
                            ">
                                Terima kasih telah melakukan pendaftaran
                                kepanitiaan melalui Sistem Administrasi
                                Kepanitiaan Mahasiswa.
                            </p>

                            <p style="
                                font-size:15px;
                                line-height:1.8;
                                margin-bottom:28px;
                            ">
                                Pendaftaran Anda telah berhasil diterima dan saat
                                ini sedang menunggu proses selanjutnya oleh panitia.
                            </p>

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
                                        color:#f39c12;
                                    ">
                                        MENUNGGU
                                    </td>
                                </tr>

                            </table>

                            <!-- JADWAL INTERVIEW -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="
                                background-color:#ffffff;
                                border-radius:12px;
                                overflow:hidden;
                                box-shadow:0 4px 12px rgba(0,0,0,0.08);
                            ">

                                <tr>
                                    <td colspan="2" style="
                                        font-size:16px;
                                        font-weight:bold;
                                        color:#0d6efd;
                                        padding-bottom:15px;
                                    ">
                                        Detail Jadwal Interview
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        width:35%;
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        <strong>Tanggal</strong>
                                    </td>

                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        {{ $interviewDate }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        <strong>Waktu</strong>
                                    </td>

                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        {{ $interviewTime }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        <strong>Tempat</strong>
                                    </td>

                                    <td style="
                                        padding:10px 0;
                                        font-size:15px;
                                    ">
                                        {{ $interviewPlace }}
                                    </td>
                                </tr>

                            </table>

                            <p style="
                                font-size:15px;
                                line-height:1.8;
                                margin-bottom:24px;
                            ">
                                Anda dapat memantau perkembangan status
                                pendaftaran melalui website Sistem Administrasi
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

                            <p style="
                                font-size:15px;
                                line-height:1.8;
                            ">
                                Semoga sukses dalam proses seleksi dan terima kasih
                                atas antusiasme Anda untuk berkontribusi dalam
                                kegiatan kepanitiaan.
                            </p>

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