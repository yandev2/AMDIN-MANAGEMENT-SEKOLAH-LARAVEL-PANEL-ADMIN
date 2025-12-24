<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Presensi Guru</title>
</head>

<body>
    @php
    use Carbon\Carbon;
    $tanggalPresensi = Carbon::parse($json['bulan'])->format('M Y');
    $bulan = Carbon::parse($json['bulan'])->format('m');
    $tahun = Carbon::parse($json['bulan'])->format('Y');
    $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth; // jumlah hari di bulan
    $mingguList = [];
    $colspanHeader = $jumlahHari+7;

    for ($day = 1; $day <= $jumlahHari; $day++) { $tanggal=Carbon::createFromDate($tahun, $bulan, $day); if ($tanggal->
        format('D') == 'Sun') {
        $mingguList[] = $day;
        }
        }
        @endphp


        <div class="spacing"></div>

        <table border="1" cellspacing="0" cellpadding="2" style="width: 100%">
            <thead>
                <tr>
                    <th style="text-align: center; font-size:15px; padding: 50px; font-weight: 800;  background-color:#3a3dff; color: white; align-content: center; align-items: center;"
                        colspan="{{ $colspanHeader }}">
                        DAFTAR KEHADIRAN GURU
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center; font-size:12px; padding: 50px;  background-color:#3a3dff; color: white; align-content: center; align-items: center;"
                        colspan="{{ $colspanHeader }}">
                        {{ $json['nama_sekolah'] }}
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center; font-size:12px; padding: 50px;  background-color:#3a3dff; color: white; align-content: center; align-items: center;"
                        colspan="{{ $colspanHeader }}">
                        {{ $json['tahun_ajaran'] }}
                    </th>
                </tr>
                <tr>
                    <th style="background-color: #3ac1ff; text-align: center; font-size: 10px; color: white; font-weight: 600;"
                        colspan="2">NOMOR</th>
                    <th style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width:  250px"
                        rowspan="3">
                        NAMA</th>
                    <th style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 30px; "
                        rowspan="3">
                        L/P</th>
                    <th style="background-color: #3ac1ff; text-align: center; font-size: 10px; color: white; font-weight: 600;"
                        colspan={{ $jumlahHari }}>
                        BULAN {{ $tanggalPresensi }}</th>
                    <th style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600;"
                        colspan="3" rowspan="2">
                        JMLH</th>
                </tr>

                <tr>
                    <th style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 30px;"
                        rowspan="2">NO</th>
                    <th style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 100px;"
                        rowspan="2">NIP</th>
                    <th style="background-color: #3ac1ff; text-align: center; font-size: 10px; color: white; font-weight: 600;"
                        colspan={{ $jumlahHari }}>TANGGAL</th>
                </tr>

                <tr>
                    @for ($i = 1; $i <= $jumlahHari; $i++) @php $isMinggu=in_array($i, $mingguList); @endphp <th
                        style=" text-align: center; font-size: 10px;  font-weight: 600; width: 30px; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : 'color: white; background-color: #3ac1ff;' }} ">
                        {{ $i }}
                        </th>
                        @endfor
                        <th
                            style="background-color: #3c9725; text-align: center; font-size: 10px; color: white; font-weight: 600; width: 30px;">
                            H</th>
                        <th
                            style="background-color:  #8f9725; text-align: center; font-size: 10px; color: white; font-weight: 600; width: 30px;">
                            I</th>
                        <th
                            style="background-color: #972525; text-align: center; font-size: 10px; color: white; font-weight: 600; width: 30px;">
                            S</th>

                </tr>
            </thead>
            <tbody>
                @foreach($json['guru'] as $guru)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $guru['nip'] }}</td>
                    <td>{{ $guru['nama_guru'] }}</td>
                    <td style=" text-align: center;">{{ $guru['jk']}}</td>
                    @for($i = 1; $i <= $jumlahHari; $i++) @php $currentDate=Carbon::createFromDate($tahun, $bulan, $i)->
                        format('Y-m-d');
                        $index = array_search($currentDate, $guru['tanggal']);
                        $status = ($index !== false) ? $guru['absen_masuk'][$index] : '';
                        $durasi = ($index !== false) ? $guru['durasi'][$index] : '';
                        $isMinggu = in_array($i, $mingguList);

                        @endphp
                        <td
                            style=" text-align: center; {{ $isMinggu ? 'background-color: #ffcccc; color: red;' : '' }}">
                            {{ $status }}
                        </td>
                        @endfor
                        @php
                        // Hitung jumlah tiap status
                        $statusCount = array_count_values($guru['absen_masuk']);
                        $hadir = $statusCount['H'] ?? 0;
                        $izin = $statusCount['I'] ?? 0;
                        $sakit = $statusCount['S'] ?? 0;

                        @endphp
                        <td style=" text-align: center;">{{ $hadir }}</td>
                        <td style=" text-align: center;">{{ $izin }}</td>
                        <td style=" text-align: center;">{{ $sakit }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="spacing"></div>

        <div class="footer">
            <table border="1" cellspacing="0" cellpadding="4">
                <thead>
                    <tr>
                        <th style="background-color: #3ac1ff; text-align: center; font-size: 10px; color: white; font-weight: 600;"
                            colspan="2">KETERANGAN</th>
                        <th style="background-color:  #3ac1ff; text-align: center; font-size: 10px; color: white; font-weight: 600;"
                            colspan="1">JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>I</td>
                        <td>Izin</td>
                        <td>{{ $json['total']['I']}}</td>
                    </tr>
                    <tr>
                        <td>S</td>
                        <td>Sakit</td>
                        <td>{{ $json['total']['S']}}</td>
                    </tr>
                    <tr>
                        <td>H</td>
                        <td>Hadir</td>
                        <td>{{ $json['total']['H']}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
</body>


</html>