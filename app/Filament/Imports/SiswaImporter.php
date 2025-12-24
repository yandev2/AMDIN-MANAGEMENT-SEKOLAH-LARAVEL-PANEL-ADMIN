<?php

namespace App\Filament\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Log;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama_siswa')->requiredMapping(),
            ImportColumn::make('nama_siswa')->requiredMapping(),
            ImportColumn::make('nis')->requiredMapping(),
            ImportColumn::make('nisn')->requiredMapping(),
            ImportColumn::make('jk')->requiredMapping(),
            ImportColumn::make('tempat_lahir')->requiredMapping(),
            ImportColumn::make('tanggal_lahir')->requiredMapping(),
            ImportColumn::make('agama')->requiredMapping(),
            ImportColumn::make('alamat')->requiredMapping(),
            ImportColumn::make('tahun_masuk')->requiredMapping(),
            ImportColumn::make('nik')->requiredMapping(),
            ImportColumn::make('no_kk')->requiredMapping(),
        ];
    }

    public function resolveRecord(): Siswa
    {
        $data = $this->data;
        $siswaKey = [
            'nama_siswa',
            'nis',
            'nisn',
            'jk',
            'tempat_lahir',
            'tanggal_lahir',
            'agama',
            'alamat',
            'tahun_masuk',
            'nik',
            'no_kk',
            'kelas',
            'foto'
        ];
        $siswaData = [];

        foreach ($siswaKey as $key) {
            if (isset($data[$key])) {
                $siswaData[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        $siswaData['foto'] = $siswaData['jk'] == 'L' ? 'siswa/default_lk.png' : 'siswa/default_p.png';

        $siswaData['id_kelas'] = optional(
            Kelas::where('nama_kelas', 'ILIKE', $data['kelas'] ?? null)
                ->where('id_sekolah', $this->options['id_sekolah'])
                ->first()
        )->id;

        $nokSiswa = [
            'hubungan' => $this->data['nok_hubungan'],
            'nama' => $this->data['nok_nama'],
            'no_tlp' => $this->data['nok_no_tlp'],
            'id_sekolah' => $this->options['id_sekolah'],
        ];

        $siswa =  Siswa::firstOrCreate(["nis" => $siswaData['nis']], $siswaData);
        $siswa->nokSiswa()->updateOrCreate(['nama' => $nokSiswa['nama']], $nokSiswa);
        return $siswa;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your siswa import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
