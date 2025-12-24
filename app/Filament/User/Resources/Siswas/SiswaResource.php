<?php

namespace App\Filament\User\Resources\Siswas;

use App\Filament\User\Resources\Siswas\Pages\CreateSiswa;
use App\Filament\User\Resources\Siswas\Pages\EditSiswa;
use App\Filament\User\Resources\Siswas\Pages\ListSiswas;
use App\Filament\User\Resources\Siswas\Pages\ViewSiswa;
use App\Filament\User\Resources\Siswas\Schemas\SiswaForm;
use App\Filament\User\Resources\Siswas\Schemas\SiswaInfolist;
use App\Filament\User\Resources\Siswas\Tables\SiswasTable;
use App\Models\Siswa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static ?string $navigationLabel = "Siswa";
    protected static ?string $pluralModelLabel = "Siswa";
    protected static string | UnitEnum | null $navigationGroup = 'User Management';
    protected static ?string $permissionGroup = 'User';
    protected static ?string $recordTitleAttribute = 'nama_siswa';

    public static function form(Schema $schema): Schema
    {
        return SiswaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SiswaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiswasTable::configure($table);
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
            'index' => ListSiswas::route('/'),
            'create' => CreateSiswa::route('/create'),
            'view' => ViewSiswa::route('/{record}'),
            'edit' => EditSiswa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
       return parent::getEloquentQuery()
        ->addSelect('siswas.*')
        ->addSelect(DB::raw('EXTRACT(YEAR FROM AGE(CURRENT_DATE, tanggal_lahir)) AS usia'));
    }
}
