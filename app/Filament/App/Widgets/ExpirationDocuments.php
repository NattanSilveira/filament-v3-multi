<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\DocumentResource;
use App\Models\Document;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ExpirationDocuments extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    //change header
    protected static ?string $heading = 'Documentos próximos ao vencimento';

    public function table(Table $table): Table
    {
        return $table
            ->query(DocumentResource::getEloquentQuery()->where('should_notify', true)->where('expiration_date', '>=', now()->addDays(7)))
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expiration_date')
                    ->label('Data de vencimento')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notify_at')
                    ->label('Notificar em')
                    ->dateTime('d/m/Y H:i')
                    ->searchable()
                    ->sortable(),

            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->url(fn (Document $record): string => DocumentResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
