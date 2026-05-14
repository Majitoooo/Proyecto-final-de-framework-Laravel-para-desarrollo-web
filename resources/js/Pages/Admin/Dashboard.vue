<script setup>

import AppLayout
from '@/Layouts/AppLayout.vue'

import {
    Link
}
from '@inertiajs/vue3'

import {
    Bar,
    Doughnut
}
from 'vue-chartjs'

import {
    Chart,
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend
}
from 'chart.js'

Chart.register(
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend
)

import {
    router
}
from
'@inertiajs/vue3'

const props =
defineProps({

    pendingCount:
        Number,

    confirmedCount:
        Number,

    todayCount:
        Number,

    cancelledCount:
        Number,

    averageRating:
        Number,

    topSpace:
        {
            type: Object,
            default: () => ({})
        },

    recentReviews:
        {
            type: Array,
            default: () => []
        },

    upcomingReservations:
        {
            type: Array,
            default: () => []
        },
    
    statusStats: Object,

    reservationsByMonth:
    {
        type: Array,
        default: () => []
    },

    spacesUsage:
    {
        type: Array,
        default: () => []
    },

    totalMonthReservations:
        Number,

    cancellationRate:
        Number,

    peakHour:
        Object,

    topSpaceMonth:
    {
        type: Object,
        default: () => ({})
    },

    reservationsByDay:
    {
        type: Array,
        default: () => []
    },

    busiestDay:
    Object,

    selectedMonth:
        Number,

    selectedYear:
        Number,
})

const donutData = {

    labels: [
        'Pendientes',
        'Confirmadas',
        'Canceladas',
        'Finalizadas'
    ],

    datasets: [
    {
        data: [
            props.statusStats?.pending ?? 0,
            props.statusStats?.confirmed ?? 0,
            props.statusStats?.cancelled ?? 0,
            props.statusStats?.finished ?? 0,
        ],
        borderRadius: 10
    }]
}

const monthlyData = {

    labels:
        props.reservationsByMonth
            .map(item =>
                item.month_name.trim()
            ),

    datasets: [
        {
            label:
                'Reservas',

            data:
                props
                .reservationsByMonth
                .map(item =>
                    item.total
                )
        }
    ]
}

const spacesData = {

    labels:
        props.spacesUsage
            .map(space =>
                space.name
            ),

    datasets: [
        {
            label:
                'Reservas',

            data:
                props.spacesUsage
                    .map(space =>
                        space
                        .reservations_count
                    )
        }
    ]
}

const weekDays = [
    'Dom',
    'Lun',
    'Mar',
    'Mié',
    'Jue',
    'Vie',
    'Sáb'
]

const reservationsByDayData = {

    labels:
        props.reservationsByDay
            .map(item =>
                weekDays[item.day]
            ),

    datasets: [
        {
            label:
                'Reservas',

            data:
                props.reservationsByDay
                    .map(item =>
                        item.total
                    )
        }
    ]
}

</script>

<template>

<AppLayout
    title="Dashboard"
>

    <template #header>

        <h2
            class="
                font-semibold
                text-2xl
                text-gray-800
            "
        >
            Dashboard
        </h2>

    </template>

    <div
        class="
            py-8
            px-6
            max-w-7xl
            mx-auto
        "
    >

        <!-- Bienvenida -->

        <div
            class="
                mb-8
            "
        >

            <h1
                class="
                    text-3xl
                    font-bold
                    text-gray-800
                "
            >
                Hola 👋
            </h1>

            <p
                class="
                    text-gray-500
                    mt-1
                "
            >
                Resumen general
                del sistema
                de reservas.
            </p>

        </div>

        <div
    class="
        flex
        gap-4
        mb-8
    "
>

    <select
        :value="
            selectedMonth
        "
        @change="
            router.get(
                '/admin/dashboard',
                {
                    month:
                    $event.target.value,
                    year:
                    selectedYear
                }
            )
        "
        class="
            rounded-xl
            border-gray-300
        "
    >

        <option
            v-for="
                month
                in
                12
            "
            :key="
                month
            "
            :value="
                month
            "
        >
            Mes
            {{
                month
            }}
        </option>

    </select>

    <select
        :value="
            selectedYear
        "
        @change="
            router.get(
                '/admin/dashboard',
                {
                    month:
                    selectedMonth,
                    year:
                    $event.target.value
                }
            )
        "
        class="
            rounded-xl
            border-gray-300
        "
    >

        <option>
            2025
        </option>

        <option>
            2026
        </option>

    </select>

</div>

        <!-- Cards principales -->

        <div
            class="
                grid
                grid-cols-1
                md:grid-cols-2
                xl:grid-cols-4
                gap-6
                mb-10
            "
        >

            <!-- Pendientes -->

            <div
                class="
                    bg-gradient-to-r
                    from-fuchsia-500
                    to-pink-500
                    rounded-3xl
                    shadow-lg
                    p-6
                    text-white
                "
            >

                <p
                    class="
                        text-sm
                        opacity-90
                    "
                >
                    Reservas pendientes
                </p>

                <h2
                    class="
                        text-5xl
                        font-bold
                        mt-4
                    "
                >
                    {{
                        pendingCount
                    }}
                </h2>

            </div>

            <!-- Confirmadas -->

            <div
                class="
                    bg-gradient-to-r
                    from-rose-400
                    to-rose-500
                    rounded-3xl
                    shadow-lg
                    p-6
                    text-white
                "
            >

                <p
                    class="
                        text-sm
                        opacity-90
                    "
                >
                    Confirmadas
                </p>

                <h2
                    class="
                        text-5xl
                        font-bold
                        mt-4
                    "
                >
                    {{
                        confirmedCount
                    }}
                </h2>

            </div>

            <!-- Hoy -->

            <div
                class="
                    bg-gradient-to-r
                    from-sky-400
                    to-sky-500
                    rounded-3xl
                    shadow-lg
                    p-6
                    text-white
                "
            >

                <p
                    class="
                        text-sm
                        opacity-90
                    "
                >
                    Reservas hoy
                </p>

                <h2
                    class="
                        text-5xl
                        font-bold
                        mt-4
                    "
                >
                    {{
                        todayCount
                    }}
                </h2>

            </div>

            <!-- Acceso rápido -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                    border
                "
            >

                <p
                    class="
                        font-semibold
                        text-gray-700
                        mb-4
                    "
                >
                    Acceso rápido
                </p>

                <div
                    class="
                        flex
                        flex-col
                        gap-3
                    "
                >

                    <Link
                        href="/admin/reservations"
                        class="
                            text-pink-500
                            font-medium
                        "
                    >
                        Ver reservas
                    </Link>

                    <Link
                        href="/admin/calendar"
                        class="
                            text-pink-500
                            font-medium
                        "
                    >
                        Ver calendario
                    </Link>

                    <Link
                        href="/admin/spaces"
                        class="
                            text-pink-500
                            font-medium
                        "
                    >
                        Administrar espacios
                    </Link>

                </div>

            </div>

        </div>

        <!-- Segunda fila -->

        <div
            class="
                grid
                grid-cols-1
                md:grid-cols-3
                gap-6
                mb-10
            "
        >

            <!-- Rating -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                    border
                "
            >

                <p
                    class="
                        text-gray-500
                        text-sm
                    "
                >
                    Calificación promedio
                </p>

                <h2
                    class="
                        text-4xl
                        font-bold
                        mt-4
                    "
                >
                    ⭐
                    {{
                        averageRating
                        ??
                        0
                    }}
                </h2>

            </div>

            <!-- Top espacio -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                    border
                "
            >

                <p
                    class="
                        text-gray-500
                        text-sm
                    "
                >
                    Espacio más reservado
                </p>

                <h2
                    class="
                        text-2xl
                        font-bold
                        mt-4
                    "
                >
                    {{
                        topSpace?.name
                        ??
                        'Aún sin reservas'
                    }}
                </h2>

            </div>

            <!-- Cancelaciones -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                    border
                "
            >

                <p
                    class="
                        text-gray-500
                        text-sm
                    "
                >
                    Cancelaciones
                </p>

                <h2
                    class="
                        text-4xl
                        font-bold
                        mt-4
                    "
                >
                    ❌
                    {{
                        cancelledCount
                        ??
                        0
                    }}
                </h2>

            </div>

        </div>

        <div
    class="
        grid
        grid-cols-1
        md:grid-cols-2
        xl:grid-cols-4
        gap-6
        mb-10
    "
>

    <div
        class="
            bg-white
            rounded-3xl
            shadow-lg
            p-6
        "
    >

        <p
            class="
                text-gray-500
                text-sm
            "
        >
            Reservas del mes
        </p>

        <h2
            class="
                text-4xl
                font-bold
                mt-3
            "
        >
            📅
            {{
                totalMonthReservations
            }}
        </h2>

    </div>

    <div
        class="
            bg-white
            rounded-3xl
            shadow-lg
            p-6
        "
    >

        <p
            class="
                text-gray-500
                text-sm
            "
        >
            Tasa cancelación
        </p>

        <h2
            class="
                text-4xl
                font-bold
                mt-3
            "
        >
            ❌
            {{
                cancellationRate
            }}%
        </h2>

    </div>

    

    <div
        class="
            bg-white
            rounded-3xl
            shadow-lg
            p-6
        "
    >

        <p
            class="
                text-gray-500
                text-sm
            "
        >
            Hora pico
        </p>

        <h2
            class="
                text-3xl
                font-bold
                mt-3
            "
        >
            🕒
            {{
                peakHour?.hour
            }}:00
        </h2>

    </div>

    <div
        class="
            bg-white
            rounded-3xl
            shadow-lg
            p-6
        "
    >

        <p
            class="
                text-gray-500
                text-sm
            "
        >
            Espacio top del mes
        </p>

        <h2
            class="
                text-xl
                font-bold
                mt-3
            "
        >
            🏆
            {{
                topSpaceMonth?.name
                ??
                'Sin datos'
            }}
        </h2>

    </div>

    <div
    class="
        bg-white
        rounded-3xl
        shadow-lg
        p-6
    "
>

    <p
        class="
            text-gray-500
            text-sm
        "
    >
        Día más ocupado
    </p>

    <h2
        class="
            text-3xl
            font-bold
            mt-3
        "
    >
        📈
        {{
            weekDays[
                busiestDay?.day
            ]
        }}
    </h2>

</div>

</div>

        <div
            class="
                grid
                grid-cols-1
                xl:grid-cols-3
                gap-6
                mb-10
            "
        >

            <!-- Donut -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                "
            >

                <h2
                    class="
                        text-xl
                        font-bold
                        mb-4
                    "
                >
                    Reservas por estado
                </h2>

                <Doughnut
                    :data="donutData"
                    :options="{
                        cutout: '70%'
                    }"
                />

            </div>

            <!-- Mes -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                    xl:col-span-2
                "
            >

                <h2
                    class="
                        text-xl
                        font-bold
                        mb-4
                    "
                >
                    Reservas por mes
                </h2>

                <Bar
                    :data="spacesData"
                    :options="{
                        indexAxis: 'y'
                    }"
                />

            </div>

        </div>

        <div
            class="
                bg-white
                rounded-3xl
                shadow-lg
                p-6
                mb-10
            "
        >

            <h2
                class="
                    text-xl
                    font-bold
                    mb-4
                "
            >
                Espacios más usados
            </h2>

            <Bar
                :data="
                    spacesData
                "
            />

        </div>

        <div
            class="
                bg-white
                rounded-3xl
                shadow-lg
                p-6
                mb-10
            "
        >

            <h2
                class="
                    text-xl
                    font-bold
                    mb-4
                "
            >
                Actividad semanal
            </h2>

            <Bar
                :data="
                    reservationsByDayData
                "
            />

        </div>

        <!-- Aviso -->

        <div
            v-if="
                pendingCount > 0
            "
            class="
                bg-amber-50
                border
                border-amber-200
                rounded-2xl
                p-5
                mb-10
            "
        >

            <p
                class="
                    text-amber-700
                    font-medium
                "
            >
                ⚠️ Hay
                {{
                    pendingCount
                }}
                reservas pendientes
                por revisar.
            </p>

        </div>

        <!-- Panel inferior -->

        <div
            class="
                grid
                grid-cols-1
                xl:grid-cols-2
                gap-6
            "
        >

            <!-- Próximas reservas -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                "
            >

                <h2
                    class="
                        text-xl
                        font-bold
                        mb-5
                    "
                >
                    Próximas reservas
                </h2>

                <div
                    v-if="
                        upcomingReservations?.length
                    "
                    class="
                        space-y-4
                    "
                >

                    <div
                        v-for="
                            reservation
                            in
                            upcomingReservations
                        "
                        :key="
                            reservation.id
                        "
                        class="
                            border-b
                            pb-4
                        "
                    >

                        <h3
                            class="
                                font-semibold
                            "
                        >
                            {{
                                reservation
                                    .space
                                    ?.name
                            }}
                        </h3>

                        <p
                            class="
                                text-sm
                                text-gray-500
                            "
                        >
                            {{
                                reservation
                                    .user_name
                            }}
                        </p>

                        <p
                            class="
                                text-sm
                                text-pink-500
                            "
                        >
                            {{
                                new Date(
                                    reservation
                                        .start_time
                                )
                                .toLocaleString(
                                    'es-CO'
                                )
                            }}
                        </p>

                    </div>

                </div>

                <p
                    v-else
                    class="
                        text-gray-500
                    "
                >
                    No hay reservas próximas.
                </p>

            </div>

            <!-- Opiniones -->

            <div
                class="
                    bg-white
                    rounded-3xl
                    shadow-lg
                    p-6
                "
            >

                <h2
                    class="
                        text-xl
                        font-bold
                        mb-5
                    "
                >
                    Opiniones recientes
                </h2>

                <div
                    v-if="
                        recentReviews?.length
                    "
                    class="
                        space-y-4
                    "
                >

                    <div
                        v-for="
                            review
                            in
                            recentReviews
                        "
                        :key="
                            review.id
                        "
                        class="
                            border-b
                            pb-4
                        "
                    >

                        <p
                            class="
                                text-yellow-500
                                text-lg
                            "
                        >
                            {{
                                '⭐'.repeat(
                                    review.rating
                                )
                            }}
                        </p>

                        <p
                            class="
                                text-gray-700
                            "
                        >
                            {{
                                review.comment
                                ||
                                'Sin comentario'
                            }}
                        </p>

                    </div>

                </div>

                <p
                    v-else
                    class="
                        text-gray-500
                    "
                >
                    Aún no hay opiniones.
                </p>

            </div>

        </div>

    </div>

</AppLayout>

</template>