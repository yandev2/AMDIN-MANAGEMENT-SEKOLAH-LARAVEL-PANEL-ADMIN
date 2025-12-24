<?php

namespace App\Filament\User\Resources\Sekolahs;

use App\Filament\User\Resources\Sekolahs\Pages\CreateSekolah;
use App\Filament\User\Resources\Sekolahs\Pages\EditSekolah;
use App\Filament\User\Resources\Sekolahs\Pages\ListSekolahs;
use App\Filament\User\Resources\Sekolahs\Pages\ViewSekolah;
use App\Filament\User\Resources\Sekolahs\Schemas\SekolahForm;
use App\Filament\User\Resources\Sekolahs\Schemas\SekolahInfolist;
use App\Filament\User\Resources\Sekolahs\Tables\SekolahsTable;
use App\Models\Sekolah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SekolahResource extends Resource
{
    protected static ?string $model = Sekolah::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice;

    protected static ?string $recordTitleAttribute = 'nama_sekolah';
    protected static ?string $navigationLabel = "Sekolah";
    protected static ?string $pluralModelLabel = "Sekolah";


    public static function form(Schema $schema): Schema
    {
        return SekolahForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SekolahInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SekolahsTable::configure($table->modifyQueryUsing(
            fn($query) =>
            $query->where('id', auth()->user()->id_sekolah)
        ));
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
            'index' => ListSekolahs::route('/'),
            'create' => CreateSekolah::route('/create'),
            'view' => ViewSekolah::route('/{record}'),
            'edit' => EditSekolah::route('/{record}/edit'),
        ];
    }
}
