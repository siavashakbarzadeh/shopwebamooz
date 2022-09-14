<?php

namespace Jokoli\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Jokoli\Payment\Repository\PaymentRepository;

class DashboardController extends Controller
{
    public function home(PaymentRepository $paymentRepository)
    {
        $totalSales = $paymentRepository->getUserSumSales(auth()->id());
        $todaySales=$paymentRepository->getTodayUserSales(auth()->id());
        $last30Dates = CarbonPeriod::create(Carbon::yesterday()->subDays(30), Carbon::yesterday());
        $last30DaysSales=$paymentRepository->getLastNDaysUserSales(30,auth()->id());
        $last30DaysUserBenefit=$last30DaysSales->sum('seller_benefit');
        $last30DaysSiteBenefit=$last30DaysSales->sum('site_benefit');
        $last5Payments=$paymentRepository->getLastNPayments(5,auth()->id());
        return view('Dashboard::index',compact('totalSales','todaySales','last30Dates','last30DaysSales','last30DaysUserBenefit','last30DaysSiteBenefit','last5Payments'));
    }
}
