<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { Bar, Doughnut } from 'vue-chartjs'
import {
    Chart,
    ArcElement,
    BarElement,
    CategoryScale,
    LinearScale,
    Tooltip,
    Legend
} from 'chart.js'

Chart.register(ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend)

const props = defineProps({
    pendingCount:           Number,
    confirmedCount:         Number,
    todayCount:             Number,
    cancelledCount:         Number,
    averageRating:          Number,
    topSpace:               { type: Object,  default: () => ({}) },
    recentReviews:          { type: Array,   default: () => [] },
    upcomingReservations:   { type: Array,   default: () => [] },
    statusStats:            Object,
    reservationsByMonth:    { type: Array,   default: () => [] },
    spacesUsage:            { type: Array,   default: () => [] },
    totalMonthReservations: Number,
    cancellationRate:       Number,
    peakHour:               Object,
    topSpaceMonth:          { type: Object,  default: () => ({}) },
    reservationsByDay:      { type: Array,   default: () => [] },
    busiestDay:             Object,
    selectedMonth:          Number,
    selectedYear:           Number,
})

const weekDays = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']

const totalStatus =
    (props.statusStats?.pending  ?? 0) +
    (props.statusStats?.confirmed ?? 0) +
    (props.statusStats?.cancelled ?? 0) +
    (props.statusStats?.finished  ?? 0)

// ── Chart configs ───────────────────────────────────────────────────────────

const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: 'rgba(15,15,15,0.85)',
            titleColor: '#fff',
            bodyColor: 'rgba(255,255,255,0.7)',
            cornerRadius: 8,
            padding: 10,
        },
    },
    scales: {
        x: {
            grid:   { color: 'rgba(128,128,128,0.1)' },
            ticks:  { color: 'rgba(128,128,128,0.7)', font: { size: 11 } },
            border: { display: false },
        },
        y: {
            grid:   { color: 'rgba(128,128,128,0.1)' },
            ticks:  { color: 'rgba(128,128,128,0.7)', font: { size: 11 } },
            border: { display: false },
        },
    },
}

const monthlyChartData = {
    labels: props.reservationsByMonth.map(i => i.month_name.trim()),
    datasets: [{
        label: 'Reservas',
        data: props.reservationsByMonth.map(i => i.total),
        backgroundColor: 'rgba(29,158,117,0.25)',
        borderColor:     '#1D9E75',
        borderWidth: 2,
        borderRadius: 6,
        borderSkipped: false,
    }],
}

const donutChartData = {
    labels: ['Pendientes', 'Confirmadas', 'Canceladas', 'Finalizadas'],
    datasets: [{
        data: [
            props.statusStats?.pending   ?? 0,
            props.statusStats?.confirmed ?? 0,
            props.statusStats?.cancelled ?? 0,
            props.statusStats?.finished  ?? 0,
        ],
        backgroundColor: ['#BA7517', '#1D9E75', '#D85A30', '#378ADD'],
        borderWidth: 0,
        borderRadius: 4,
    }],
}

const donutChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '68%',
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: 'rgba(15,15,15,0.85)',
            titleColor: '#fff',
            bodyColor: 'rgba(255,255,255,0.7)',
            cornerRadius: 8,
            padding: 10,
        },
    },
}

const weeklyChartData = {
    labels: props.reservationsByDay.map(i => weekDays[i.day]),
    datasets: [{
        label: 'Reservas',
        data: props.reservationsByDay.map(i => i.total),
        backgroundColor: props.reservationsByDay.map(i => {
            const max = Math.max(...props.reservationsByDay.map(d => d.total))
            return i.total === max ? '#534AB7' : 'rgba(127,119,221,0.25)'
        }),
        borderRadius: 5,
        borderSkipped: false,
    }],
}

const spacesChartData = {
    labels: props.spacesUsage.map(s => s.name),
    datasets: [{
        label: 'Reservas',
        data: props.spacesUsage.map(s => s.reservations_count),
        backgroundColor: ['#1D9E75', '#7F77DD', '#378ADD', '#BA7517', '#D85A30'],
        borderRadius: 5,
        borderSkipped: false,
    }],
}

const spacesChartOptions = {
    ...chartDefaults,
    indexAxis: 'y',
    scales: {
        x: { ...chartDefaults.scales.x },
        y: {
            grid:   { display: false },
            ticks:  { color: 'rgba(128,128,128,0.7)', font: { size: 11 } },
            border: { display: false },
        },
    },
}

// ── Helpers ─────────────────────────────────────────────────────────────────

const months = [
    'Enero','Febrero','Marzo','Abril','Mayo','Junio',
    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre',
]

function statusLabel(status) {
    return { pending: 'Pendiente', confirmed: 'Confirmada', cancelled: 'Cancelada', finished: 'Finalizada' }[status] ?? status
}

function statusClass(status) {
    return {
        pending:   's-pending',
        confirmed: 's-confirmed',
        cancelled: 's-cancelled',
        finished:  's-finished',
    }[status] ?? ''
}

function initials(name) {
    return (name ?? '')
        .split(' ')
        .slice(0, 2)
        .map(w => w[0])
        .join('')
        .toUpperCase()
}

function avatarStyle(name) {
    const palettes = [
        { bg: '#E1F5EE', color: '#0F6E56' },
        { bg: '#EEEDFE', color: '#3C3489' },
        { bg: '#E6F1FB', color: '#0C447C' },
        { bg: '#FAEEDA', color: '#854F0B' },
        { bg: '#FAECE7', color: '#993C1D' },
    ]
    const idx = (name?.charCodeAt(0) ?? 0) % palettes.length
    return `background:${palettes[idx].bg};color:${palettes[idx].color}`
}

function starString(rating) {
    const full  = Math.round(rating ?? 0)
    const empty = 5 - full
    return '★'.repeat(full) + '☆'.repeat(empty)
}

function maxSpaceCount() {
    return Math.max(...props.spacesUsage.map(s => s.reservations_count), 1)
}
</script>

<template>
<AppLayout title="Dashboard">

    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            Dashboard
        </h2>
    </template>

    <div class="py-8 px-6 max-w-7xl mx-auto space-y-8">

        <!-- Bienvenida + filtros -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">
                    Hola 👋
                </h1>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-0.5">
                    Resumen general del sistema de reservas
                </p>
            </div>

            <div class="flex gap-3">
                <div class="filter-pill">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <select
                        :value="selectedMonth"
                        @change="router.get('/admin/dashboard', { month: $event.target.value, year: selectedYear })"
                        class="bg-transparent border-none text-sm text-gray-700 dark:text-slate-200 focus:outline-none cursor-pointer"
                    >
                        <option v-for="(name, idx) in months" :key="idx+1" :value="idx+1">{{ name }}</option>
                    </select>
                </div>

                <div class="filter-pill">
                    <select
                        :value="selectedYear"
                        @change="router.get('/admin/dashboard', { month: selectedMonth, year: $event.target.value })"
                        class="bg-transparent border-none text-sm text-gray-700 dark:text-slate-200 focus:outline-none cursor-pointer"
                    >
                        <option>2024</option>
                        <option>2025</option>
                        <option>2026</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4">

            <div class="kpi-card border-l-amber">
                <p class="kpi-label">Pendientes</p>
                <p class="kpi-value">{{ pendingCount ?? 0 }}</p>
                <p class="kpi-sub text-amber-600 dark:text-amber-400">
                    <span class="kpi-dot bg-amber-400"></span> En revisión
                </p>
            </div>

            <div class="kpi-card border-l-teal">
                <p class="kpi-label">Confirmadas</p>
                <p class="kpi-value">{{ confirmedCount ?? 0 }}</p>
                <p class="kpi-sub text-emerald-600 dark:text-emerald-400">
                    <span class="kpi-dot bg-emerald-400"></span> Activas
                </p>
            </div>

            <div class="kpi-card border-l-blue">
                <p class="kpi-label">Reservas hoy</p>
                <p class="kpi-value">{{ todayCount ?? 0 }}</p>
                <p class="kpi-sub text-sky-600 dark:text-sky-400">
                    <span class="kpi-dot bg-sky-400"></span> En curso
                </p>
            </div>

            <div class="kpi-card border-l-coral">
                <p class="kpi-label">Canceladas</p>
                <p class="kpi-value">{{ cancelledCount ?? 0 }}</p>
                <p class="kpi-sub text-rose-600 dark:text-rose-400">
                    <span class="kpi-dot bg-rose-400"></span>
                    {{ cancellationRate ? cancellationRate.toFixed(1) + '%' : '—' }}
                </p>
            </div>

            <div class="kpi-card border-l-purple">
                <p class="kpi-label">Valoración</p>
                <p class="kpi-value">{{ averageRating ? averageRating.toFixed(1) : '—' }}</p>
                <p class="kpi-sub text-violet-600 dark:text-violet-400">
                    <span class="text-amber-400">★</span> promedio
                </p>
            </div>

        </div>

        <!-- Gráficas principales -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <!-- Reservas por mes -->
            <div class="chart-card lg:col-span-2">
                <p class="chart-title">Reservas por mes</p>
                <div class="h-52">
                    <Bar :data="monthlyChartData" :options="chartDefaults" />
                </div>
            </div>

            <!-- Dona de estados -->
            <div class="chart-card">
                <p class="chart-title">Por estado</p>
                <div class="flex flex-wrap gap-x-3 gap-y-1 mb-3">
                    <span class="legend-item"><span class="legend-dot bg-amber-500"></span>Pendientes {{ statusStats?.pending ?? 0 }}</span>
                    <span class="legend-item"><span class="legend-dot bg-emerald-500"></span>Confirmadas {{ statusStats?.confirmed ?? 0 }}</span>
                    <span class="legend-item"><span class="legend-dot bg-rose-500"></span>Canceladas {{ statusStats?.cancelled ?? 0 }}</span>
                    <span class="legend-item"><span class="legend-dot bg-sky-500"></span>Finalizadas {{ statusStats?.finished ?? 0 }}</span>
                </div>
                <div class="h-44 flex items-center justify-center">
                    <Doughnut :data="donutChartData" :options="donutChartOptions" />
                </div>
            </div>

        </div>

        <!-- Segunda fila de gráficas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- Uso de espacios -->
            <div class="chart-card">
                <div class="flex items-center justify-between mb-3">
                    <p class="chart-title !mb-0">Uso de espacios</p>
                    <span class="text-xs text-gray-400 dark:text-slate-500">este mes</span>
                </div>
                <div class="space-y-3">
                    <div
                        v-for="(space, i) in spacesUsage"
                        :key="space.id ?? i"
                        class="flex items-center gap-3"
                    >
                        <span class="text-sm text-gray-600 dark:text-slate-300 w-32 truncate">{{ space.name }}</span>
                        <div class="flex-1 bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                            <div
                                class="h-1.5 rounded-full transition-all duration-500"
                                :style="{
                                    width: ((space.reservations_count / maxSpaceCount()) * 100).toFixed(0) + '%',
                                    background: ['#1D9E75','#7F77DD','#378ADD','#BA7517','#D85A30'][i % 5]
                                }"
                            ></div>
                        </div>
                        <span class="text-xs font-medium text-gray-500 dark:text-slate-400 w-6 text-right">
                            {{ space.reservations_count }}
                        </span>
                    </div>
                    <p v-if="!spacesUsage.length" class="text-sm text-gray-400 dark:text-slate-500 text-center py-4">
                        Sin datos este mes
                    </p>
                </div>
            </div>

            <!-- Actividad semanal -->
            <div class="chart-card">
                <p class="chart-title">Actividad semanal</p>
                <div class="h-44">
                    <Bar :data="weeklyChartData" :options="chartDefaults" />
                </div>
                <p v-if="busiestDay" class="text-xs text-gray-400 dark:text-slate-500 mt-2 text-right">
                    Día pico: <span class="font-medium text-violet-600 dark:text-violet-400">{{ weekDays[busiestDay.day] }}</span>
                    con {{ busiestDay.total }} reservas
                </p>
            </div>

        </div>

        <!-- Estadísticas destacadas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

            <div class="stat-card">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-slate-500 mb-1">
                    Espacio del mes
                </p>
                <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">
                    {{ topSpaceMonth?.name ?? topSpace?.name ?? 'Sin datos' }}
                </p>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">
                    {{ topSpaceMonth?.reservations_count ?? topSpace?.reservations_count ?? 0 }} reservas
                </p>
            </div>

            <div class="stat-card">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-slate-500 mb-1">
                    Total del mes
                </p>
                <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">
                    {{ totalMonthReservations ?? 0 }}
                </p>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">reservas registradas</p>
            </div>

            <div class="stat-card">
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-slate-500 mb-1">
                    Hora pico
                </p>
                <p class="text-lg font-semibold text-gray-800 dark:text-slate-100">
                    {{ peakHour ? peakHour.hour + ':00 h' : '—' }}
                </p>
                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">
                    {{ peakHour?.total ?? 0 }} reservas en esa franja
                </p>
            </div>

        </div>

        <!-- Tabla inferior: próximas reservas + reseñas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- Próximas reservas -->
            <div class="chart-card">
                <div class="flex items-center justify-between mb-4">
                    <p class="chart-title !mb-0">Próximas reservas</p>
                    <Link href="/admin/reservations" class="text-xs text-violet-600 dark:text-violet-400 hover:underline">
                        Ver todas →
                    </Link>
                </div>

                <div class="space-y-2">
                    <div
                        v-for="res in upcomingReservations"
                        :key="res.id"
                        class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-slate-800/60"
                    >
                        <div
                            class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-semibold flex-shrink-0"
                            :style="avatarStyle(res.user?.name)"
                        >
                            {{ initials(res.user?.name) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 dark:text-slate-100 truncate">
                                {{ res.user?.name ?? 'Usuario' }}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 truncate">
                                {{ res.space?.name ?? '—' }} · {{ res.start_time }} – {{ res.end_time }}
                            </p>
                        </div>
                        <span class="status-badge" :class="statusClass(res.status)">
                            {{ statusLabel(res.status) }}
                        </span>
                    </div>
                    <p v-if="!upcomingReservations.length" class="text-sm text-gray-400 dark:text-slate-500 text-center py-6">
                        No hay reservas próximas
                    </p>
                </div>
            </div>

            <!-- Reseñas recientes -->
            <div class="chart-card">
                <p class="chart-title">Reseñas recientes</p>
                <div class="space-y-3">
                    <div
                        v-for="review in recentReviews"
                        :key="review.id"
                        class="p-3 rounded-xl border border-gray-100 dark:border-slate-700"
                    >
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-amber-400 text-sm tracking-wide">
                                {{ starString(review.rating) }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-slate-500">{{ review.created_at_human }}</span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed">
                            {{ review.comment }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">
                            {{ review.user?.name ?? 'Anónimo' }} · {{ review.space?.name ?? '—' }}
                        </p>
                    </div>
                    <p v-if="!recentReviews.length" class="text-sm text-gray-400 dark:text-slate-500 text-center py-6">
                        Sin reseñas aún
                    </p>
                </div>
            </div>

        </div>

    </div>
</AppLayout>
</template>

<style scoped>
/* KPI cards */
.kpi-card {
    @apply bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 border-l-4;
}
.border-l-amber  { border-left-color: #F59E0B; }
.border-l-teal   { border-left-color: #10B981; }
.border-l-blue   { border-left-color: #38BDF8; }
.border-l-coral  { border-left-color: #F43F5E; }
.border-l-purple { border-left-color: #8B5CF6; }

.kpi-label { @apply text-xs font-medium uppercase tracking-wide text-gray-400 dark:text-slate-500; }
.kpi-value { @apply text-4xl font-bold text-gray-800 dark:text-slate-100 mt-1 leading-none; }
.kpi-sub   { @apply text-xs flex items-center gap-1.5 mt-2; }
.kpi-dot   { @apply w-1.5 h-1.5 rounded-full inline-block; }

/* Chart cards */
.chart-card {
    @apply bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700;
}
.chart-title {
    @apply text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-slate-500 mb-4;
}

/* Stat cards */
.stat-card {
    @apply bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700;
}

/* Legend */
.legend-item {
    @apply flex items-center gap-1.5 text-xs text-gray-500 dark:text-slate-400;
}
.legend-dot {
    @apply w-2 h-2 rounded-sm inline-block;
}

/* Filter pills */
.filter-pill {
    @apply flex items-center gap-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2;
}

/* Status badges */
.status-badge {
    @apply flex-shrink-0 text-xs font-medium px-2.5 py-1 rounded-full;
}
.s-pending   { @apply bg-amber-50  dark:bg-amber-900/30  text-amber-700  dark:text-amber-300; }
.s-confirmed { @apply bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300; }
.s-cancelled { @apply bg-rose-50   dark:bg-rose-900/30   text-rose-700   dark:text-rose-300; }
.s-finished  { @apply bg-sky-50    dark:bg-sky-900/30    text-sky-700    dark:text-sky-300; }
</style>