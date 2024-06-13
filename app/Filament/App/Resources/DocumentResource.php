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
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('Document');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->placeholder('Enter the name of the document')
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('description')
                    ->label('Descrição')
                    ->required()
                    ->placeholder('Insira a descrição do documento')
//                    block image upload
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->columnSpanFull(),

                Forms\Components\Select::make('category_id')
                    ->label('Categoria')
                    ->options(
                        Category::all()->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('subcategory_id')
                    ->label('Subcategory')
                    ->placeholder(fn (Forms\Get $get) => $get('category_id') ? 'Select a subcategory' : 'Select a category first')
                    ->options(
                        fn (Forms\Get $get) => $get('category_id') ? Subcategory::where('category_id', $get('category_id'))->get()->pluck('name', 'id') : []
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\DateTimePicker::make('expiration_date')
                    ->label('Data de vencimento')
                    ->nullable(),

                Forms\Components\Toggle::make('should_notify')
                    ->label('Habilitar notificação')
                    ->default(false)
                    ->reactive()  // Adiciona a reatividade ao toggle
                    ->columnSpanFull(),

                Forms\Components\DateTimePicker::make('notify_at')
                    ->label('Data de notificação')
                    ->nullable()
                    ->visible(fn ($get) => $get('should_notify'))
                    ->reactive(),  // Adiciona a reatividade ao campo

                Forms\Components\TagsInput::make('emails_to_notify')
                    ->label('Emails para notificar')
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
                    ->label('Arquivos')
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
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('should_notify')
                    ->label('Notificação')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->boolean(),
                TextColumn::make('expiration_date')
                    ->label('Data de vencimento')
                    ->searchable()
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
//                TextColumn::make('files_count')
//                    ->label('Quantidade de Arquivos')
//                    ->getStateUsing(function ($record) {
//                        return $record->files_count;
//                    })
//                    ->sortable(),
//                TextColumn::make('files_size')
//                    ->label('Tamanho dos Arquivos')
//                    ->getStateUsing(function ($record) {
//                        $size = function ($size) {
//                            if ($size < 1000000) {
//                                return Number::format($size / 1000, 2) . ' KB';
//                            }
//                            if ($size < 1000000000) {
//                                return Number::format($size / 1000000, 2) . ' MB';
//                            }
//                            return Number::format($size / 1000000000, 2) . ' GB';
//                        };
//                        return $size($record->files_size);
//                    })
//                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Data de criação')
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
