<?php

namespace App\Filament\Imports;

use App\Models\Guru;
use App\Models\Jabatan;
use App\Models\Shift;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;
    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->example('Nata S.Kom')
                ->requiredMapping(),
            ImportColumn::make('email')
                ->example('nata@gmail.com')
                ->requiredMapping(),
        ];
    }

    public function resolveRecord(): User
    {
        $data = $this->data;
        $guruKey = [
            'nip',
            'jk',
            'alamat',
            'status_dinas',
            'status',
            'no_hp',
            'agama',
            'pendidikan_terakhir',
            'tempat_lahir',
            'tanggal_lahir',
            'shift',
            'jabatan',
        ];

        $guruData = [];

        foreach ($guruKey as $key) {
            if (isset($data[$key])) {
                if ($key === 'status') {
                    $guruData[$key] = optional((object) $data)->$key ?? 'active';
                } elseif ($key === 'status_dinas') {
                    $guruData[$key] = optional((object) $data)->$key ?? 'dinas dalam';
                } else {
                    $guruData[$key] = $data[$key];
                }
                unset($data[$key]);
            }
        }
        $guruData['id_shift'] = optional(
            Shift::where('nama_shift', $guruData['shift'] ?? null)
                ->where('id_sekolah', $this->options['id_sekolah'])
                ->first()
        )->id;

        $guruData['id_jabatan'] = $guruData['jabatan'] === 'kepala sekolah' ? null : optional(
            Jabatan::where('nama_jabatan', $guruData['jabatan'] ?? null)
                ->where('id_sekolah', $this->options['id_sekolah'])
                ->first()
        )->id;

        $userData = [
            'name' => $this->data['name'],
            'email' => $this->data['email'],
            'password' => '12345',
            'foto' => $guruData['jk'] == 'L' ? 'guru/default_sys_l.jpg' : 'guru/default_sys.jpg',
            'id_sekolah' => $this->options['id_sekolah'],
        ];

        $users =  User::firstOrCreate(['email' => $userData['email']], $userData);

        $users->guru()->updateOrCreate(['id_user' => $users->id], $guruData);
        $users->assignRole('guru');

        return $users;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
