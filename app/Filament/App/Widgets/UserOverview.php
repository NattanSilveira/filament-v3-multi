<?php

namespace App\Filament\App\Widgets;

use App\Models\Document;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class UserOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $documentsCount = Document::count();

        $documentsSize = Document::all()->map(function ($document) {
            return $document->getMedia()->map(function ($media) {
                return $media->size;
            })->sum();
        })->sum();

        //todo trocar pelo plano do usuário
        $planSize = 1000000000;

        $sizeLabel = function ($size) {
            if ($size < 1000000) {
                return Number::format($size / 1000, 2) . 'KB';
            }
            if ($size < 1000000000) {
                return Number::format($size / 1000000, 2) . 'MB';
            }
            return Number::format($size / 1000000000, 2) . 'GB';
        };

        $completed = $documentsSize / $planSize * 100;

        $completeLabel = function ($completed) {
            if ($completed < 100) {
                return Number::format($completed, 2) . '%';
            }
            return '100%';
        };

        return [
            Stat::make('Documentos', $documentsCount)
                ->description('Documentos cadastrados')
                ->descriptionIcon('heroicon-m-document')
                ->color('success'),
            Stat::make('Espaço consumido', $sizeLabel($documentsSize) . ' / ' . $sizeLabel($planSize))
                ->description('Ocupado ' . $completeLabel($completed))
                ->descriptionIcon('heroicon-m-chart-pie')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
