<script setup>
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    spaces:          Array,
    selectedSpaceId: [String, Number],
    date:            String,
    slots:           Array,
})

const updateFilters = (event) => {
    const form = new FormData(event.target.form)
    router.get(
        route('calendar.public'),
        { space_id: form.get('space_id'), date: form.get('date') },
        { preserveState: true, replace: true }
    )
}

const goToReservation = (slot) => {
    router.get(route('reservations.create'), {
        space_id: props.selectedSpaceId,
        start_time: slot.full_start,
    })
}

// Format "08:00" → "8:00 AM" style kept as-is since slots already come formatted
const formatDate = (dateStr) => {
    if (!dateStr) return ''
    const d = new Date(dateStr + 'T00:00:00')
    return d.toLocaleDateString('es-CO', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
}

defineOptions({ layout: AppLayout })
</script>

<template>
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-200">
        <div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- ── Header ── -->
            <div class="mb-8">
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 mb-2">Coworking Reserve</p>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Disponibilidad</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Consulta los horarios libres y encuentra el ideal para ti.
                </p>
            </div>

            <!-- ── Filters card ── -->
            <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">Filtrar disponibilidad</h2>
                </div>
                <div class="p-5">
                    <form class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        <!-- Espacio -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                Espacio
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 text-slate-400">
                                        <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <select
                                    name="space_id"
                                    @change="updateFilters"
                                    class="appearance-none w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-9 pr-9 py-2.5 text-sm font-medium text-slate-800 dark:text-white outline-none transition hover:border-slate-300 dark:hover:border-slate-600 focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                >
                                    <option value="">Selecciona un espacio</option>
                                    <option
                                        v-for="space in spaces"
                                        :key="space.id"
                                        :value="space.id"
                                        :selected="selectedSpaceId == space.id"
                                    >
                                        {{ space.name }}
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 text-slate-400">
                                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Fecha -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                Fecha
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 text-slate-400">
                                        <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input
                                    type="date"
                                    name="date"
                                    :value="date"
                                    @change="updateFilters"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-9 pr-3 py-2.5 text-sm font-medium text-slate-800 dark:text-white outline-none transition hover:border-slate-300 dark:hover:border-slate-600 focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                />
                            </div>
                        </div>

                    </form>
                </div>
            </section>

            <!-- ── Results ── -->
            <div v-if="slots && slots.length">

                <!-- Date heading -->
                <div class="mb-4 flex items-center gap-3">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 capitalize">
                        {{ formatDate(date) }}
                    </p>
                    <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800"></div>
                    <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-full px-2.5 py-1">
                        {{ slots.length }} {{ slots.length === 1 ? 'horario disponible' : 'horarios disponibles' }}
                    </span>
                </div>

                <!-- Slots grid -->
                <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                    <div
                        v-for="slot in slots"
                        :key="slot.full_start"
                        @click="goToReservation(slot)"
                        class="group rounded-xl border border-emerald-200 dark:border-emerald-900 bg-white dark:bg-slate-900 px-4 py-4 transition-all duration-150 hover:border-emerald-400 dark:hover:border-emerald-700 hover:shadow-md hover:shadow-emerald-100 dark:hover:shadow-emerald-900/30 cursor-pointer"
                    >
                        <!-- Dot + label -->
                        <div class="flex items-center gap-1.5 mb-3">
                            <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 flex-shrink-0"></div>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-500">Libre</span>
                        </div>

                        <!-- Start time — big -->
                        <p class="text-2xl font-bold text-slate-900 dark:text-white leading-none tabular-nums">
                            {{ slot.start }}
                        </p>

                        <!-- End time -->
                        <p class="mt-2 text-xs text-slate-400 dark:text-slate-500 font-medium">
                            hasta <span class="font-semibold text-slate-600 dark:text-slate-300">{{ slot.end }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- ── Empty state ── -->
            <div
                v-else
                class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 py-24 text-center"
            >
                <div class="mb-4 h-14 w-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl">🗓️</div>
                <p class="font-bold text-slate-800 dark:text-white">
                    {{ selectedSpaceId ? 'No hay horarios disponibles' : 'Selecciona un espacio' }}
                </p>
                <p class="mt-1.5 text-sm text-slate-400 dark:text-slate-500 max-w-xs">
                    {{ selectedSpaceId ? 'Prueba con otra fecha o espacio.' : 'Elige un espacio y una fecha para ver su disponibilidad.' }}
                </p>
            </div>

        </div>
    </div>
</template>