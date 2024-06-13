<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class Overview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {

        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $isBusinessCustomersOnly = $this->filters['businessCustomersOnly'] ?? null;
        $businessCustomerMultiplier = match (true) {
            boolval($isBusinessCustomersOnly) => 2 / 3,
            blank($isBusinessCustomersOnly) => 1,
            default => 1 / 3,
        };

        $diffInDays = $startDate ? $startDate->diffInDays($endDate) : 0;

        $revenue = (int) (($startDate ? ($diffInDays * 137) : 192100) * $businessCustomerMultiplier);
        $newCustomers = (int) (($startDate ? ($diffInDays * 7) : 1340) * $businessCustomerMultiplier);
        $newOrders = (int) (($startDate ? ($diffInDays * 13) : 3543) * $businessCustomerMultiplier);

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'k';
            }

            return Number::format($number / 1000000, 2) . 'm';
        };

        return [
            Stat::make('Usuários', 10)
                ->description('32k increase') //todo pensar em adicionar aumento semanal
                ->descriptionIcon('heroicon-m-arrow-trending-up')
//                ->chart([7, 2, 10, 3, 15, 4, 17]) //todo ajustar o gráfico para esse historico semanal pegando as informações do banco das 6 ultimas semanas por exemplo
                ->color('success'),
            Stat::make('Assinatura ativa', $formatNumber($newCustomers))
                ->description('3% decrease') //todo pensar em adicionar aumento semanal
                ->descriptionIcon('heroicon-m-arrow-trending-down') //todo pensar em adicionar aumento semanal
//                ->chart([17, 16, 14, 15, 14, 13, 12]) //todo ajustar o gráfico para esse historico semanal pegando as informações do banco das 6 ultimas semanas por exemplo
                ->color('danger'),
            Stat::make('Novas assinaturas', $formatNumber($newOrders))
                ->description('7% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('success'),
        ];
    }
}
