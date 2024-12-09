<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Country;
use App\Models\Department;
use App\Models\Employee;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Manage users';

    protected static ?string $recordTitleAttribute = 'firstname';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('country_id')
                    ->label('Country')
                    ->relationship('country', 'name')
                    ->preload()
                    ->searchable()
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('state_id')
                    ->label('State')
                    ->relationship('state', 'name', function ($query, $get) {
                        $countryId = $get('country_id');
                        if ($countryId) {
                            $query->where('country_id', $countryId);
                        }
                    })
                    ->preload()
                    ->searchable()
                    // ->dependsOn('country_id')
                    ->required(),
                Forms\Components\Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('firstname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lastname')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('Profile Picture')
                    ->default('image/default.png'),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->numeric()
                    ->prefix('₦')
                    ->default(null),
                Forms\Components\TextInput::make('address')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Textarea::make('bio')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('zipcode')
                    ->numeric()
                    ->default(null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary')
                    ->prefix('₦')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('department')
                    ->relationship('department', 'name'),
                SelectFilter::make('state')
                    ->relationship('state', 'name'),
                SelectFilter::make('country')
                    ->relationship('country', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('country.name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('state.name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('department.name')
                    ->weight(FontWeight::Bold),
                TextEntry::make('firstname')
                    ->weight(FontWeight::Bold),
                TextEntry::make('lastname')
                    ->weight(FontWeight::Bold),
                TextEntry::make('date_of_birth')
                    ->weight(FontWeight::Bold),
                TextEntry::make('salary')
                    ->weight(FontWeight::Bold),
                TextEntry::make('address')
                    ->weight(FontWeight::Bold),
                TextEntry::make('zipcode')
                    ->weight(FontWeight::Bold),
                TextEntry::make('bio')
                    ->weight(FontWeight::Bold),
            ]);
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            // 'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
