<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;

use App\Models\Reservation;

use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\SpaceController
as AdminSpaceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlockedSlotController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyReservationController;
use App\Http\Controllers\CalendarPublicController;
use App\Http\Controllers\SpaceController;

/*
|--------------------------------------------------------------------------
| Test Email
|--------------------------------------------------------------------------
*/

Route::get('/test-email', function () {

    Mail::raw(
        'Este es un correo de prueba desde el sistema de coworking 😮‍💨',
        function ($message) {

            $message
                ->to('mariajosecordonvasco@gmail.com')
                ->subject('Prueba Brevo');
        }
    );

    return 'Correo enviado';
});

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return Inertia::render('Welcome', [

        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);

})->name('home');

/*
|--------------------------------------------------------------------------
| Parte pública
|--------------------------------------------------------------------------
*/

Route::get(
    '/spaces',
    [SpaceController::class, 'index']
)->name('spaces.index');

Route::get(
    '/spaces/{space:slug}',
    [SpaceController::class, 'show']
)->name('spaces.show');

Route::get(
    '/reservations/create',
    [ReservationController::class, 'create']
)->name('reservations.create');

Route::post(
    '/reservations',
    [ReservationController::class, 'store']
)->name('reservations.store');

/*
|--------------------------------------------------------------------------
| Área autenticada (todos los usuarios)
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::redirect(
        '/dashboard',
        '/admin/dashboard'
    );

    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reservations/{reservation}/review',
        [ReviewController::class, 'create']
    )->name('reviews.create');

    Route::post(
        '/reservations/{reservation}/review',
        [ReviewController::class, 'store']
    )->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| Panel administrativo
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])
->prefix('admin')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard admin
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/dashboard',
        [HomeController::class, 'index']
    )->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    |/*
    |--------------------------------------------------------------------------
    | Espacios admin
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/spaces',
        [
            AdminSpaceController::class,
            'index'
        ]
    )->name(
        'admin.spaces.index'
    );

    Route::get(
        '/spaces/create',
        [
            AdminSpaceController::class,
            'create'
        ]
    )->name(
        'spaces.create'
    );

    Route::post(
        '/spaces',
        [
            AdminSpaceController::class,
            'store'
        ]
    )->name(
        'spaces.store'
    );

    Route::get(
        '/spaces/{space:slug}/edit',
        [
            AdminSpaceController::class,
            'edit'
        ]
    )->name(
        'spaces.edit'
    );

    Route::put(
        '/spaces/{space:slug}',
        [
            AdminSpaceController::class,
            'update'
        ]
    )->name(
        'spaces.update'
    );

    Route::patch(
        '/spaces/{space:slug}/toggle',
        [
            AdminSpaceController::class,
            'toggle'
        ]
    )->name(
        'spaces.toggle'
        
    );

    Route::delete(
        '/spaces/{space:slug}',
        [
            AdminSpaceController::class,
            'destroy'
        ]
    )->name(
        'spaces.destroy'
    );
    
    /*
    |--------------------------------------------------------------------------
    | Bloqueos de horarios
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/spaces/{space:slug}/blocked-slots/create',
        [BlockedSlotController::class, 'create']
    )->name('admin.blocked-slots.create');

    Route::get(
        '/spaces/{space:slug}/blocked-slots',
        [BlockedSlotController::class, 'index']
    )->name('admin.blocked-slots.index');

    Route::post(
        '/spaces/{space:slug}/blocked-slots',
        [BlockedSlotController::class, 'store']
    )->name('admin.blocked-slots.store');

    Route::delete(
        '/blocked-slots/{blockedSlot}',
        [BlockedSlotController::class, 'destroy']
    )->name('admin.blocked-slots.destroy');

    /*
    |--------------------------------------------------------------------------
    | Calendario admin
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/calendar',
        [CalendarController::class, 'show']
    )->name('admin.calendar.show');

    /*
    |--------------------------------------------------------------------------
    | Usuarios admin
    |--------------------------------------------------------------------------
    */

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('toggle.role');
    });

    /*
    |--------------------------------------------------------------------------
    | Reservas admin
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/reservations',
        [AdminReservationController::class, 'index']
    )->name('reservations.index');

    Route::patch(
        '/reservations/{reservation}/confirm',
        [AdminReservationController::class, 'confirm']
    )->name('reservations.confirm');

    Route::patch(
        '/reservations/{reservation}/reject',
        [AdminReservationController::class, 'reject']
    )->name('reservations.reject');

    Route::patch(
        '/reservations/{reservation}/cancel',
        [AdminReservationController::class, 'cancel']
    )->name('reservations.cancel');

    Route::patch(
        '/reservations/{reservation}/finish',
        [AdminReservationController::class, 'finish']
    )->name('reservations.finish');
});

Route::get(
    '/dashboard',
    function () {

        if (
            auth()->check()
            &&
            auth()->user()->role === 'admin'
        ) {

            return redirect(
                '/admin/dashboard'
            );
        }

        return redirect(
            route('spaces.index')
        );
    }
)->name('dashboard');

Route::middleware(
    ['auth']
)->group(function () {

    Route::get(
        '/my-reservations',
        [
            MyReservationController::class,
            'index'
        ]
    )->name(
        'my.reservations'
    );
});

Route::get(
    '/calendar',
    [
        CalendarPublicController::class,
        'show'
    ]
)->name(
    'calendar.public'
);