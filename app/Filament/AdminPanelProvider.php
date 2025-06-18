<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

// tambahan untuk is admin
use App\Http\Middleware\AdminOnly;

// tambahan
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// tamnbahan
// use App\Filament\Widgets\BarangChart;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->databaseNotifications()
            // ->login(function (Request $request) {
            //     $credentials = $request->only('email', 'password');

            //     if (Auth::guard('admin')->attempt($credentials)) {
            //         $user = Auth::guard('admin')->user();
            //         if ($user->user_group === 'admin') {
            //             return redirect()->intended('/admin');
            //         } else {
            //             Auth::guard('admin')->logout();
            //             return back()->withErrors(['email' => 'Anda tidak memiliki akses ke panel admin.']);
            //         }
            //     }

            //     return back()->withErrors(['email' => 'Email atau password salah.']);
            // })

            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
                \Filament\Pages\Dashboard::class, // ✅ Dashboard default bawaan Filament
            ])
            // ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\DashboardStatCards::class,
                \App\Filament\Widgets\TotalPenjualanChart::class,
                \App\Filament\Widgets\PenjualanPerBulanChart::class,
                \App\Filament\Widgets\PiePenjualanPerProdukChart::class,
                \App\Filament\Widgets\PenjualanPerPelangganChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // tambahan untuk admin only
                // AdminOnly::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
