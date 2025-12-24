<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Guru</title>

</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="13"
                    style="text-align:center; font-size:15px; height:50px; vertical-align: middle; background-color:#3a3dff; color: white;">
                    DATA GURU {{ $json['sekolah'] }} <br>
                    @if(!empty($json['status_dinas']))
                    <p style="margin:5px; text-align:center; font-weight: 400; font-size: 12px;">{{
                        $json['status_dinas'] }}</p>
                    @else
                    @endif
                </th>
            </tr>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 200px;">Nama</th>
                <th style="width: 200px;">Email</th>
                <th style="width: 100px;">NIP</th>
                <th style="width: 30px;">JK</th>
                <th style="width: 250px;">Alamat</th>
                <th style="width: 150px;">No HP</th>
                <th style="width: 130px;">Agama</th>
                <th style="width: 200px;">Pendidikan Terakhir</th>
                <th style="width: 200px;">Tempat Lahir</th>
                <th style="width: 200px;">Tanggal Lahir</th>
                <th style="width: 130px;">Shift</th>
                <th style="width: 150px;">Jabatan</th>
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