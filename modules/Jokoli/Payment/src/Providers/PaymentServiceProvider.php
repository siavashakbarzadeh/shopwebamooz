<?php

namespace Jokoli\Payment\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Jokoli\Payment\Gateways\Gateway;
use Jokoli\Payment\Gateways\Zarinpal\ZarinpalAdaptor;
use Jokoli\Payment\Models\Payment;
use Jokoli\Payment\Models\Settlement;
use Jokoli\Payment\Policies\PaymentPolicy;
use Jokoli\Payment\Policies\SettlementPolicy;
use Jokoli\Permission\Enums\Permissions;

class PaymentServiceProvider extends ServiceProvider
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
        $this->app->singleton(Gateway::class, function () {
            return new ZarinpalAdaptor();
        });
    }

    private function _loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'Payment');
    }

    private function _loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations/');
    }

    private function _loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Payment');
    }

    private function _sidebar()
    {
        config()->set('sidebar.items.payments', [
            'name' => 'تراکنش‌ها',
            'icon' => 'i-transactions',
            'route' => 'payments.index',
            'routes' => 'payments.*',
            'permissions' => Permissions::ManagePayments,
        ]);
        config()->set('sidebar.items.my-purchases', [
            'name' => 'خریدهای من',
            'icon' => 'i-my__purchases',
            'route' => 'purchases.index',
            'routes' => 'purchases.index',
        ]);
        config()->set('sidebar.items.settlements', [
            'name' => 'تسویه حساب ها',
            'icon' => 'i-checkouts',
            'route' => 'settlements.index',
            'routes' => 'settlements.index',
            'permissions' => [Permissions::Teach, Permissions::ManageSettlements],
        ]);
        config()->set('sidebar.items.settlements-request', [
            'name' => 'درخواست تسویه',
            'icon' => 'i-checkout__request',
            'route' => 'settlements.create',
            'routes' => 'settlements.create',
            'permissions' => Permissions::Teach,
        ]);
    }

    private function _register_policies()
    {
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Settlement::class, SettlementPolicy::class);
    }
}
