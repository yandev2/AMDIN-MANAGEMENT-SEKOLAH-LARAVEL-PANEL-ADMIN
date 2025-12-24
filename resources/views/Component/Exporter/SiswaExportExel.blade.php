<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
</head>

<body>
    @php
    $kelas = $json['kelas'];
    @endphp
    <table border="1" cellspacing="0" cellpadding="2" style="width: 100%">
        <thead>
            <tr>
                <th colspan="11"
                    style="text-align: center; font-size:15px; padding: 50px; font-weight: 800;  background-color:#3a3dff; color: white; align-content: center; align-items: center;">
                    DATA SISWA {{ $json['sekolah'] }}
                </th>
            </tr>
            <tr>
                <th style="text-align: center; font-size:12px; padding: 50px;  background-color:#3a3dff; color: white; align-content: center; align-items: center;"
                    colspan="11">
                    {{ $kelas != null ? 'Kelas ' . $json['kelas'] . '. Wali Kelas ' . $json['wali_kelas'] : 'Daftar
                    Siswa Semua Kelas' }}
                </th>
            </tr>
            <tr>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 30px;">
                    No</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 200px;">
                    Nama</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 150px;">
                    Nis</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 150px;">
                    Nisn</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 30px;">
                    JK</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 50px;">
                    Kelas</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 250px;">
                    Alamat</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 100px;">
                    Tahun Masuk</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 100px;">
                    Agama</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 150px;">
                    Tanggal Lahir</th>
                <th
                    style="background-color: #3ac1ff; text-align: center; vertical-align: middle; font-size: 10px; color: white; font-weight: 600; width: 150px;">
                    Nik</th>
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