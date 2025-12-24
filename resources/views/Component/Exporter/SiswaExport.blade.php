<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            /* font agak kecil agar muat di A4 */
            margin: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;

            /* supaya kolom tetap rapi */
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            word-wrap: break-word;
            /* pecah kata panjang agar tidak meluber */
            text-align: center;
        }


        th {
            background-color: #3a3dff;
            color: white;
        }

        thead {
            display: table-header-group;
            /* penting supaya header muncul di tiap halaman PDF */
        }

        tbody {
            display: table-row-group;
        }

        tbody,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            word-wrap: break-word;
            /* pecah kata panjang agar tidak meluber */
            text-align: left;
        }
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="11" style="font-size: medium; padding: 10px;">
                    DATA SISWA {{ $json['sekolah'] }}<br>
                    @if(!empty($json['kelas']))
                    <p style="margin:5px; text-align:center; font-weight: 400; font-size: 12px;">
                       Kelas {{$json['kelas'] }}. Wali Kelas {{ $json['wali_kelas'] }}</p>
                    @else
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width: 1%;">No</th>
                <th style="width: 15%;">Nama</th>
                <th style="width: 12%;">Nis</th>
                <th style="width: 12%;">Nisn</th>
                <th style="width: 3%;">JK</th>
                <th style="width: 5%;">Kelas</th>
                <th style="width: 15%;">Alamat</th>
                <th style="width: 1%;">Tahun Masuk</th>
                <th style="width: 7%;">Agama</th>
                <th>Tanggal Lahir</th>
                <th>Nik</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($json['data_siswa'] as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data['nama_siswa'] }}</td>
                <td>{{ $data['nis'] }}</td>
                <td>{{ $data['nisn'] }}</td>
                <td>{{ $data['jk'] }}</td>
                <td>{{ $data['kelas'] }}</td>
                <td>{{ $data['alamat'] }}</td>
                <td>{{ $data['tahun_masuk'] }}</td>
                <td>{{ $data['agama'] }}</td>
                <td>{{ $data['tanggal_lahir'] }}</td>
                <td>{{ $data['nik'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>