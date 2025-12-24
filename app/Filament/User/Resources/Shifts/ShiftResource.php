<?php

namespace App\Filament\User\Resources\Shifts;

use App\Filament\User\Resources\Shifts\Pages\CreateShift;
use App\Filament\User\Resources\Shifts\Pages\EditShift;
use App\Filament\User\Resources\Shifts\Pages\ListShifts;
use App\Filament\User\Resources\Shifts\Pages\ViewShift;
use App\Filament\User\Resources\Shifts\Schemas\ShiftForm;
use App\Filament\User\Resources\Shifts\Schemas\ShiftInfolist;
use App\Filament\User\Resources\Shifts\Tables\ShiftsTable;
use App\Models\Shift;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Clock;

    protected static ?string $recordTitleAttribute = 'nama_shift';
    protected static ?string $navigationLabel = "Shift";
    protected static ?string $pluralModelLabel = "Shift";
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';


    public static function form(Schema $schema): Schema
    {
        return ShiftForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShiftInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftsTable::configure($table);
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
            'index' => ListShifts::route('/'),
            'create' => CreateShift::route('/create'),
            'view' => ViewShift::route('/{record}'),
            'edit' => EditShift::route('/{record}/edit'),
        ];
    }
}
