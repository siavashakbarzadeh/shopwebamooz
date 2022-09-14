<?php

namespace Jokoli\Ticket\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jokoli\Common\Responses\AjaxResponses;
use Jokoli\Media\Services\MediaFileService;
use Jokoli\Ticket\Enums\TicketStatus;
use Jokoli\Ticket\Http\Requests\TicketReplyRequest;
use Jokoli\Ticket\Http\Requests\TicketRequest;
use Jokoli\Ticket\Models\Ticket;
use Jokoli\Ticket\Repository\TicketRepository;
use Jokoli\Ticket\Services\TicketReplyService;

class TicketController extends Controller
{
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    public function index(Request $request)
    {
        $tickets = $this->ticketRepository->paginate($request);
        return view('Ticket::index', compact('tickets'));
    }

    public function show($ticket)
    {
        $ticket = $this->ticketRepository->findOrFailById($ticket);
        $this->authorize('show', $ticket);
        return view('Ticket::show', compact('ticket'));
    }

    public function create()
    {
        return view('Ticket::create');
    }

    public function store(TicketRequest $request)
    {
        $ticket = $this->ticketRepository->store($request);
        TicketReplyService::store($ticket, $request);
        showFeedback();
        return redirect()->route('tickets.index');
    }

    public function destroy($ticket)
    {
        $this->authorize('destroy', Ticket::class);
        $ticket = $this->ticketRepository->findOrFailById($ticket);
        try {
            $this->ticketRepository->delete($ticket);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function reply(TicketReplyRequest $request, $ticket)
    {
        $ticket = $this->ticketRepository->findOrFailById($ticket);
        $this->authorize('reply', $ticket);
        if ($ticket->status != TicketStatus::Open)
            $this->ticketRepository->updateStatus($ticket, TicketStatus::Open);
        TicketReplyService::store($ticket, $request);
        showFeedback();
        return redirect()->route('tickets.show', $ticket->id);
    }

    public function close($ticket)
    {
        $ticket = $this->ticketRepository->findOrFailById($ticket);
        $this->authorize('close', $ticket);
        try {
            $status = TicketStatus::Close();
            $this->ticketRepository->updateStatus($ticket, $status->value);
            return AjaxResponses::successResponse([
                'status' => $status->description,
                'class' => $status->getCssClass(),
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }
}
