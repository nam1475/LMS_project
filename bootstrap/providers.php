<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\PaymentGatewaySettingServiceProvider::class => !\Illuminate\Support\Facades\App::environment('ci'),
    App\Providers\SettingServiceProvider::class => !\Illuminate\Support\Facades\App::environment('ci'),
    Barryvdh\Debugbar\ServiceProvider::class => !\Illuminate\Support\Facades\App::environment('ci'),
];
