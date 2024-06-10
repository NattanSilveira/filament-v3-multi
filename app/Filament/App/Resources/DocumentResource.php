<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DocumentResource\Pages;
use App\Filament\App\Resources\DocumentResource\RelationManagers;
use App\Models\Category;
use App\Models\Document;
use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    //getmodellabel
    public static function getModelLabel(): string
    {
        return __('Document');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('Enter the name of the document')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->required()
                    ->placeholder('Enter the description of the document')
                    ->columnSpanFull(),
                Forms\Components\Select::make('category_id')
//                    ->relationship('category', 'name')
//                    ->live()
                    ->label('Category')
//                    ->dehydrated(false)
                    ->options(
                        Category::all()->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('subcategory_id')
//                    ->relationship('subcategory', 'name')
                    ->label('Subcategory')
                    ->placeholder(fn (Forms\Get $get) => $get('category_id') ? 'Select a subcategory' : 'Select a category first')
                    ->options(
                        fn (Forms\Get $get) => $get('category_id') ? Subcategory::where('category_id', $get('category_id'))->get()->pluck('name', 'id') : []
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Toggle::make('should_notify')
                    ->label('Should Notify')
                    ->default(false)
                    ->reactive()  // Adiciona a reatividade ao toggle
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('notify_at')
                    ->label('Notify At')
                    ->nullable()
                    ->visible(fn ($get) => $get('should_notify'))
                    ->reactive(),  // Adiciona a reatividade ao campo

                Forms\Components\TagsInput::make('emails_to_notify')
                    ->label('Emails To Notify')
                    ->nullable()
                    ->visible(fn ($get) => $get('should_notify'))
                    ->reactive()
                    ->nestedRecursiveRules([
                        'email',
                    ])
                    ->validationMessages([
                        '*.email' => 'The email :input, must be a valid email address.',
                    ]),


                SpatieMediaLibraryFileUpload::make('document_files')
                    ->label('File')
                    ->multiple()
                    ->preserveFilenames()
                    ->disk('public')
                    ->downloadable()
                    ->reorderable()
                    ->openable()
                    ->required()
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
