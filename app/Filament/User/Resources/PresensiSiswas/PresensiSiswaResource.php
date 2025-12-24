<?php

namespace App\Filament\User\Resources\PresensiSiswas;

use App\Filament\User\Resources\PresensiSiswas\Pages\CreatePresensiSiswa;
use App\Filament\User\Resources\PresensiSiswas\Pages\EditPresensiSiswa;
use App\Filament\User\Resources\PresensiSiswas\Pages\ListPresensiSiswas;
use App\Filament\User\Resources\PresensiSiswas\Pages\ViewPresensiSiswa;
use App\Filament\User\Resources\PresensiSiswas\Schemas\PresensiSiswaForm;
use App\Filament\User\Resources\PresensiSiswas\Schemas\PresensiSiswaInfolist;
use App\Filament\User\Resources\PresensiSiswas\Tables\PresensiSiswasTable;
use App\Models\PresensiSiswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PresensiSiswaResource extends Resource
{
    protected static ?string $model = PresensiSiswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
    protected static ?string $navigationLabel = "Presensi Siswa";
    protected static ?string $pluralModelLabel = "Presensi Siswa";
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';
    protected static ?string $permissionGroup = 'User';
    protected static ?string $recordTitleAttribute = 'tanggal';

    public static function form(Schema $schema): Schema
    {
        return PresensiSiswaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PresensiSiswaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresensiSiswasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPresensiSiswas::route('/'),
            'create' => CreatePresensiSiswa::route('/create'),
            'view' => ViewPresensiSiswa::route('/{record}'),
            'edit' => EditPresensiSiswa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('tanggal', 'desc'); // urut dari terbaru
    }
}
