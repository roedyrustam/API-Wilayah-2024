<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemilihResource\Pages;
use App\Filament\Resources\PemilihResource\RelationManagers;
use App\Models\Pemilih;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PemilihResource extends Resource
{
    protected static ?string $model = Pemilih::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('provinsi_id')
                    ->relationship('provinsi', 'name')
                    ->required(),
                Forms\Components\Select::make('kabupaten_id')
                    ->relationship('kabupaten', 'name')
                    ->required(),
                Forms\Components\Select::make('kecamatan_id')
                    ->relationship('kecamatan', 'name')
                    ->required(),
                Forms\Components\Select::make('kelurahan_id')
                    ->relationship('kelurahan', 'name')
                    ->required(),
                Forms\Components\Select::make('tps_id')
                    ->relationship('tps', 'name')
                    ->required(),
                Forms\Components\TextInput::make('nkk')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->maxLength(16),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->required(),
                Forms\Components\Select::make('jenis_kelamin')
                    ->required()
                    ->options([
                        'laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',

                    ]),
                Forms\Components\TextInput::make('alamat_ktp')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('agama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status_perkawinan')
                    ->required()
                    ->options([
                        'lajang' => 'Belum Menikah',
                        'menikah' => 'Menikah',
                        'cerai mati' => 'Cerai Mati',
                        'cerai hidup' => 'Cerai Hidup',

                    ]),
                Forms\Components\TextInput::make('pekerjaan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('keterangan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status_dpt')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('afiliasi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_hired')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provinsi.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kabupaten.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kecamatan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelurahan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tps.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nkk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat_ktp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_perkawinan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_dpt')
                    ->searchable(),
                Tables\Columns\TextColumn::make('afiliasi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_hired')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListPemilihs::route('/'),
            'create' => Pages\CreatePemilih::route('/create'),
            'view' => Pages\ViewPemilih::route('/{record}'),
            'edit' => Pages\EditPemilih::route('/{record}/edit'),
        ];
    }
}
