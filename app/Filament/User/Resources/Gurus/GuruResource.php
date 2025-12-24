<?php

namespace App\Filament\User\Resources\Gurus;

use App\Filament\User\Resources\Gurus\Pages\CreateGuru;
use App\Filament\User\Resources\Gurus\Pages\EditGuru;
use App\Filament\User\Resources\Gurus\Pages\ListGurus;
use App\Filament\User\Resources\Gurus\Pages\ViewGuru;
use App\Filament\User\Resources\Gurus\Schemas\GuruForm;
use App\Filament\User\Resources\Gurus\Schemas\GuruInfolist;
use App\Filament\User\Resources\Gurus\Tables\GurusTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class GuruResource extends Resource
{
    protected static ?string $model = User::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::User;
    protected static ?string $navigationLabel = "Guru";
    protected static ?string $pluralModelLabel = "Guru";
    protected static string | UnitEnum | null $navigationGroup = 'User Management';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $permissionGroup = 'User';

    public static function form(Schema $schema): Schema
    {
        return GuruForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GuruInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GurusTable::configure($table->modifyQueryUsing(
            fn($query) =>
            $query->where('id_sekolah', auth()->user()->id_sekolah)->whereHas('roles', fn($q) => $q->whereIn('name', ['guru', 'kepala_sekolah']))
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
            'index' => ListGurus::route('/'),
            'create' => CreateGuru::route('/create'),
            'view' => ViewGuru::route('/{record}'),
            'edit' => EditGuru::route('/{record}/edit'),
        ];
    }
 public static function getEloquentQuery(): Builder
    {
       return parent::getEloquentQuery()
        ->leftJoin(DB::raw(
            'LATERAL (
                SELECT g.tanggal_lahir
                FROM gurus g
                WHERE g.id_user = users.id
                LIMIT 1
            ) AS guru_data'
        ), DB::raw('true'), '=', DB::raw('true'))
        ->addSelect('users.*')
        ->addSelect(DB::raw('EXTRACT(YEAR FROM AGE(CURRENT_DATE, guru_data.tanggal_lahir)) AS usia'));
    }
}
