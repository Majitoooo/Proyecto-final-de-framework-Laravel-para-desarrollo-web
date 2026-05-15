<script setup>
import { router } from '@inertiajs/vue3'
import { computed, ref, reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    spaces:          Array,
    selectedSpaceId: [String, Number],
    selectedSpace:   Object,
    availabilities:  Array,
    weekDays:        Array,
    currentWeek:     String,
    reservations:    Array,
    blockedSlots:    Array,
})

// ── Time slots ───────────────────────────────────────────────────────────────

const timeSlots = computed(() => {
    if (!props.availabilities?.length) return []
    const earliest = props.availabilities.reduce((min, a) => a.start_time < min ? a.start_time : min, '23:59:59')
    const latest   = props.availabilities.reduce((max, a) => a.end_time   > max ? a.end_time   : max, '00:00:00')
    const slots = []
    let h = parseInt(earliest.split(':')[0])
    const end = parseInt(latest.split(':')[0])
    while (h < end) { slots.push(`${String(h).padStart(2, '0')}:00`); h++ }
    return slots
})

// ── Slot logic ───────────────────────────────────────────────────────────────

const getSlotInfo = (
    day,
    hour
) => {

    const t =
        new Date(
            `${day}T${hour}:00`
        )

    const dayOfWeek =
        t.getDay()

    const availability =
        props.availabilities?.find(
            a =>
                Number(a.day_of_week)
                ===
                Number(dayOfWeek)
        )

    if (
        !availability
    ) {
        return {
            type:
                'unavailable'
        }
    }

        const slotHour =
        parseInt(
            hour.split(':')[0]
        )

    const startHour =
        parseInt(
            availability.start_time
                .split(':')[0]
        )

    const endHour =
        parseInt(
            availability.end_time
                .split(':')[0]
        )

    if (
        slotHour <
            startHour
        ||
        slotHour >=
            endHour
    ) {

        return {
            type:
                'unavailable'
        }
    }

    // bloqueos
    const blocked =
        props.blockedSlots?.find(
            s =>
                t >= new Date(s.start_time)
                &&
                t < new Date(s.end_time)
        )

    if (
        blocked
    ) {
        return {
            type:
                'blocked',
            data:
                blocked
        }
    }

    // reservas
    const reservation =
        props.reservations?.find(
            s =>
                t >= new Date(s.start_time)
                &&
                t < new Date(s.end_time)
        )

    if (
        reservation
    ) {

        return reservation.status
            ===
            'rejected'

            ? {
                type:
                    'available'
            }

            : {
                type:
                    'reservation',
                data:
                    reservation
            }
    }

    return {
        type:
            'available'
    }
}

// ── Selection & panel ────────────────────────────────────────────────────────

const selectedSlot = ref(null)
const selectSlot   = (day, hour) => {
    const info = getSlotInfo(day, hour)
    if (selectedSlot.value?.day === day && selectedSlot.value?.hour === hour) {
        selectedSlot.value = null; return
    }
    selectedSlot.value = { day, hour, ...info }
}
const closePanel = () => { selectedSlot.value = null }

// ── Tooltip ──────────────────────────────────────────────────────────────────

const hoveredSlot    = ref(null)
const tooltipVisible = ref(false)
const tooltip        = reactive({ x: 0, y: 0 })

const onCellEnter = (day, hour) => {
    hoveredSlot.value = { day, hour, info: getSlotInfo(day, hour) }
}
const moveTooltip = (e) => {
    tooltipVisible.value = true
    const TW = 240, TH = 90
    tooltip.x = e.clientX + TW > window.innerWidth  - 12 ? e.clientX - TW - 10 : e.clientX + 12
    tooltip.y = e.clientY + TH > window.innerHeight - 12 ? e.clientY - TH - 10 : e.clientY + 12
}
const hideTooltip = () => { tooltipVisible.value = false; hoveredSlot.value = null }

// ── Date helpers ─────────────────────────────────────────────────────────────

const isPastWeek = computed(() => {
    const t = new Date(); t.setHours(0, 0, 0, 0)
    const ws = new Date(props.currentWeek)
    const we = new Date(ws); we.setDate(we.getDate() + 6); we.setHours(23, 59, 59, 999)
    return we < t
})

const isPastDay = (day) => {
    const t =
        new Date()

    t.setHours(
        0,0,0,0
    )

    return new Date(
        day + 'T12:00:00'
    ) < t
}

const isToday = (day) =>
    new Date(
        day + 'T12:00:00'
    ).toDateString()
    ===
    new Date()
        .toDateString()

const weekRange = computed(() => {
    if (!props.weekDays?.length) return props.currentWeek
    const f = new Date(props.weekDays[0])
    const l = new Date(props.weekDays[props.weekDays.length - 1])
    return `${f.toLocaleDateString('es-CO', { day: 'numeric', month: 'short' })} – ${l.toLocaleDateString('es-CO', { day: 'numeric', month: 'short', year: 'numeric' })}`
})

const changeSpace  = (e) => router.get('/admin/calendar', { space_id: e.target.value, week: props.currentWeek })
const previousWeek = () => {
    const d = new Date(props.currentWeek); d.setDate(d.getDate() - 7)
    router.get('/admin/calendar', { space_id: props.selectedSpaceId, week: d.toISOString().split('T')[0] })
}
const nextWeek = () => {
    const d = new Date(props.currentWeek); d.setDate(d.getDate() + 7)
    router.get('/admin/calendar', { space_id: props.selectedSpaceId, week: d.toISOString().split('T')[0] })
}

const formatTime = (iso)  => new Date(iso).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
const formatDay  = (date) => new Date(date).toLocaleDateString('es-CO', { weekday: 'long', day: 'numeric', month: 'long' })

// ── Visual config ────────────────────────────────────────────────────────────

const STATUS_LABELS = { pending: 'Pendiente', confirmed: 'Confirmada', finished: 'Finalizada' }

const slotKey = (info) => info.type === 'reservation' ? info.data?.status : info.type

// Active (present/future)
const PILL = {
    available:   { bg: 'slot-available',   dot: 'dot-available',   text: 'text-available',   label: 'Libre' },
    blocked:     { bg: 'slot-blocked',     dot: 'dot-blocked',     text: 'text-blocked',     label: 'Bloqueado' },
    pending:     { bg: 'slot-pending',     dot: 'dot-pending',     text: 'text-pending',     label: 'Pendiente' },
    confirmed:   { bg: 'slot-confirmed',   dot: 'dot-confirmed',   text: 'text-confirmed',   label: 'Confirmada' },
    finished:    { bg: 'slot-finished',    dot: 'dot-finished',    text: 'text-finished',    label: 'Finalizada' },
    unavailable: { bg: 'slot-unavailable', dot: 'dot-unavailable', text: 'text-unavailable', label: '' },
}

// Past — same semantics, softer opacity handled via CSS class
const PILL_PAST = {
    available:   { bg: 'slot-available-past',   dot: 'dot-available-past',   text: 'text-available-past',   label: 'Pasado' },
    blocked:     { bg: 'slot-blocked-past',     dot: 'dot-blocked-past',     text: 'text-blocked-past',     label: 'Bloqueado' },
    pending:     { bg: 'slot-pending-past',     dot: 'dot-pending-past',     text: 'text-pending-past',     label: 'Pendiente' },
    confirmed:   { bg: 'slot-confirmed-past',   dot: 'dot-confirmed-past',   text: 'text-confirmed-past',   label: 'Confirmada' },
    finished:    { bg: 'slot-finished-past',    dot: 'dot-finished-past',    text: 'text-finished-past',    label: 'Finalizada' },
    unavailable: { bg: 'slot-unavailable',      dot: 'dot-unavailable',      text: 'text-unavailable',      label: '' },
}

const getPill = (info, past) => {
    const map = past ? PILL_PAST : PILL
    return map[slotKey(info)] ?? map.available
}

defineOptions({ layout: AppLayout })
</script>

<template>

    <!-- ── TOOLTIP ─────────────────────────────────────────────────────────── -->
    <Teleport to="body">
        <Transition name="fade">
            <div
                v-if="tooltipVisible && hoveredSlot"
                class="tooltip-box pointer-events-none fixed z-[9999] w-60 text-xs"
                :style="{ left: `${tooltip.x}px`, top: `${tooltip.y}px` }"
            >
                <template v-if="hoveredSlot.info.type === 'reservation'">
                    <div class="flex items-center gap-2.5 px-3 py-2.5 border-b tooltip-border">
                        <div class="h-7 w-7 rounded-full tooltip-avatar flex items-center justify-center font-bold text-[11px] flex-shrink-0">
                            {{ hoveredSlot.info.data.user_name?.charAt(0)?.toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold tooltip-text truncate">{{ hoveredSlot.info.data.user_name }}</p>
                            <p class="tooltip-muted truncate text-[11px]">{{ hoveredSlot.info.data.user_email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2">
                        <span class="tooltip-muted">
                            {{ formatTime(hoveredSlot.info.data.start_time) }} – {{ formatTime(hoveredSlot.info.data.end_time) }}
                        </span>
                        <span class="font-semibold px-1.5 py-0.5 rounded-full text-[11px]"
                            :class="`${getPill(hoveredSlot.info, false).bg} ${getPill(hoveredSlot.info, false).text}`">
                            {{ STATUS_LABELS[hoveredSlot.info.data.status] }}
                        </span>
                    </div>
                </template>
                <template v-else-if="hoveredSlot.info.type !== 'unavailable'">
                    <div class="flex items-center gap-2 px-3 py-2.5">
                        <div class="h-2 w-2 rounded-full flex-shrink-0"
                             :class="getPill(hoveredSlot.info, false).dot"></div>
                        <p class="font-semibold tooltip-text">
                            {{ hoveredSlot.info.type === 'blocked'
                                ? (hoveredSlot.info.data?.reason || 'Bloqueado')
                                : 'Disponible' }}
                        </p>
                    </div>
                </template>
            </div>
        </Transition>
    </Teleport>

    <!-- ── SIDE PANEL ──────────────────────────────────────────────────────── -->
    <Teleport to="body">
        <Transition name="panel-slide">
            <div
                v-if="selectedSlot"
                class="panel fixed inset-y-0 right-0 z-[9998] flex w-80 flex-col overflow-y-auto"
            >
                <!-- Panel header -->
                <div class="flex items-start justify-between px-5 py-5 panel-border-b">
                    <div>
                        <p class="panel-label mb-1.5">Detalle del bloque</p>
                        <p class="text-sm font-bold panel-title capitalize">{{ formatDay(selectedSlot.day) }}</p>
                        <p class="text-xs panel-muted mt-0.5">{{ selectedSlot.hour }} h</p>
                    </div>
                    <button @click="closePanel" class="panel-close h-7 w-7 flex items-center justify-center rounded-lg transition-colors mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                        </svg>
                    </button>
                </div>

                <!-- Panel body -->
                <div class="flex-1 p-5 space-y-3">

                    <!-- Available -->
                    <div v-if="selectedSlot.type === 'available'" class="flex flex-col items-center text-center py-12">
                        <div class="h-12 w-12 rounded-2xl panel-available-icon flex items-center justify-center mb-3">
                            <div class="h-3.5 w-3.5 rounded-full dot-available"></div>
                        </div>
                        <p class="font-semibold text-emerald-600 dark:text-emerald-400 text-sm">Disponible</p>
                        <p class="text-xs panel-muted mt-1">Sin reservas en este bloque.</p>
                    </div>

                    <!-- Blocked -->
                    <div v-else-if="selectedSlot.type === 'blocked'">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold mb-4 slot-blocked text-blocked">
                            <div class="h-1.5 w-1.5 rounded-full dot-blocked"></div> Bloqueado
                        </span>
                        <div class="panel-detail-block rounded-xl p-3.5">
                            <p class="panel-label mb-1.5">Motivo</p>
                            <p class="text-sm panel-title">{{ selectedSlot.data?.reason || 'Sin motivo indicado' }}</p>
                        </div>
                    </div>

                    <!-- Reservation -->
                    <div v-else-if="selectedSlot.type === 'reservation'" class="space-y-2.5">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold mb-2"
                            :class="`${getPill(selectedSlot, false).bg} ${getPill(selectedSlot, false).text}`">
                            <div class="h-1.5 w-1.5 rounded-full" :class="getPill(selectedSlot, false).dot"></div>
                            {{ STATUS_LABELS[selectedSlot.data.status] ?? selectedSlot.data.status }}
                        </span>

                        <div class="panel-detail-block rounded-xl p-3.5">
                            <p class="panel-label mb-2.5">Usuario</p>
                            <div class="flex items-center gap-2.5">
                                <div class="h-9 w-9 rounded-full panel-avatar flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ selectedSlot.data.user_name?.charAt(0)?.toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-semibold panel-title text-sm">{{ selectedSlot.data.user_name }}</p>
                                    <p class="text-xs panel-muted">{{ selectedSlot.data.user_email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="panel-detail-block rounded-xl p-3.5">
                            <p class="panel-label mb-1.5">Horario</p>
                            <p class="text-sm font-bold panel-title">
                                {{ formatTime(selectedSlot.data.start_time) }} — {{ formatTime(selectedSlot.data.end_time) }}
                            </p>
                        </div>

                        <div v-if="selectedSlot.data.notes" class="panel-detail-block rounded-xl p-3.5">
                            <p class="panel-label mb-1.5">Notas</p>
                            <p class="text-sm panel-title">{{ selectedSlot.data.notes }}</p>
                        </div>
                    </div>

                    <!-- Unavailable -->
                    <div v-else-if="selectedSlot.type === 'unavailable'" class="flex flex-col items-center text-center py-12">
                        <div class="h-12 w-12 rounded-2xl panel-unavailable-icon flex items-center justify-center mb-3 text-2xl">🚫</div>
                        <p class="font-semibold panel-muted text-sm">No disponible</p>
                        <p class="text-xs panel-muted mt-1">El espacio no opera en este horario.</p>
                    </div>

                </div>
            </div>
        </Transition>
    </Teleport>

    <!-- ── PAGE ────────────────────────────────────────────────────────────── -->
    <div class="page-bg min-h-screen">
        <div class="mx-auto max-w-screen-xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold page-title tracking-tight">Calendario</h1>
                    <p class="text-sm page-muted mt-0.5">Disponibilidad semanal de espacios</p>
                </div>

                <!-- Space selector -->
                <div class="space-select-wrap relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 page-icon">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <select
                        :value="selectedSpaceId"
                        @change="changeSpace"
                        class="space-select appearance-none h-10 rounded-xl pl-9 pr-8 text-sm font-medium outline-none min-w-[200px] transition"
                    >
                        <option value="">Seleccionar espacio</option>
                        <option v-for="s in spaces" :key="s.id" :value="s.id">{{ s.name }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 page-icon">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Week nav + legend -->
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button @click="previousWeek" class="nav-btn h-9 w-9 flex items-center justify-center rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div class="week-range h-9 px-4 flex items-center gap-2 rounded-xl">
                        <span class="text-sm font-semibold page-title">{{ weekRange }}</span>
                        <span v-if="isPastWeek" class="badge-past text-[10px] font-bold rounded-full px-2 py-0.5">Pasada</span>
                    </div>

                    <button @click="nextWeek" class="nav-btn h-9 w-9 flex items-center justify-center rounded-xl transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>

                <!-- Legend -->
                <div class="flex items-center gap-4 flex-wrap">
                    <span v-for="item in [
                        { label: 'Libre',      cls: 'dot-available' },
                        { label: 'Pendiente',  cls: 'dot-pending' },
                        { label: 'Confirmada', cls: 'dot-confirmed' },
                        { label: 'Finalizada', cls: 'dot-finished' },
                        { label: 'Bloqueado',  cls: 'dot-blocked' },
                    ]" :key="item.label" class="flex items-center gap-1.5 text-[11px] font-medium page-muted">
                        <div class="h-2 w-2 rounded-full" :class="item.cls"></div>
                        {{ item.label }}
                    </span>
                </div>
            </div>

            <!-- ── GRID ──────────────────────────────────────────────────── -->
            <div v-if="selectedSpace" class="calendar-wrap rounded-2xl overflow-hidden">

                <!-- Day headers (sticky) -->
                <div
                    class="grid calendar-head sticky top-0 z-10"
                    :style="`grid-template-columns: 52px repeat(${weekDays.length}, minmax(0,1fr))`"
                >
                    <div class="border-r calendar-border"></div>
                    <div
                        v-for="day in weekDays" :key="day"
                        class="border-l calendar-border py-3 px-1 text-center"
                        :class="isToday(day) ? 'today-col' : ''"
                    >
                        <p class="text-[10px] font-bold uppercase tracking-wider"
                           :class="isToday(day)
                               ? 'text-blue-500 dark:text-blue-400'
                               : isPastDay(day)
                                   ? 'day-past-label'
                                   : 'day-label'">
                            {{ new Date(day + 'T12:00:00')
                                .toLocaleDateString('es-CO', {
                                    weekday: 'short'
                                })
                            }}
                        </p>
                        <div class="flex justify-center mt-1">
                            <div class="h-7 w-7 flex items-center justify-center rounded-full text-sm font-bold"
                                 :class="isToday(day)
                                     ? 'bg-blue-600 text-white'
                                     : isPastDay(day)
                                         ? 'day-past-num'
                                         : 'day-num'">
                                {{ new Date(day + 'T12:00:00')
                                    .getDate()
                                }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hour rows -->
                <div class="overflow-x-auto">
                    <div
                        v-for="hour in timeSlots" :key="hour"
                        class="grid border-b calendar-row-border last:border-b-0"
                        :style="`grid-template-columns: 52px repeat(${weekDays.length}, minmax(0,1fr))`"
                    >
                        <!-- Hour label -->
                        <div class="flex items-center justify-end pr-2.5 border-r calendar-border hour-col select-none">
                            <span class="text-[10px] font-semibold hour-label tabular-nums">{{ hour }}</span>
                        </div>

                        <!-- Cells -->
                        <div
                            v-for="day in weekDays" :key="`${day}-${hour}`"
                            class="border-l calendar-border p-1"
                            :class="isToday(day) ? 'today-col-light' : isPastDay(day) ? 'past-col' : ''"
                        >
                            <button
                                @mouseenter="onCellEnter(day, hour)"
                                @mousemove="moveTooltip"
                                @mouseleave="hideTooltip"
                                @click="selectSlot(day, hour)"
                                class="w-full rounded-lg px-1.5 py-1.5 text-left transition-all duration-100 focus:outline-none"
                                :class="[
                                    getPill(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).bg,
                                    getSlotInfo(day, hour).type !== 'unavailable' ? 'cursor-pointer hover:brightness-95' : 'cursor-default',
                                    selectedSlot?.day === day && selectedSlot?.hour === hour ? 'ring-2 ring-inset ring-blue-400 dark:ring-blue-500' : '',
                                ]"
                            >
                                <!-- Dot + label (hidden when unavailable) -->
                                <template v-if="getSlotInfo(day, hour).type !== 'unavailable'">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <div class="h-1.5 w-1.5 rounded-full flex-shrink-0"
                                             :class="getPill(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).dot"></div>
                                        <span class="text-[11px] font-semibold truncate leading-none"
                                              :class="getPill(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).text">
                                            {{ getPill(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).label }}
                                        </span>
                                    </div>
                                    <!-- User name for reservations -->
                                    <p
                                        v-if="getSlotInfo(day, hour).type === 'reservation' && getSlotInfo(day, hour).data?.user_name"
                                        class="mt-0.5 text-[10px] truncate pl-3 leading-tight font-medium"
                                        :class="isPastDay(day) || isPastWeek ? 'text-slate-400 dark:text-slate-500' : 'slot-user'"
                                    >
                                        {{ getSlotInfo(day, hour).data.user_name }}
                                    </p>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="empty-state flex flex-col items-center justify-center rounded-2xl border-2 border-dashed py-28 text-center">
                <div class="mb-4 h-14 w-14 rounded-2xl empty-icon flex items-center justify-center text-3xl">🗓️</div>
                <p class="font-semibold page-title">Selecciona un espacio</p>
                <p class="mt-1 text-sm page-muted">Elige un espacio en el selector para ver su calendario semanal.</p>
            </div>

        </div>
    </div>
</template>

<style scoped>

/* ── Transitions ─────────────────────────────────────────────────────────── */
.fade-enter-active, .fade-leave-active     { transition: opacity 0.08s ease; }
.fade-enter-from,  .fade-leave-to         { opacity: 0; }
.panel-slide-enter-active,
.panel-slide-leave-active                  { transition: transform 0.2s cubic-bezier(0.4,0,0.2,1); }
.panel-slide-enter-from,
.panel-slide-leave-to                      { transform: translateX(100%); }

/* ── Page ────────────────────────────────────────────────────────────────── */
.page-bg    { background: #F8FAFC; }
.page-title { color: #0F172A; }
.page-muted { color: #94A3B8; }
.page-icon  { color: #94A3B8; }

@media (prefers-color-scheme: dark) {
    .page-bg    { background: #0F172A; }
    .page-title { color: #F1F5F9; }
    .page-muted { color: #64748B; }
    .page-icon  { color: #64748B; }
}

/* ── Space selector ──────────────────────────────────────────────────────── */
.space-select-wrap {}
.space-select {
    background: #fff;
    border: 1px solid #E2E8F0;
    color: #1E293B;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.space-select:hover  { border-color: #CBD5E1; }
.space-select:focus  { border-color: #60A5FA; box-shadow: 0 0 0 3px rgba(96,165,250,0.15); }
@media (prefers-color-scheme: dark) {
    .space-select {
        background: #1E293B;
        border-color: #334155;
        color: #E2E8F0;
    }
    .space-select:hover { border-color: #475569; }
    .space-select:focus { border-color: #3B82F6; }
}

/* ── Nav buttons ─────────────────────────────────────────────────────────── */
.nav-btn { background: #fff; border: 1px solid #E2E8F0; color: #64748B; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
.nav-btn:hover { background: #F8FAFC; border-color: #CBD5E1; }
@media (prefers-color-scheme: dark) {
    .nav-btn { background: #1E293B; border-color: #334155; color: #94A3B8; }
    .nav-btn:hover { background: #263348; border-color: #475569; }
}

/* ── Week range pill ─────────────────────────────────────────────────────── */
.week-range { background: #fff; border: 1px solid #E2E8F0; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
@media (prefers-color-scheme: dark) {
    .week-range { background: #1E293B; border-color: #334155; }
}

/* ── Badge past ──────────────────────────────────────────────────────────── */
.badge-past { background: #FEF3C7; color: #92400E; }
@media (prefers-color-scheme: dark) {
    .badge-past { background: #451A03; color: #FCD34D; }
}

/* ── Calendar grid ───────────────────────────────────────────────────────── */
.calendar-wrap      { background: #fff; border: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.calendar-head      { background: #F8FAFC; }
.calendar-border    { border-color: #E2E8F0; }
.calendar-row-border{ border-color: #F1F5F9; }
.hour-col           { background: #FAFAFA; }
.hour-label         { color: #94A3B8; }
.today-col          { background: #EFF6FF; }
.today-col-light    { background: rgba(239,246,255,0.4); }
.past-col           { background: rgba(248,250,252,0.6); }

@media (prefers-color-scheme: dark) {
    .calendar-wrap       { background: #1E293B; border-color: #334155; }
    .calendar-head       { background: #172032; }
    .calendar-border     { border-color: #293548; }
    .calendar-row-border { border-color: #243040; }
    .hour-col            { background: #172032; }
    .hour-label          { color: #475569; }
    .today-col           { background: rgba(37,99,235,0.12); }
    .today-col-light     { background: rgba(37,99,235,0.06); }
    .past-col            { background: rgba(15,23,42,0.3); }
}

/* ── Day header labels ───────────────────────────────────────────────────── */
.day-label     { color: #64748B; }
.day-num       { color: #1E293B; }
.day-past-label{ color: #94A3B8; }  /* Visible but softer */
.day-past-num  { color: #94A3B8; }

@media (prefers-color-scheme: dark) {
    .day-label     { color: #64748B; }
    .day-num       { color: #CBD5E1; }
    .day-past-label{ color: #475569; }
    .day-past-num  { color: #475569; }
}

/* ── Slot types — active ─────────────────────────────────────────────────── */
.slot-available   { background: #ECFDF5; }
.slot-blocked     { background: #FFFBEB; }
.slot-pending     { background: #EDE9FE; }
.slot-confirmed   { background: #EFF6FF; }
.slot-finished    { background: #F1F5F9; }
.slot-unavailable { background: transparent; }

.dot-available   { background: #34D399; }
.dot-blocked     { background: #FBBF24; }
.dot-pending     { background: #8B5CF6; }
.dot-confirmed   { background: #3B82F6; }
.dot-finished    { background: #94A3B8; }
.dot-unavailable { background: transparent; }

.text-available   { color: #065F46; }
.text-blocked     { color: #92400E; }
.text-pending     { color: #4C1D95; }
.text-confirmed   { color: #1E40AF; }
.text-finished    { color: #475569; }
.text-unavailable { color: transparent; }

.slot-user { color: #64748B; }

/* Dark mode — active slots */
@media (prefers-color-scheme: dark) {
    .slot-available   { background: rgba(52,211,153,0.12); }
    .slot-blocked     { background: rgba(251,191,36,0.1); }
    .slot-pending     { background: rgba(139,92,246,0.12); }
    .slot-confirmed   { background: rgba(59,130,246,0.12); }
    .slot-finished    { background: rgba(100,116,139,0.12); }
    .slot-unavailable { background: transparent; }

    .dot-available   { background: #34D399; }
    .dot-blocked     { background: #FBBF24; }
    .dot-pending     { background: #A78BFA; }
    .dot-confirmed   { background: #60A5FA; }
    .dot-finished    { background: #64748B; }
    .dot-unavailable { background: transparent; }

    .text-available   { color: #6EE7B7; }
    .text-blocked     { color: #FCD34D; }
    .text-pending     { color: #C4B5FD; }
    .text-confirmed   { color: #93C5FD; }
    .text-finished    { color: #94A3B8; }
    .text-unavailable { color: transparent; }

    .slot-user { color: #94A3B8; }
}

/* ── Slot types — past (softer, still readable) ──────────────────────────── */
.slot-available-past   { background: rgba(209,250,229,0.35); }
.slot-blocked-past     { background: rgba(254,243,199,0.35); }
.slot-pending-past     { background: rgba(237,233,254,0.35); }
.slot-confirmed-past   { background: rgba(239,246,255,0.35); }
.slot-finished-past    { background: rgba(241,245,249,0.35); }

.dot-available-past   { background: #86EFAC; }
.dot-blocked-past     { background: #FDE68A; }
.dot-pending-past     { background: #C4B5FD; }
.dot-confirmed-past   { background: #93C5FD; }
.dot-finished-past    { background: #CBD5E1; }

.text-available-past   { color: #86EFAC; }
.text-blocked-past     { color: #D97706; }
.text-pending-past     { color: #8B5CF6; }
.text-confirmed-past   { color: #60A5FA; }
.text-finished-past    { color: #94A3B8; }

@media (prefers-color-scheme: dark) {
    .slot-available-past   { background: rgba(52,211,153,0.05); }
    .slot-blocked-past     { background: rgba(251,191,36,0.05); }
    .slot-pending-past     { background: rgba(139,92,246,0.05); }
    .slot-confirmed-past   { background: rgba(59,130,246,0.05); }
    .slot-finished-past    { background: rgba(100,116,139,0.05); }

    .dot-available-past   { background: #34D39955; }
    .dot-blocked-past     { background: #FBBF2455; }
    .dot-pending-past     { background: #8B5CF655; }
    .dot-confirmed-past   { background: #3B82F655; }
    .dot-finished-past    { background: #64748B55; }

    .text-available-past   { color: #34D39988; }
    .text-blocked-past     { color: #FBBF2488; }
    .text-pending-past     { color: #8B5CF688; }
    .text-confirmed-past   { color: #60A5FA88; }
    .text-finished-past    { color: #64748B88; }
}

/* ── Tooltip ─────────────────────────────────────────────────────────────── */
.tooltip-box  { background: #fff; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.10); }
.tooltip-border{ border-color: #F1F5F9; }
.tooltip-text { color: #1E293B; }
.tooltip-muted{ color: #94A3B8; }
.tooltip-avatar{ background: #F1F5F9; color: #475569; }

@media (prefers-color-scheme: dark) {
    .tooltip-box   { background: #1E293B; border-color: #334155; }
    .tooltip-border{ border-color: #293548; }
    .tooltip-text  { color: #F1F5F9; }
    .tooltip-muted { color: #64748B; }
    .tooltip-avatar{ background: #334155; color: #94A3B8; }
}

/* ── Panel ───────────────────────────────────────────────────────────────── */
.panel        { background: #fff; border-left: 1px solid #E2E8F0; box-shadow: -8px 0 32px rgba(0,0,0,0.08); }
.panel-border-b { border-bottom: 1px solid #F1F5F9; }
.panel-label  { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .14em; color: #94A3B8; }
.panel-title  { color: #0F172A; }
.panel-muted  { color: #94A3B8; }
.panel-close  { color: #94A3B8; }
.panel-close:hover { background: #F1F5F9; color: #475569; }
.panel-avatar { background: #E2E8F0; color: #475569; }
.panel-detail-block { background: #F8FAFC; border: 1px solid #F1F5F9; }
.panel-available-icon  { background: #ECFDF5; border: 1px solid #A7F3D0; }
.panel-unavailable-icon{ background: #F1F5F9; border: 1px solid #E2E8F0; }

@media (prefers-color-scheme: dark) {
    .panel        { background: #1E293B; border-left-color: #334155; box-shadow: -8px 0 32px rgba(0,0,0,0.4); }
    .panel-border-b { border-bottom-color: #293548; }
    .panel-label  { color: #475569; }
    .panel-title  { color: #F1F5F9; }
    .panel-muted  { color: #64748B; }
    .panel-close  { color: #64748B; }
    .panel-close:hover { background: #293548; color: #94A3B8; }
    .panel-avatar { background: #334155; color: #94A3B8; }
    .panel-detail-block { background: #172032; border-color: #293548; }
    .panel-available-icon  { background: rgba(52,211,153,0.08); border-color: rgba(52,211,153,0.2); }
    .panel-unavailable-icon{ background: #172032; border-color: #293548; }
}

/* ── Empty state ─────────────────────────────────────────────────────────── */
.empty-state { border-color: #E2E8F0; }
.empty-icon  { background: #F1F5F9; }
@media (prefers-color-scheme: dark) {
    .empty-state { border-color: #293548; }
    .empty-icon  { background: #172032; }
}
</style>