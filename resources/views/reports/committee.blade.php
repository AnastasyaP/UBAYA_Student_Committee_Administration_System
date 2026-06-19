<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

   <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
            line-height: 1.5;
        }

        h1,
        h2,
        h3 {
            margin-bottom: 10px;
        }

        h2 {
            background: #f2f2f2;
            padding: 8px;
            border-left: 5px solid #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background: #e5e5e5;
        }

        th,
        td {
            padding: 6px;
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .cover {
            margin-top: 120px;
            text-align: center;
        }

        .section {
            margin-bottom: 20px;
        }

        .comment-box {
            border: 1px solid #ccc;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    {{-- COVER --}}
    <div class="cover">

        <h1>LAPORAN KEPANITIAAN</h1>

        <h2 style="background:none;border:none;">
            {{ $committee->committee_name }}
        </h2>

        <br>

        @if($committee->picture)
            <img src="{{ public_path('storage/'.$committee->picture) }}"
                style="width:250px;">
        @endif

        <br><br>

        <h3>
            {{ $committee->organizerName }}
        </h3>

        <p>
            Periode Kegiatan
            <br>
            {{ date('d F Y', strtotime($committee->start_period)) }}
            -
            {{ date('d F Y', strtotime($committee->end_period)) }}
        </p>

        <br><br>

        <p>
            Digenerate pada
            {{ date('d F Y') }}
        </p>

    </div>

    <div class="page-break"></div>

    {{-- RINGKASAN --}}
    <h2>I. Ringkasan Kepanitiaan</h2>

    <table>
        <tr>
            <th width="35%">Nama Kepanitiaan</th>
            <td>{{ $committee->committee_name }}</td>
        </tr>

        <tr>
            <th>Unit Penyelenggara</th>
            <td>{{ $committee->organizerName }}</td>
        </tr>

        <tr>
            <th>Email</th>
            <td>{{ $committee->email }}</td>
        </tr>

        <tr>
            <th>Kontak</th>
            <td>{{ $committee->contact }}</td>
        </tr>

        <tr>
            <th>Periode</th>
            <td>
                {{ date('d M Y', strtotime($committee->start_period)) }}
                -
                {{ date('d M Y', strtotime($committee->end_period)) }}
            </td>
        </tr>

        <tr>
            <th>Status</th>
            <td>
                {{ $committee->is_active ? 'Aktif' : 'Tidak Aktif' }}
            </td>
        </tr>
    </table>

    @php
        $totalMembers = collect($members)
                            ->flatten()
                            ->whereNotNull('name')
                            ->count();

        $totalDivisions = count($members);
    @endphp

    <table>
        <tr>
            <th>Total Divisi</th>
            <td>{{ $totalDivisions }}</td>
        </tr>

        <tr>
            <th>Total Panitia</th>
            <td>{{ $totalMembers }}</td>
        </tr>

        <tr>
            <th>Total Evaluasi Kepanitiaan</th>
            <td>{{ $generalCommitteeEvaluation->count() }}</td>
        </tr>
    </table>

    {{-- INFORMASI --}}
    <h2>II. Informasi Kepanitiaan</h2>

    <table>
        <tr>
            <th width="35%">Nama Kepanitiaan</th>
            <td>{{ $committee->committee_name }}</td>
        </tr>

        <tr>
            <th>Unit Penyelenggara</th>
            <td>{{ $committee->organizerName }}</td>
        </tr>

        <tr>
            <th>Email</th>
            <td>{{ $committee->email }}</td>
        </tr>

        <tr>
            <th>Kontak</th>
            <td>{{ $committee->contact }}</td>
        </tr>

        <tr>
            <th>Mulai Pendaftaran</th>
            <td>{{ $committee->start_regis }}</td>
        </tr>

        <tr>
            <th>Akhir Pendaftaran</th>
            <td>{{ $committee->end_regis }}</td>
        </tr>

        <tr>
            <th>Batas Evaluasi</th>
            <td>{{ $committee->end_evaluation }}</td>
        </tr>
    </table>

    {{-- DESKRIPSI --}}
    <h2>III. Deskripsi Kegiatan</h2>

    <p>
        {{ $committee->description }}
    </p>

    {{-- DAFTAR PANITIA --}}
    <h2>IV. Daftar Panitia</h2>

    @foreach($members as $division => $divisionMembers)

        <h3>Divisi {{ $division }}</h3>

        <table>

            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Posisi</th>
                </tr>
            </thead>

            <tbody>

                @php $hasMember = false; @endphp

                @foreach($divisionMembers as $member)

                    @if($member->name)

                        @php $hasMember = true; @endphp

                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->position }}</td>
                        </tr>

                    @endif

                @endforeach

                @if(!$hasMember)
                    <tr>
                        <td colspan="3" class="center">
                            Belum ada anggota diterima
                        </td>
                    </tr>
                @endif

            </tbody>

        </table>

    @endforeach

    <div class="page-break"></div>

    {{-- EVALUASI KEPANITIAAN --}}
    <h2>V. Evaluasi Kepanitiaan</h2>

    <h3>Evaluasi Keseluruhan</h3>

    @forelse($generalCommitteeEvaluation as $eval)

        <div class="comment-box">
            {{ $eval->general_comment }}
        </div>

    @empty

        <p>Tidak terdapat evaluasi kepanitiaan.</p>

    @endforelse

    <h3>Evaluasi Berdasarkan Kriteria</h3>

    @forelse($committeeCriteriaEvaluations as $eval)

        @if($eval->criteria_comment)

            <div class="comment-box">

                <strong>{{ $eval->criteria }}</strong>

                <br><br>

                {{ $eval->criteria_comment }}

            </div>

        @endif

    @empty

        <p>Tidak terdapat evaluasi berdasarkan kriteria.</p>

    @endforelse

    {{-- EVALUASI DIVISI --}}
    <h2>VI. Evaluasi Per Divisi</h2>

    @foreach($members as $division => $divisionMembers)

        <h3>Divisi {{ $division }}</h3>

        <strong>Evaluasi Umum</strong>

        <br><br>

        @if(isset($generalDivisionEvaluation[$division]))

            @foreach($generalDivisionEvaluation[$division] as $eval)

                <div class="comment-box">
                    {{ $eval->general_comment }}
                </div>

            @endforeach

        @else

            <p>Tidak terdapat evaluasi.</p>

        @endif

        <strong>Evaluasi Berdasarkan Kriteria</strong>

        <br><br>

        @if(isset($divisionCriteriaEvaluations[$division]))

            @foreach($divisionCriteriaEvaluations[$division] as $eval)

                @if($eval->criteria_comment)

                    <div class="comment-box">

                        <strong>{{ $eval->criteria }}</strong>

                        <br><br>

                        {{ $eval->criteria_comment }}

                    </div>

                @endif

            @endforeach

        @endif

        <hr>

    @endforeach

    {{-- KESIMPULAN --}}
    <h2>VII. Kesimpulan</h2>

    <p>
        Berdasarkan data yang tercatat dalam sistem, kepanitiaan
        {{ $committee->committee_name }}
        telah melaksanakan kegiatan sesuai periode yang ditentukan.
        Hasil evaluasi yang diberikan oleh panitia dapat digunakan
        sebagai bahan refleksi dan perbaikan dalam pelaksanaan
        kegiatan serupa pada masa mendatang.
    </p>

    {{-- PENUTUP --}}
    <h2>VIII. Penutup</h2>

    <p>
        Demikian laporan kepanitiaan ini dibuat secara otomatis oleh
        Sistem Administrasi Kepanitiaan Mahasiswa sebagai bentuk
        dokumentasi pelaksanaan kegiatan dan rekapitulasi hasil evaluasi.
    </p>

    <br><br>

    <p>
        Surabaya, {{ date('d F Y') }}
    </p>

    <br><br><br>

    <p>
        __________________________
    </p>

    <p>
        Admin Kepanitiaan
    </p>

    {{-- LAMPIRAN --}}
    @if($committee->poster)

        <div class="page-break"></div>

        <h2>LAMPIRAN</h2>

        <h3>Poster Kepanitiaan</h3>

        <div class="center">

            <img
                src="{{ public_path('storage/'.$committee->poster) }}"
                style="width:450px;">

        </div>

    @endif

</body>
</html>