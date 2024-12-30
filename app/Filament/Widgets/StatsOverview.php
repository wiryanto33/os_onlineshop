<?php

namespace App\Filament\Widgets;

use App\Models\Agent;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\Stockist;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;


class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $distributor = Distributor::count();
        $agent = Agent::count();
        $stockist = Stockist::count();

        $pemasukan = Order::where('payment_status', 'paid')->sum('total_amount');
        $pending = Order::where('payment_status', 'unpaid')->sum('total_amount');
        $totalOrder = Order::count();

        return [
            Stat::make('Distributor Member', $distributor),
            Stat::make('Agent Member', $agent),
            Stat::make('Stockist Member', $stockist),
            Stat::make('Total Order', $totalOrder),
            Stat::make('Total Penjualan', 'Rp ' . number_format($pemasukan, 0, ',', '.') ),
            Stat::make('Total Pending', 'Rp ' . number_format($pending, 0, ',', '.') ),


        ];
    }
}
