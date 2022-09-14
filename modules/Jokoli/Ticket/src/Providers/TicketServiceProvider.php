<?php

namespace Jokoli\Ticket\Providers;

use App\Models\TicketReply;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jokoli\Discount\Models\Discount;
use Jokoli\Discount\Policies\DiscountPolicy;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Ticket\Models\Ticket;
use Jokoli\Ticket\Policies\TicketPolicy;
use Jokoli\Ticket\Policies\TicketReplyPolicy;

class TicketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->_loadMigrations();
        $this->_loadViews();
        $this->_loadTranslations();
    }

    public function boot()
    {
        $this->_sidebar();
        $this->_register_policies();
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Ticket');
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.tickets', [
            'name' => 'تیکت ها',
            'icon' => 'i-tickets',
            'route' => 'tickets.index',
            'routes' => 'tickets.*',
        ]);
    }

    private function _loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'Ticket');
    }

    private function _register_policies()
    {
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(TicketReply::class, TicketReplyPolicy::class);
    }
}
