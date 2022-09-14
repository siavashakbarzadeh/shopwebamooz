<?php

namespace Jokoli\Payment\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Jokoli\Payment\Models\Settlement;
use Jokoli\Permission\Enums\Permissions;

class SettlementRepository
{
    private function query()
    {
        return Settlement::query();
    }

    public function findOrFailById($settlement)
    {
        return $this->query()->findOrFail($settlement);
    }

    public function paginate(Request $request)
    {
        return $this->query()
            ->with('user')
            ->filterStatus($request->status)
            ->filterCard($request->card)
            ->filterTransactionId($request->transaction_id)
            ->filterDate($request->date)
            ->filterEmail($request->email)
            ->filterName($request->name)
            ->when(!auth()->user()->can(Permissions::ManageSettlements), function (Builder $builder) {
                $builder->where('user_id', auth()->id());
            })->latest()
            ->paginate()
            ->appends($request->only(['status', 'card', 'transaction_id', 'date', 'email', 'name',]));
    }

    public function store($user, $request)
    {
        return $user->settlements()->create([
            'from' => $request->from,
            'amount' => $request->amount,
        ]);
    }

    public function update($settlement, $request)
    {
        return $settlement->update([
            'from' => $request->from,
            'to' => $request->to,
            'status' => $request->status,
        ]);
    }

    public function getUserSettlementBalance($settlement)
    {
        return $this->query()
            ->withAggregate('user', 'balance')
            ->findOrFail($settlement)
            ->user_balance;
    }

    public function userHasPendingSettlement($user)
    {
        return $this->query()
            ->pending()
            ->where('user_id', $user->id)
            ->latest()
            ->exists();
    }

    public function getLatestUserSettlementId($user_id)
    {
        return $this->query()
            ->where('user_id', $user_id)
            ->latest()
            ->first()->id;
    }
}
