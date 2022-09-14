<?php

namespace Jokoli\Payment\Services;

use Illuminate\Support\Facades\DB;
use Jokoli\Payment\Enums\SettlementStatus;
use Jokoli\Payment\Repository\SettlementRepository;

class SettlementService
{
    public static function store($request)
    {
        resolve(SettlementRepository::class)->store(auth()->user(), $request);
        auth()->user()->update(['balance' => auth()->user()->balance - $request->amount]);
        showFeedback();
    }

    public static function update($settlement, $request)
    {
        $settlementRepository = resolve(SettlementRepository::class);
        $settlement = $settlementRepository->findOrFailById($settlement);
        if ($settlementRepository->getLatestUserSettlementId($settlement->user_id) != $settlement->id){
            errorFeedback("عملیات ناموفق", "تنها آخرین درخواست تسویه کاربر قابل ویرایش میباشد");
            return redirect()->route('settlements.index');
        }
        try {
            return DB::transaction(function () use ($settlement, $settlementRepository, $request) {
                if (in_array($settlement->status, [SettlementStatus::Pending, SettlementStatus::Settled]) && in_array($request->status, [SettlementStatus::Canceled, SettlementStatus::Rejected]))
                    $settlement->user()->update(['balance' => $settlement->user->balance + $request->amount]);
                if (in_array($settlement->status, [SettlementStatus::Canceled, SettlementStatus::Rejected]) && in_array($request->status, [SettlementStatus::Pending, SettlementStatus::Settled])) {
                    if ($settlement->user->balance < $request->amount) {
                        errorFeedback("عملیات ناموفق", "موجودی حساب کاربر کافی نمیباشد");
                        return redirect()->route('settlements.edit', $settlement->id)->withInput();
                    }
                    $settlement->user()->update(['balance' => $settlement->user->balance - $request->amount]);
                }
                $settlementRepository->update($settlement, $request);
                showFeedback();
                return redirect()->route('settlements.index');
            });
        } catch (\Throwable $e) {
            errorFeedback();
            return redirect()->route('settlements.index');
        }
    }
}
