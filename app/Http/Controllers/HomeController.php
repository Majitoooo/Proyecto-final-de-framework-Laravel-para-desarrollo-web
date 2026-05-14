<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\BlockedSlot;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Review;
use App\Models\Space;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController
    extends Controller
{
    public function index(Request $request)
{
        
    $month =
        $request->month
        ??
        now()->month;

    $year =
        $request->year
        ??
        now()->year;

    $reservationQuery =
        Reservation::whereYear(
            'start_time',
            $year
        )
        ->whereMonth(
            'start_time',
            $month
        );
        
    $pendingCount =
        (
            clone
            $reservationQuery
        )
        ->where(
            'status',
            'pending'
        )
        ->count();

    $confirmedCount =
        (
            clone
            $reservationQuery
        )
        ->where(
            'status',
            'confirmed'
        )
        ->count();

    $todayCount =
        (
            clone
            $reservationQuery
        )
        ->whereDate(
            'start_time',
            Carbon::today()
        )
        ->count();

    $cancelledCount =
        (
            clone
            $reservationQuery
        )
        ->where(
            'status',
            'cancelled'
        )->count();

    $busiestDay =
        Reservation::selectRaw(
            "
            EXTRACT(DOW FROM start_time)
            as day,
            COUNT(*) as total
            "
        )
        ->groupBy('day')
        ->orderByDesc('total')
        ->first();

    // Promedio reviews

    $averageRating =
        round(

            Review::avg(
                'rating'
            ) ?? 0,

            1
        );

    // Espacio más reservado

    $topSpace =
        Space::withCount([
            'reservations'
            =>
            function (
                $query
            )
            use (
                $month,
                $year
            ) {

                $query
                    ->whereYear(
                        'start_time',
                        $year
                    )
                    ->whereMonth(
                        'start_time',
                        $month
                    );
            }
        ])
        ->orderByDesc(
            'reservations_count'
        )
        ->first();

    // Reviews recientes

    $recentReviews =
        Review::with(
            'reservation'
        )
        ->latest()
        ->take(5)
        ->get();

    // Próximas reservas

    $upcomingReservations =
        Reservation::with(
            'space'
        )
        ->where(
            'start_time',
            '>=',
            now()
        )
        ->orderBy(
            'start_time'
        )
        ->take(5)
        ->get();

    $statusStats = [

        'pending' =>
            Reservation::where(
                'status',
                'pending'
            )->count(),

        'confirmed' =>
            Reservation::where(
                'status',
                'confirmed'
            )->count(),

        'cancelled' =>
            Reservation::where(
                'status',
                'cancelled'
            )->count(),

        'finished' =>
            Reservation::where(
                'status',
                'finished'
            )->count(),
    ];
    
    $reservationsByMonth =
        Reservation::selectRaw(
            "
            EXTRACT(MONTH FROM start_time) as month,
            TO_CHAR(start_time, 'TMMonth') as month_name,
            COUNT(*) as total
            "
        )
        ->groupByRaw(
            "
            EXTRACT(MONTH FROM start_time),
            TO_CHAR(start_time, 'TMMonth')
            "
        )
        ->orderBy('month')
        ->get();

    $spacesUsage =
        Space::withCount(
            'reservations'
        )
        ->orderByDesc(
            'reservations_count'
        )
        ->take(5)
        ->get();

    $totalMonthReservations =
        Reservation::whereRaw(
            'EXTRACT(MONTH FROM start_time) = ?',
            [now()->month]
        )
        ->count();

    $totalReservations =
        Reservation::count();

    $cancellationRate =
        $totalReservations > 0

        ? round(    
            (
                $cancelledCount
                /
                $totalReservations
            ) * 100,
            1
        )

        : 0;

    $peakHour =
        Reservation::selectRaw(
            '
            EXTRACT(HOUR FROM start_time)
            as hour,
            COUNT(*) as total
            '
        )
        ->groupBy('hour')
        ->orderByDesc('total')
        ->first();

    $topSpaceMonth =
        Space::withCount([
            'reservations' => function (
                $query
            ) {

                $query->whereRaw(
                    'EXTRACT(MONTH FROM start_time) = ?',
                    [now()->month]
                );
            }
        ])
        ->orderByDesc(
            'reservations_count'
        )
        ->first();

    $reservationsByDay =
        Reservation::selectRaw(
            "
            EXTRACT(DOW FROM start_time)
            as day,
            COUNT(*) as total
            "
        )
        ->groupBy('day')
        ->orderBy('day')
        ->get();

    return Inertia::render(
        'Admin/Dashboard',
        [

            'pendingCount'
                =>
                $pendingCount,

            'confirmedCount'
                =>
                $confirmedCount,

            'todayCount'
                =>
                $todayCount,

            'cancelledCount'
                =>
                $cancelledCount,

            'averageRating'
                =>
                $averageRating,

            'topSpace'
                =>
                $topSpace,

            'recentReviews'
                =>
                $recentReviews,

            'upcomingReservations'
                =>
                $upcomingReservations,

            'statusStats'
                =>
                $statusStats,

            'statusStats'
                =>
                $statusStats,

            'reservationsByMonth'
                =>
                $reservationsByMonth,

            'spacesUsage'
                =>
                $spacesUsage,

            'totalMonthReservations'
                =>
                $totalMonthReservations,

            'cancellationRate'
                =>
                $cancellationRate,

            'peakHour'
                =>
                $peakHour,

            'topSpaceMonth'
                =>
                $topSpaceMonth,

            'reservationsByDay'
                =>
                $reservationsByDay,

            'busiestDay'
                =>
                $busiestDay,
            
            'selectedMonth'
                =>
                $month,

            'selectedYear'
                =>
                $year,
        ]
    );
}
}