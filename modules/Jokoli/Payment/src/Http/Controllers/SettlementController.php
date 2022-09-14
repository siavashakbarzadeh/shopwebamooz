<?php

namespace Jokoli\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jokoli\Payment\Http\Requests\SettlementRequest;
use Jokoli\Payment\Models\Settlement;
use Jokoli\Payment\Repository\SettlementRepository;
use Jokoli\Payment\Services\SettlementService;

class SettlementController extends Controller
{
    private SettlementRepository $settlementRepository;

    public function __construct(SettlementRepository $settlementRepository)
    {
        $this->settlementRepository = $settlementRepository;
    }

    public function index(Request $request)
    {
        $this->authorize('index', Settlement::class);
        $settlements = $this->settlementRepository->paginate($request);
        return view('Payment::settlement.index', compact('settlements'));
    }

    public function create()
    {
        $this->authorize('create', Settlement::class);
        if ($this->settlementRepository->userHasPendingSettlement(auth()->user())) {
            errorFeedback("عملیات ناموفق", "شما یک درخواست تسویه حساب در حال بررسی دارید");
            return redirect()->route('settlements.index');
        }
        return view('Payment::settlement.create');
    }

    public function store(SettlementRequest $request)
    {
        $this->authorize('create', Settlement::class);
        if ($this->settlementRepository->userHasPendingSettlement(auth()->user())) {
            errorFeedback("عملیات ناموفق", "شما یک درخواست تسویه حساب در حال بررسی دارید");
            return redirect()->route('settlements.index');
        }
        SettlementService::store($request);
        return redirect()->route('settlements.index');
    }

    public function edit($settlement)
    {
        $this->authorize('manage', Settlement::class);
        $settlement = $this->settlementRepository->findOrFailById($settlement);
        if ($this->settlementRepository->getLatestUserSettlementId($settlement->user_id) != $settlement->id) {
            errorFeedback("عملیات ناموفق", "تنها آخرین درخواست تسویه کاربر قابل ویرایش میباشد");
            return redirect()->route('settlements.index');
        }
        return view('Payment::settlement.edit', compact('settlement'));
    }

    public function update(SettlementRequest $request, $settlement)
    {
        $this->authorize('manage', Settlement::class);
        return SettlementService::update($settlement, $request);
    }
}
