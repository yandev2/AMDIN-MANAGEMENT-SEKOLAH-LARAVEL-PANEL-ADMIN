<?php

namespace App\Filament\User\Resources\PresensiGurus;

use App\Filament\User\Resources\PresensiGurus\Pages\CreatePresensiGuru;
use App\Filament\User\Resources\PresensiGurus\Pages\EditPresensiGuru;
use App\Filament\User\Resources\PresensiGurus\Pages\ListPresensiGurus;
use App\Filament\User\Resources\PresensiGurus\Pages\ViewPresensiGuru;
use App\Filament\User\Resources\PresensiGurus\Schemas\PresensiGuruForm;
use App\Filament\User\Resources\PresensiGurus\Schemas\PresensiGuruInfolist;
use App\Filament\User\Resources\PresensiGurus\Tables\PresensiGurusTable;
use App\Models\PresensiGuru;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PresensiGuruResource extends Resource
{
    protected static ?string $model = PresensiGuru::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;
    protected static ?string $navigationLabel = "Presensi Guru";
    protected static ?string $pluralModelLabel = "Presensi Guru";
    protected static string | UnitEnum | null $navigationGroup = 'Presensi Management';
    protected static ?string $permissionGroup = 'User';
    protected static ?string $recordTitleAttribute = 'tanggal';

    public static function form(Schema $schema): Schema
    {
        return PresensiGuruForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PresensiGuruInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PresensiGurusTable::configure($table);
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
            'index' => ListPresensiGurus::route('/'),
            'create' => CreatePresensiGuru::route('/create'),
            'view' => ViewPresensiGuru::route('/{record}'),
            'edit' => EditPresensiGuru::route('/{record}/edit'),
        ];
    }
}
