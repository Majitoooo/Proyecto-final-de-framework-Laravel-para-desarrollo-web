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

const timeSlots = computed(() => {
    if (!props.availabilities?.length) return []
    const earliest = props.availabilities.reduce(
        (min, item) => item.start_time < min ? item.start_time : min, '23:59:59'
    )
    const latest = props.availabilities.reduce(
        (max, item) => item.end_time > max ? item.end_time : max, '00:00:00'
    )
    const slots = []
    let hour = parseInt(earliest.split(':')[0])
    const end = parseInt(latest.split(':')[0])
    while (hour < end) { slots.push(`${String(hour).padStart(2, '0')}:00`); hour++ }
    return slots
})

const getSlotInfo = (day, hour) => {
    const slotStart = new Date(`${day}T${hour}:00`)
    const blocked = props.blockedSlots?.find(slot => {
        const s = new Date(slot.start_time), e = new Date(slot.end_time)
        return slotStart >= s && slotStart < e
    })
    if (blocked) return { type: 'blocked', data: blocked }
    const reservation = props.reservations?.find(r => {
        const s = new Date(r.start_time), e = new Date(r.end_time)
        return slotStart >= s && slotStart < e
    })
    if (reservation) {
        if (reservation.status === 'rejected') return { type: 'available' }
        return { type: 'reservation', data: reservation }
    }
    return { type: 'available' }
}

const selectedSlot = ref(null)
const selectSlot = (day, hour) => {
    const info = getSlotInfo(day, hour)
    if (selectedSlot.value?.day === day && selectedSlot.value?.hour === hour) {
        selectedSlot.value = null; return
    }
    selectedSlot.value = { day, hour, ...info }
}
const closePanel = () => { selectedSlot.value = null }

const hoveredSlot    = ref(null)
const tooltipVisible = ref(false)
const tooltip        = reactive({ x: 0, y: 0 })
const onCellEnter = (day, hour) => { hoveredSlot.value = { day, hour, info: getSlotInfo(day, hour) } }
const moveTooltip = (event) => {
    tooltipVisible.value = true
    const TW = 256, TH = 110, M = 12
    tooltip.x = event.clientX + TW > window.innerWidth  - M ? event.clientX - TW - 12 : event.clientX + 14
    tooltip.y = event.clientY + TH > window.innerHeight - M ? event.clientY - TH - 12 : event.clientY + 14
}
const hideTooltip = () => { tooltipVisible.value = false; hoveredSlot.value = null }

const isPastWeek = computed(() => {
    const today = new Date(); today.setHours(0,0,0,0)
    const ws = new Date(props.currentWeek), we = new Date(ws)
    we.setDate(we.getDate() + 6); we.setHours(23,59,59,999)
    return we < today
})
const isPastDay = (day) => { const t = new Date(); t.setHours(0,0,0,0); return new Date(day) < t }
const isToday   = (day) => new Date(day).toDateString() === new Date().toDateString()

const changeSpace  = (e) => router.get('/admin/calendar', { space_id: e.target.value, week: props.currentWeek })
const previousWeek = () => { const d = new Date(props.currentWeek); d.setDate(d.getDate()-7); router.get('/admin/calendar', { space_id: props.selectedSpaceId, week: d.toISOString().split('T')[0] }) }
const nextWeek     = () => { const d = new Date(props.currentWeek); d.setDate(d.getDate()+7); router.get('/admin/calendar', { space_id: props.selectedSpaceId, week: d.toISOString().split('T')[0] }) }

const formatTime = (iso)  => new Date(iso).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })
const formatDay  = (date) => new Date(date).toLocaleDateString('es-CO', { weekday: 'long', day: 'numeric', month: 'long' })
const formatWeekRange = computed(() => {
    if (!props.weekDays?.length) return props.currentWeek
    const f = new Date(props.weekDays[0]), l = new Date(props.weekDays[props.weekDays.length-1])
    return `${f.toLocaleDateString('es-CO',{day:'numeric',month:'short'})} – ${l.toLocaleDateString('es-CO',{day:'numeric',month:'short',year:'numeric'})}`
})

const STATUS_LABELS = { pending: 'Pendiente', confirmed: 'Confirmada', finished: 'Finalizada', rejected: 'Rechazada' }

const CONFIGS = {
    available: {
        live: { bg:'bg-emerald-50', border:'border-emerald-200', hover:'hover:bg-emerald-100 hover:border-emerald-300', dot:'bg-emerald-500', text:'text-emerald-800', sub:'text-emerald-600' },
        past: { bg:'bg-emerald-50/30', border:'border-emerald-100', dot:'bg-emerald-200', text:'text-emerald-300', sub:'text-emerald-200' },
    },
    blocked: {
        live: { bg:'bg-amber-50', border:'border-amber-200', hover:'hover:bg-amber-100 hover:border-amber-300', dot:'bg-amber-400', text:'text-amber-900', sub:'text-amber-600' },
        past: { bg:'bg-amber-50/30', border:'border-amber-100', dot:'bg-amber-200', text:'text-amber-300', sub:'text-amber-200' },
    },
    pending: {
        live: { bg:'bg-violet-50', border:'border-violet-200', hover:'hover:bg-violet-100 hover:border-violet-300', dot:'bg-violet-500', text:'text-violet-900', sub:'text-violet-500' },
        past: { bg:'bg-violet-50/30', border:'border-violet-100', dot:'bg-violet-200', text:'text-violet-300', sub:'text-violet-200' },
    },
    confirmed: {
        live: { bg:'bg-blue-50', border:'border-blue-200', hover:'hover:bg-blue-100 hover:border-blue-300', dot:'bg-blue-500', text:'text-blue-900', sub:'text-blue-500' },
        past: { bg:'bg-blue-50/30', border:'border-blue-100', dot:'bg-blue-200', text:'text-blue-300', sub:'text-blue-200' },
    },
    finished: {
        live: { bg:'bg-slate-100', border:'border-slate-200', hover:'hover:bg-slate-150 hover:border-slate-300', dot:'bg-slate-400', text:'text-slate-700', sub:'text-slate-400' },
        past: { bg:'bg-slate-50/40', border:'border-slate-100', dot:'bg-slate-200', text:'text-slate-300', sub:'text-slate-200' },
    },
}

const getConfig = (info, past = false) => {
    const key = info.type === 'reservation' ? (info.data?.status ?? 'available') : info.type
    return (CONFIGS[key] ?? CONFIGS.available)[past ? 'past' : 'live']
}
const getLabel  = (info) => info.type === 'available' ? 'Libre' : info.type === 'blocked' ? 'Bloqueado' : STATUS_LABELS[info.data?.status] ?? '—'

defineOptions({ layout: AppLayout })
</script>

<template>

    <!-- TOOLTIP -->
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="tooltipVisible && hoveredSlot"
                 class="pointer-events-none fixed z-[9999] w-64 rounded-xl border border-slate-200 bg-white shadow-xl text-[13px] overflow-hidden"
                 :style="{ left: `${tooltip.x}px`, top: `${tooltip.y}px` }">
                <template v-if="hoveredSlot.info.type === 'reservation'">
                    <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100">
                        <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-xs flex-shrink-0">
                            {{ hoveredSlot.info.data.user_name?.charAt(0)?.toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-900 truncate text-xs">{{ hoveredSlot.info.data.user_name }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ hoveredSlot.info.data.user_email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between px-4 py-2.5">
                        <span class="text-[11px] text-slate-400">{{ formatTime(hoveredSlot.info.data.start_time) }} – {{ formatTime(hoveredSlot.info.data.end_time) }}</span>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full"
                            :class="{ 'bg-violet-100 text-violet-700': hoveredSlot.info.data.status === 'pending', 'bg-blue-100 text-blue-700': hoveredSlot.info.data.status === 'confirmed', 'bg-slate-100 text-slate-600': hoveredSlot.info.data.status === 'finished' }">
                            {{ STATUS_LABELS[hoveredSlot.info.data.status] }}
                        </span>
                    </div>
                </template>
                <template v-else>
                    <div class="flex items-center gap-2.5 px-4 py-3">
                        <div class="h-2 w-2 rounded-full flex-shrink-0" :class="hoveredSlot.info.type === 'blocked' ? 'bg-amber-400' : 'bg-emerald-500'"></div>
                        <div>
                            <p class="font-semibold text-slate-900 text-xs">{{ hoveredSlot.info.type === 'blocked' ? 'Bloqueado' : 'Disponible' }}</p>
                            <p v-if="hoveredSlot.info.type === 'blocked'" class="text-[11px] text-slate-400 mt-0.5">{{ hoveredSlot.info.data?.reason || 'Sin motivo' }}</p>
                        </div>
                    </div>
                </template>
            </div>
        </Transition>
    </Teleport>

    <!-- SIDE PANEL -->
    <Teleport to="body">
        <Transition name="panel-slide">
            <div v-if="selectedSlot"
                 class="fixed inset-y-0 right-0 z-[9998] flex w-[340px] flex-col border-l border-slate-200 bg-white shadow-2xl overflow-y-auto">
                <div class="flex items-start justify-between px-6 py-6 border-b border-slate-100">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400 mb-2">Detalle</p>
                        <p class="text-base font-bold text-slate-900 capitalize">{{ formatDay(selectedSlot.day) }}</p>
                        <p class="text-sm text-slate-400 mt-0.5">{{ selectedSlot.hour }}:00 h</p>
                    </div>
                    <button @click="closePanel" class="mt-0.5 h-7 w-7 flex items-center justify-center rounded-md hover:bg-slate-100 text-slate-400 hover:text-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 p-6 space-y-3">
                    <!-- Available -->
                    <div v-if="selectedSlot.type === 'available'" class="flex flex-col items-center text-center py-14">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center mb-4">
                            <div class="h-4 w-4 rounded-full bg-emerald-500"></div>
                        </div>
                        <p class="font-bold text-emerald-800">Horario disponible</p>
                        <p class="text-sm text-slate-400 mt-1.5">Sin reservas en este bloque.</p>
                    </div>
                    <!-- Blocked -->
                    <div v-else-if="selectedSlot.type === 'blocked'">
                        <div class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-800 mb-5">
                            <div class="h-1.5 w-1.5 rounded-full bg-amber-500"></div> Bloqueado
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 mb-2">Motivo</p>
                            <p class="text-sm text-slate-700">{{ selectedSlot.data?.reason || 'Sin motivo indicado' }}</p>
                        </div>
                    </div>
                    <!-- Reservation -->
                    <div v-else-if="selectedSlot.type === 'reservation'" class="space-y-3">
                        <div class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold mb-2"
                            :class="{ 'bg-violet-100 text-violet-800': selectedSlot.data.status === 'pending', 'bg-blue-100 text-blue-800': selectedSlot.data.status === 'confirmed', 'bg-slate-100 text-slate-700': selectedSlot.data.status === 'finished' }">
                            <div class="h-1.5 w-1.5 rounded-full"
                                :class="{ 'bg-violet-500': selectedSlot.data.status === 'pending', 'bg-blue-500': selectedSlot.data.status === 'confirmed', 'bg-slate-400': selectedSlot.data.status === 'finished' }"></div>
                            {{ STATUS_LABELS[selectedSlot.data.status] ?? selectedSlot.data.status }}
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 mb-3">Usuario</p>
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-700 text-sm flex-shrink-0">
                                    {{ selectedSlot.data.user_name?.charAt(0)?.toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">{{ selectedSlot.data.user_name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ selectedSlot.data.user_email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 mb-2">Horario</p>
                            <p class="text-sm font-bold text-slate-800">{{ formatTime(selectedSlot.data.start_time) }} — {{ formatTime(selectedSlot.data.end_time) }}</p>
                        </div>
                        <div v-if="selectedSlot.data.notes" class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400 mb-2">Notas</p>
                            <p class="text-sm text-slate-700">{{ selectedSlot.data.notes }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>

    <!-- PAGE -->
    <div class="min-h-screen bg-white">
        <div class="mx-auto max-w-[1440px] px-6 py-8 lg:px-10">

            <!-- Header -->
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-2">Administrador</p>
                    <h1 class="text-[28px] font-bold tracking-tight text-slate-900 leading-none">Calendario</h1>
                    <p class="text-sm text-slate-500 mt-2">Reservas · Bloqueos · Disponibilidad</p>
                </div>
                <div class="relative flex-shrink-0">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 text-slate-400">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <select :value="selectedSpaceId" @change="changeSpace"
                        class="appearance-none h-10 rounded-lg border border-slate-200 bg-white pl-9 pr-9 text-sm font-medium text-slate-800 outline-none transition hover:border-slate-300 focus:border-blue-400 focus:ring-2 focus:ring-blue-100 min-w-[210px]">
                        <option value="">Seleccionar espacio</option>
                        <option v-for="space in spaces" :key="space.id" :value="space.id">{{ space.name }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 text-slate-400">
                            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Week nav + Legend -->
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button @click="previousWeek"
                        class="h-9 w-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <div class="px-4 h-9 flex items-center rounded-lg border border-slate-200 bg-slate-50">
                        <p class="text-sm font-semibold text-slate-900">{{ formatWeekRange }}</p>
                    </div>
                    <button @click="nextWeek"
                        class="h-9 w-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    <span v-if="isPastWeek" class="text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 rounded-full px-2.5 py-1">Semana pasada</span>
                </div>
                <div class="hidden sm:flex items-center gap-4">
                    <span v-for="item in [
                        { label: 'Libre',      dot: 'bg-emerald-500' },
                        { label: 'Pendiente',  dot: 'bg-violet-500'  },
                        { label: 'Confirmada', dot: 'bg-blue-500'    },
                        { label: 'Finalizada', dot: 'bg-slate-400'   },
                        { label: 'Bloqueado',  dot: 'bg-amber-400'   },
                    ]" :key="item.label" class="flex items-center gap-1.5 text-[12px] font-medium text-slate-500">
                        <div class="h-2 w-2 rounded-full" :class="item.dot"></div>
                        {{ item.label }}
                    </span>
                </div>
            </div>

            <!-- Calendar grid -->
            <div v-if="selectedSpace" class="rounded-xl border border-slate-200 overflow-hidden">

                <!-- Day headers -->
                <div class="grid border-b border-slate-200 bg-slate-50"
                     :style="`grid-template-columns: 68px repeat(${weekDays.length}, 1fr)`">
                    <div class="py-4 border-r border-slate-200"></div>
                    <div v-for="day in weekDays" :key="day"
                         class="border-l border-slate-200 py-4 px-3 text-center"
                         :class="isToday(day) ? 'bg-blue-50' : ''">
                        <p class="text-[10px] font-bold uppercase tracking-[0.15em]"
                           :class="isToday(day) ? 'text-blue-500' : isPastDay(day) ? 'text-slate-300' : 'text-slate-400'">
                            {{ new Date(day).toLocaleDateString('es-CO', { weekday: 'short' }) }}
                        </p>
                        <div class="flex justify-center mt-1.5">
                            <div class="h-8 w-8 flex items-center justify-center rounded-full text-sm font-bold"
                                 :class="isToday(day) ? 'bg-blue-600 text-white' : isPastDay(day) ? 'text-slate-300' : 'text-slate-800'">
                                {{ new Date(day).toLocaleDateString('es-CO', { day: 'numeric' }) }}
                            </div>
                        </div>
                        <p class="text-[10px] mt-1"
                           :class="isToday(day) ? 'text-blue-400' : isPastDay(day) ? 'text-slate-300' : 'text-slate-400'">
                            {{ new Date(day).toLocaleDateString('es-CO', { month: 'short' }) }}
                        </p>
                    </div>
                </div>

                <!-- Rows -->
                <div class="overflow-x-auto bg-white">
                    <div v-for="hour in timeSlots" :key="hour"
                         class="grid border-b border-slate-100 last:border-b-0"
                         :style="`grid-template-columns: 68px repeat(${weekDays.length}, 1fr)`">

                        <!-- Hour label -->
                        <div class="flex items-center justify-end pr-4 py-2 border-r border-slate-100 bg-slate-50/60">
                            <span class="text-[11px] font-bold text-slate-400 tabular-nums">{{ hour }}</span>
                        </div>

                        <!-- Cells -->
                        <div v-for="day in weekDays" :key="`${day}-${hour}`"
                             class="border-l border-slate-100 p-2"
                             :class="isToday(day) ? 'bg-blue-50/15' : ''">
                            <button
                                :disabled="isPastDay(day) || isPastWeek"
                                @mouseenter="onCellEnter(day, hour)"
                                @mousemove="moveTooltip"
                                @mouseleave="hideTooltip"
                                @click="selectSlot(day, hour)"
                                class="group w-full rounded-lg border px-3 py-3 text-left transition-all duration-150 focus:outline-none"
                                :class="[
                                    getConfig(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).bg,
                                    getConfig(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).border,
                                    !(isPastDay(day) || isPastWeek) && getConfig(getSlotInfo(day, hour), false).hover,
                                    !(isPastDay(day) || isPastWeek) ? 'cursor-pointer' : 'cursor-default',
                                    selectedSlot?.day === day && selectedSlot?.hour === hour ? 'ring-2 ring-offset-1 ring-blue-400' : '',
                                ]"
                            >
                                <!-- Dot + status label -->
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 rounded-full flex-shrink-0 transition-transform duration-150 group-hover:scale-125"
                                         :class="getConfig(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).dot"></div>
                                    <span class="text-[12px] font-bold leading-none truncate"
                                          :class="getConfig(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).text">
                                        {{ getLabel(getSlotInfo(day, hour)) }}
                                    </span>
                                </div>
                                <!-- User name sub-line for reservations -->
                                <p v-if="getSlotInfo(day, hour).type === 'reservation' && getSlotInfo(day, hour).data?.user_name"
                                   class="mt-1.5 text-[11px] truncate pl-4 font-medium leading-tight"
                                   :class="getConfig(getSlotInfo(day, hour), isPastDay(day) || isPastWeek).sub">
                                    {{ getSlotInfo(day, hour).data.user_name }}
                                </p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div v-else class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 py-28 text-center">
                <div class="mb-4 h-14 w-14 rounded-2xl bg-slate-100 flex items-center justify-center text-3xl">🗓️</div>
                <p class="text-base font-bold text-slate-900">Selecciona un espacio</p>
                <p class="mt-1.5 text-sm text-slate-400 max-w-xs">Elige un espacio en el selector para ver su calendario.</p>
            </div>

        </div>
    </div>

</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.08s ease; }
.fade-enter-from, .fade-leave-to       { opacity: 0; }

.panel-slide-enter-active, .panel-slide-leave-active {
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
.panel-slide-enter-from, .panel-slide-leave-to { transform: translateX(100%); }
</style>