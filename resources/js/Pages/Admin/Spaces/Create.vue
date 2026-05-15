<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
    name: '',
    type: '',
    capacity: 1,
    description: '',
    price_per_hour: '',
    usage_rules: '',
    is_active: true,
    image: null,
    availability: [
        { day_of_week: 1, start_time: '', end_time: '' },
        { day_of_week: 2, start_time: '', end_time: '' },
        { day_of_week: 3, start_time: '', end_time: '' },
        { day_of_week: 4, start_time: '', end_time: '' },
        { day_of_week: 5, start_time: '', end_time: '' },
        { day_of_week: 6, start_time: '', end_time: '' },
        { day_of_week: 0, start_time: '', end_time: '' },
    ],
})

const DAY_NAMES = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado']

const submit = () => form.post('/admin/spaces')

const applyToAllDays = () => {
    const monday = form.availability.find(d => d.day_of_week === 1)
    if (!monday.start_time || !monday.end_time) {
        alert('Primero llena el horario del lunes')
        return
    }
    form.availability.forEach(d => {
        d.start_time = monday.start_time
        d.end_time   = monday.end_time
    })
}

defineOptions({ layout: AppLayout })
</script>

<template>
    <!-- Fondo general que respeta dark mode del layout -->
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-200">
        <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- ── Header ── -->
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-2">
                    <Link
                        href="/admin/spaces"
                        class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-colors uppercase tracking-widest"
                    >
                        Espacios
                    </Link>
                    <span class="text-slate-300 dark:text-slate-600 text-xs">/</span>
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Nuevo</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Crear espacio
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Configura la información y disponibilidad del nuevo espacio.
                </p>
            </div>

            <!-- ── Form card ── -->
            <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-6">

                <!-- Section: Información general -->
                <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">Información general</h2>
                    </div>
                    <div class="p-5 space-y-4">

                        <!-- Nombre + Tipo -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                    Nombre <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Ej. Sala de juntas A"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-xs text-rose-500">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                    Tipo
                                </label>
                                <input
                                    v-model="form.type"
                                    type="text"
                                    placeholder="Ej. Sala, Oficina, Auditorio"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                />
                            </div>
                        </div>

                        <!-- Capacidad + Precio -->
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                    Capacidad
                                </label>
                                <div class="relative">
                                    <input
                                        v-model="form.capacity"
                                        type="number"
                                        min="1"
                                        class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                    />
                                    <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs text-slate-400">personas</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                    Precio por hora
                                </label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-sm text-slate-400">$</span>
                                    <input
                                        v-model="form.price_per_hour"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        placeholder="0.00"
                                        class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-7 pr-3 py-2.5 text-sm text-slate-900 dark:text-white outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                Descripción
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Describe brevemente el espacio..."
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 resize-none"
                            ></textarea>
                        </div>

                        <!-- Reglas de uso -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                Reglas de uso
                            </label>
                            <textarea
                                v-model="form.usage_rules"
                                rows="4"
                                placeholder="Indica las normas o restricciones del espacio..."
                                class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900 resize-none"
                            ></textarea>
                        </div>

                        <!-- Imagen -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5 uppercase tracking-wide">
                                Imagen del espacio
                            </label>
                            <label
                                class="flex flex-col items-center justify-center w-full rounded-lg border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 py-6 px-4 cursor-pointer transition hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50/30 dark:hover:bg-blue-900/10 group"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-8 w-8 text-slate-300 dark:text-slate-600 mb-2 group-hover:text-blue-400 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                </svg>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 group-hover:text-blue-500 transition-colors">
                                    <span class="font-semibold">Haz clic para subir</span> o arrastra aquí
                                </p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">PNG, JPG, WEBP — máx. 10 MB</p>
                                <p v-if="form.image" class="mt-2 text-xs font-semibold text-blue-500">
                                    {{ form.image.name }}
                                </p>
                                <input
                                    type="file"
                                    accept="image/*"
                                    class="sr-only"
                                    @change="(e) => form.image = e.target.files[0]"
                                />
                            </label>
                            <p v-if="form.errors.image" class="mt-1 text-xs text-rose-500">{{ form.errors.image }}</p>
                        </div>

                    </div>
                </section>

                <!-- Section: Disponibilidad semanal -->
                <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-3 flex-wrap">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white tracking-tight">Disponibilidad semanal</h2>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Define el horario de apertura por día.</p>
                        </div>
                        <button
                            type="button"
                            @click="applyToAllDays"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 text-slate-400">
                                <path d="M7 3.5A1.5 1.5 0 0 1 8.5 2h3.879a1.5 1.5 0 0 1 1.06.44l3.122 3.12A1.5 1.5 0 0 1 17 6.622V12.5a1.5 1.5 0 0 1-1.5 1.5h-1v-3.379a3 3 0 0 0-.879-2.121L10.5 5.379A3 3 0 0 0 8.379 4.5H7v-1Z"/>
                                <path d="M4.5 6A1.5 1.5 0 0 0 3 7.5v9A1.5 1.5 0 0 0 4.5 18h7a1.5 1.5 0 0 0 1.5-1.5v-5.879a1.5 1.5 0 0 0-.44-1.06L9.44 6.439A1.5 1.5 0 0 0 8.378 6H4.5Z"/>
                            </svg>
                            Copiar lunes a todos
                        </button>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                        <div
                            v-for="day in form.availability"
                            :key="day.day_of_week"
                            class="grid grid-cols-[100px_1fr_1fr] sm:grid-cols-[120px_1fr_1fr] items-center gap-3 px-5 py-3"
                        >
                            <!-- Day name -->
                            <div class="flex items-center gap-2">
                                <!-- Weekend indicator -->
                                <div
                                    class="h-1.5 w-1.5 rounded-full flex-shrink-0"
                                    :class="[0,6].includes(day.day_of_week) ? 'bg-violet-400' : 'bg-emerald-400'"
                                ></div>
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    {{ DAY_NAMES[day.day_of_week] }}
                                </span>
                            </div>

                            <!-- Start time -->
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-2.5 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3 text-slate-300 dark:text-slate-600">
                                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Zm7.75-4.25a.75.75 0 0 0-1.5 0V8c0 .414.336.75.75.75h3.25a.75.75 0 0 0 0-1.5h-2.5v-3.5Z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <input
                                    v-model="day.start_time"
                                    type="time"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-7 pr-2 py-2 text-sm text-slate-900 dark:text-white outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                />
                            </div>

                            <!-- End time -->
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-2.5 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3 w-3 text-slate-300 dark:text-slate-600">
                                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8Zm7.75-4.25a.75.75 0 0 0-1.5 0V8c0 .414.336.75.75.75h3.25a.75.75 0 0 0 0-1.5h-2.5v-3.5Z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <input
                                    v-model="day.end_time"
                                    type="time"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 pl-7 pr-2 py-2 text-sm text-slate-900 dark:text-white outline-none transition focus:border-blue-400 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-900"
                                />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section: Estado -->
                <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-5 py-4">
                    <label class="flex items-center justify-between cursor-pointer gap-4 group">
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Espacio activo</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Los usuarios podrán ver y reservar este espacio.</p>
                        </div>
                        <!-- Toggle switch -->
                        <div class="relative flex-shrink-0">
                            <input v-model="form.is_active" type="checkbox" class="sr-only peer" />
                            <div class="w-11 h-6 rounded-full border border-slate-200 dark:border-slate-700 bg-slate-200 dark:bg-slate-700 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-colors duration-200"></div>
                            <div class="absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-200 peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                </section>

                <!-- Footer actions -->
                <div class="flex items-center justify-between gap-3 pt-1">
                    <Link
                        href="/admin/spaces"
                        class="inline-flex h-10 items-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                    >
                        Cancelar
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex h-10 items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 px-6 text-sm font-bold text-white shadow-sm transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <svg v-if="form.processing" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 animate-spin">
                            <path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43a7 7 0 0 0 11.58-3.144.75.75 0 0 0-1.007-.873Z" clip-rule="evenodd"/>
                        </svg>
                        {{ form.processing ? 'Guardando...' : 'Guardar espacio' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>