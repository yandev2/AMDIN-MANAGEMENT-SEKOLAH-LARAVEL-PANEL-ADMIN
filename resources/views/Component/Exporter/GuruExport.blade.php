<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Guru</title>
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
            text-align: left;
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
    </style>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="13" style="text-align:center; font-size:15px; height:50px;">
                    DATA GURU {{ $json['sekolah'] }} <br>
                    @if(!empty($json['status_dinas']))
                    <p style="margin:5px; text-align:center; font-weight: 400; font-size: 12px;">{{
                        $json['status_dinas'] }}</p>
                    @else
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width: 1%;">No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NIP</th>
                <th style="width: 3%;">JK</th>
                <th style="width: 10%;">Alamat</th>
                <th>No HP</th>
                <th style="width: 7%;">Agama</th>
                <th>Pendidikan Terakhir</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Shift</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Contoh data -->

            @foreach ($json['data_guru'] as $data )
            <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $data['nama'] }}</td>
            <td>{{ $data['email']}}</td>
            <td>{{ $data['nip']}}</td>
            <td>{{ $data['jk']}}</td>
            <td>{{ $data['alamat']}}</td>
            <td>{{ $data['no_hp']}}</td>
            <td>{{ $data['agama']}}</td>
            <td>{{ $data['pendidikan_terakhir']}} </td>
            <td>{{ $data['tempat_lahir']}}</td>
            <td>{{ $data['tanggal_lahir']}}</td>
            <td>{{ $data['shift']}}</td>
            <td>{{ $data['jabatan']}}</td>
            </tr>
            @endforeach

        </tbody>

    </table>
</body>

</html>