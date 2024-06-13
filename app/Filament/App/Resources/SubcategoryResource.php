<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SubcategoryResource\Pages;
use App\Filament\App\Resources\SubcategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubcategoryResource extends Resource
{
    protected static ?string $model = Subcategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Categorias/Subcategorias';

    public static function getModelLabel(): string
    {
        return __('Subcategory');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->placeholder('Insira o nome da subcategoria')
                    ->columnSpanFull(),
                Forms\Components\Select::make('category_id')
                    ->label('Categoria')
                    ->options(
                        Category::all()->pluck('name', 'id')
                    )
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Descrição')
                    ->required()
                    ->placeholder('Insira a descrição da subcategoria')
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->description(fn (Subcategory $record) => $record->description)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSubcategories::route('/'),
            'create' => Pages\CreateSubcategory::route('/create'),
            'edit' => Pages\EditSubcategory::route('/{record}/edit'),
        ];
    }
}
