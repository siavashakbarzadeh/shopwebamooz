<?php

namespace Jokoli\Payment\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jokoli\Course\Models\Course;
use Jokoli\Payment\Models\Payment;

class PaymentRepository
{
    private function query()
    {
        return Payment::query();
    }

    private function getSuccessSales()
    {
        return $this->query()->success();
    }

    public function paginate(Request $request)
    {
        return $this->query()
            ->filterEmail($request->email)
            ->filterAmount($request->amount)
            ->filterInvoiceId($request->invoice_id)
            ->filterFrom($request->from)
            ->filterTo($request->to)
            ->with(['buyer', 'paymentable'])
            ->latest()
            ->paginate(5)
            ->appends($request->only(['email', 'amount', 'invoice_id', 'from', 'to',]));
    }

    public function getUserPayments()
    {
        return auth()->user()->payments()
            ->with('paymentable')
            ->paginate();
    }

    public function store($paymentable, array $data, $discounts = [])
    {
        $paymentable = $paymentable->payments()->create($data);
        $paymentable->discounts()->sync($discounts);
        return $paymentable;
    }

    public function findByInvoiceId($invoice_id)
    {
        return $this->query()->where('invoice_id', $invoice_id)->first();
    }

    public function changeStatus($payment, $status)
    {
        return $payment->update(['status' => $status]);
    }

    public function getLastNDaysSales($days)
    {
        return $this->getSuccessSales()
            ->selectRaw('SUM(`site_share`) AS site_benefit,SUM(`amount`) AS total')
            ->whereDate('created_at', '>=', Carbon::yesterday()->subDays($days))
            ->whereDate('created_at', '<=', Carbon::yesterday())
            ->first();
    }

    public function last30DaysTransactions($days)
    {
        return $this->getSuccessSales()
            ->selectRaw('DATE(created_at) AS date,SUM(amount) AS total,SUM(site_share) AS site_benefit,SUM(seller_share) AS seller_benefit')
            ->whereDate('created_at', '>=', Carbon::yesterday()->subDays($days))
            ->whereDate('created_at', '<=', Carbon::yesterday())
            ->groupBy('date')
            ->get();
    }

    public function getAllSales()
    {
        return $this->getSuccessSales()
            ->selectRaw('SUM(`site_share`) AS site_benefit,SUM(`amount`) AS total')
            ->first();
    }

    public function getUserSales($user_id)
    {
        return $this->query()
            ->success()
            ->whereHasMorph('paymentable', Course::class, function (Builder $builder, $model) use ($user_id) {
                $builder->where('teacher_id', $user_id);
            });
    }

    public function getUserSumSales($user_id)
    {
        return $this->getUserSales($user_id)
            ->selectRaw('SUM(amount) AS total,SUM(site_share) AS site_benefit,SUM(seller_share) AS seller_benefit')
            ->first();
    }

    public function getTodayUserSales($user_id)
    {
        return $this->getUserSales($user_id)
            ->selectRaw('COUNT(*) AS sales_count,SUM(amount) AS total,SUM(seller_share) AS seller_benefit')
            ->whereDate('created_at', today())->first();
    }

    public function getLastNDaysUserSales($days, $user_id)
    {
        return $this->getUserSales($user_id)
            ->selectRaw('DATE(created_at) AS date,SUM(amount) AS total,SUM(site_share) AS site_benefit,SUM(seller_share) AS seller_benefit')
            ->whereDate('created_at', '>=', Carbon::yesterday()->subDays($days))
            ->whereDate('created_at', '<=', Carbon::yesterday())
            ->groupBy('date')
            ->get();
    }

    public function getLastNPayments($count, $user_id)
    {
        return $this->query()
            ->with(['buyer', 'paymentable'])
            ->where('buyer_id', $user_id)
            ->latest()
            ->limit($count)
            ->get();
    }
}
