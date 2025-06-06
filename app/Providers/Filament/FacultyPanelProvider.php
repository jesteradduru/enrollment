<?php

namespace App\Providers\Filament;

use App\Filament\Resources\EnrollmentResource\Widgets\EnrolleesPerSchoolYearWidget;
use App\Filament\Resources\StudentResource\Widgets\GenderPieWidget;
use App\Filament\Resources\StudentResource\Widgets\StudentTypePie;
use App\Filament\Resources\StudentResource\Widgets\TotalStudents;
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

class FacultyPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandName('AUES-Faculty')
            ->favicon(asset('assets/images/logo.png'))
            // ->databaseNotifications()
            ->id('faculty')
            ->path('faculty')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->login()
            ->discoverResources(in: app_path('Filament/Faculty/Resources'), for: 'App\\Filament\\Faculty\\Resources')
            ->discoverPages(in: app_path('Filament/Faculty/Pages'), for: 'App\\Filament\\Faculty\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Faculty/Widgets'), for: 'App\\Filament\\Faculty\\Widgets')
            ->widgets([
                TotalStudents::class,
                StudentTypePie::class,
                GenderPieWidget::class,
                EnrolleesPerSchoolYearWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Poppins');
            ;
    }
}
