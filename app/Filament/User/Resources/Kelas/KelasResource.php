<?php

namespace App\Filament\User\Resources\Kelas;

use App\Filament\User\Resources\Kelas\Pages\CreateKelas;
use App\Filament\User\Resources\Kelas\Pages\EditKelas;
use App\Filament\User\Resources\Kelas\Pages\ListKelas;
use App\Filament\User\Resources\Kelas\Pages\ViewKelas;
use App\Filament\User\Resources\Kelas\RelationManagers\MapelRelationManager;
use App\Filament\User\Resources\Kelas\RelationManagers\PresensiRelationManager;
use App\Filament\User\Resources\Kelas\RelationManagers\SiswaRelationManager;
use App\Filament\User\Resources\Kelas\Schemas\KelasForm;
use App\Filament\User\Resources\Kelas\Schemas\KelasInfolist;
use App\Filament\User\Resources\Kelas\Tables\KelasTable;
use App\Models\Kelas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice;

    protected static ?string $recordTitleAttribute = 'nama_kelas';
    protected static ?string $navigationLabel = "Kelas";
    protected static ?string $pluralModelLabel = "Kelas";
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    public static function form(Schema $schema): Schema
    {
        return KelasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KelasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KelasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SiswaRelationManager::class,
            MapelRelationManager::class,
            PresensiRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKelas::route('/'),
            'create' => CreateKelas::route('/create'),
            'view' => ViewKelas::route('/{record}'),
            'edit' => EditKelas::route('/{record}/edit'),
        ];
    }
}
