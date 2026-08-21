<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\DepartureController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\TownController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

// Public booking flow — the landing page is the trip browser.
Route::get('/', [TripController::class, 'index'])->name('home');
Route::get('trips/{schedule}/seats', [TripController::class, 'seats'])->name('trips.seats');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    // Booking a seat is tied to a user (for the ticket + payment).
    Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // Admin — every signed-in user is an operator/admin (single-operator model).
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('towns', TownController::class)->except('show');
        Route::resource('routes', RouteController::class)->except('show');
        Route::resource('buses', BusController::class)->except('show');

        // Inline status toggles (no edit screen needed).
        Route::patch('towns/{town}/toggle', [TownController::class, 'toggle'])->name('towns.toggle');
        Route::patch('routes/{route}/toggle', [RouteController::class, 'toggle'])->name('routes.toggle');
        Route::patch('buses/{bus}/toggle', [BusController::class, 'toggle'])->name('buses.toggle');

        // Departures (times) are managed inside the corridor editor.
        Route::post('routes/{route}/schedules', [ScheduleController::class, 'store'])->name('routes.schedules.store');
        Route::patch('schedules/{schedule}/toggle', [ScheduleController::class, 'toggle'])->name('schedules.toggle');
        Route::delete('schedules/{schedule}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');

        // Per-date cancellations for a specific departure.
        Route::get('schedules/{schedule}/departures', [DepartureController::class, 'index'])->name('schedules.departures.index');
        Route::post('schedules/{schedule}/departures/cancel', [DepartureController::class, 'cancel'])->name('schedules.departures.cancel');
        Route::post('schedules/{schedule}/departures/reopen', [DepartureController::class, 'reopen'])->name('schedules.departures.reopen');

        Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    });
});

require __DIR__.'/settings.php';
