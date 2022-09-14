<?php

namespace Jokoli\Ticket\Repository;

use Illuminate\Database\Eloquent\Builder;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Ticket\Models\Ticket;

class TicketRepository
{
    private function query()
    {
        return Ticket::query();
    }

    public function findOrFailById($ticket)
    {
        return $this->query()
            ->with('replies')
            ->findOrFail($ticket);
    }

    public function paginate($request)
    {
        return $this->query()
            ->with('user')
            ->withAggregate('latest_reply', 'user_id')
            ->withAggregate('latest_reply', 'created_at')
            ->when(auth()->user()->can(Permissions::ManageTickets), function (Builder $builder) use ($request) {
                $builder->filterAnswerStatus($request->status)
                    ->filterEmail($request->email)
                    ->filterName($request->name)
                    ->filterDate($request->date);
            }, function (Builder $builder) {
                $builder->where('user_id', auth()->id());
            })->paginate()
            ->appends(['status','email','name','date']);
    }

    public function store($request)
    {
        return auth()->user()->tickets()->create([
            'title' => $request->title,
        ]);
    }

    public function updateStatus($ticket, $status)
    {
        return $ticket->update(['status' => $status]);
    }

    public function delete($ticket)
    {
        return $ticket->delete();
    }
}
