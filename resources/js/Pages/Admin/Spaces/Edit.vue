<script setup>

import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    space: Object
})

const getAvailability = (day) => {
    const availability = props.space.availabilities?.find(item => item.day_of_week === day)
    return {
        day_of_week: day,
        start_time: availability?.start_time?.slice(0, 5) ?? '',
        end_time: availability?.end_time?.slice(0, 5) ?? ''
    }
}

const form = useForm({
    name: props.space.name,
    type: props.space.type,
    capacity: props.space.capacity,
    description: props.space.description,
    usage_rules: props.space.usage_rules,
    price_per_hour: props.space.price_per_hour,
    is_active: props.space.is_active,
    image: null,
    availability: [
        getAvailability(1),
        getAvailability(2),
        getAvailability(3),
        getAvailability(4),
        getAvailability(5),
        getAvailability(6),
        getAvailability(0)
    ]
})

const applyToAllDays = () => {
    const monday = form.availability.find(day => day.day_of_week === 1)
    if (!monday.start_time || !monday.end_time) {
        alert('Primero llena el horario del lunes')
        return
    }
    form.availability.forEach(day => {
        day.start_time = monday.start_time
        day.end_time = monday.end_time
    })
}

const submit = () => {
    form.put(`/admin/spaces/${props.space.slug}`)
}

defineOptions({ layout: AppLayout })

</script>

<template>

    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

        <!-- Encabezado de página -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <div class="mb-2 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                    Administración
                </div>
                <h1 class="text-3xl font-bold tracking-[-0.03em] text-slate-900 dark:text-slate-100">
                    Editar Espacio
                </h1>
            </div>
            <Link
                href="/admin/spaces"
                class="flex items-center gap-1.5 rounded-2xl border border-slate-200 bg-white/80 px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm backdrop-blur-sm transition duration-200 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800/80 dark:text-slate-300 dark:hover:bg-slate-700"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Volver
            </Link>
        </div>

        <form @submit.prevent="submit" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1fr_320px]">

            <!-- COLUMNA PRINCIPAL -->
            <div class="space-y-6">

                <!-- Card: Información básica -->
                <div class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
                    <!-- Decoración -->
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-indigo-100/60 blur-3xl dark:bg-indigo-900/20"></div>
                        <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))] dark:bg-[linear-gradient(to_bottom_right,rgba(30,41,59,0.65),rgba(15,23,42,0.92))]"></div>
                    </div>

                    <div class="relative p-6 sm:p-8">
                        <p class="mb-5 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                            Información básica
                        </p>

                        <div class="grid gap-5 sm:grid-cols-2">

                            <!-- Nombre -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Nombre
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a1 1 0 1 1 0 2H4a1 1 0 1 1 0-2V4zm3 1h2v2H7V5zm0 4h2v2H7V9zm0 4h2v2H7v-2zm4-8h2v2h-2V5zm0 4h2v2h-2V9zm0 4h2v2h-2v-2z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        placeholder="Nombre del espacio"
                                        class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                    >
                                </div>
                            </div>

                            <!-- Tipo -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Tipo
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                            <path d="M10 1a6 6 0 0 0-3.815 10.631C7.237 12.5 8 13.443 8 14.456v.644a.75.75 0 0 0 .572.729 6.016 6.016 0 0 0 2.856 0A.75.75 0 0 0 12 15.1v-.644c0-1.013.762-1.957 1.815-2.825A6 6 0 0 0 10 1ZM8.863 17.414a.75.75 0 0 0-.226 1.483 9.066 9.066 0 0 0 2.726 0 .75.75 0 0 0-.226-1.483 7.553 7.553 0 0 1-2.274 0Z" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.type"
                                        type="text"
                                        placeholder="Ej: sala, auditorio..."
                                        class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                    >
                                </div>
                            </div>

                            <!-- Capacidad -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Capacidad
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                            <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.capacity"
                                        type="number"
                                        placeholder="Nº de personas"
                                        class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                    >
                                </div>
                            </div>

                            <!-- Precio por hora -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Precio por hora
                                </label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                            <path d="M10.75 10.818v2.614A3.13 3.13 0 0 0 11.888 13c.482-.315.612-.648.612-.875 0-.227-.13-.56-.612-.875a3.13 3.13 0 0 0-1.138-.432ZM8.33 8.62c.053.055.115.11.184.164.208.16.46.284.736.363V6.603a2.45 2.45 0 0 0-.35.13c-.14.065-.27.143-.386.233-.377.292-.514.627-.514.909 0 .184.058.39.33.576ZM9.25 3.75v.345a6.166 6.166 0 0 0-.191.026c-.832.14-1.545.527-2.041 1.134-.49.601-.768 1.411-.768 2.395 0 .756.182 1.47.548 2.05.35.557.85.96 1.452 1.182v2.748a4.035 4.035 0 0 1-.453-.106c-.408-.116-.902-.36-1.164-.657a.75.75 0 0 0-1.146.965c.42.499 1.153.861 1.862 1.066.24.068.487.122.739.161V16.25a.75.75 0 0 0 1.5 0v-.37a6.743 6.743 0 0 0 .232-.05c.931-.218 1.535-.725 1.919-1.327.375-.586.483-1.218.483-1.703 0-.831-.22-1.561-.67-2.114-.585-.729-1.45-1.079-2.163-1.225v-2.82c.107.028.209.067.306.113.387.19.618.465.777.704a.75.75 0 0 0 1.262-.808c-.272-.423-.69-.848-1.243-1.136a4.62 4.62 0 0 0-1.102-.383V3.75a.75.75 0 0 0-1.5 0Z" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.price_per_hour"
                                        type="number"
                                        placeholder="0.00"
                                        class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                    >
                                </div>
                            </div>

                        </div>

                        <!-- Descripción -->
                        <div class="mt-5 space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Descripción
                            </label>
                            <textarea
                                v-model="form.description"
                                rows="3"
                                placeholder="Describe el espacio..."
                                class="w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                            ></textarea>
                        </div>

                        <!-- Reglas de uso -->
                        <div class="mt-5 space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Reglas de uso
                            </label>
                            <textarea
                                v-model="form.usage_rules"
                                rows="4"
                                placeholder="Normas y condiciones del espacio..."
                                class="w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                            ></textarea>
                        </div>

                    </div>
                </div>

                <!-- Card: Imagen -->
                <div class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -bottom-12 -left-12 h-40 w-40 rounded-full bg-fuchsia-100/50 blur-3xl dark:bg-fuchsia-900/20"></div>
                        <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))] dark:bg-[linear-gradient(to_bottom_right,rgba(30,41,59,0.65),rgba(15,23,42,0.92))]"></div>
                    </div>
                    <div class="relative p-6 sm:p-8">
                        <p class="mb-5 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                            Imagen del espacio
                        </p>

                        <label class="flex w-full cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/80 py-8 transition duration-300 hover:border-indigo-300 hover:bg-indigo-50/50 dark:border-slate-600 dark:bg-slate-700/50 dark:hover:border-indigo-500 dark:hover:bg-indigo-900/20">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 text-slate-400">
                                    <path fill-rule="evenodd" d="M1 5.25A2.25 2.25 0 0 1 3.25 3h13.5A2.25 2.25 0 0 1 19 5.25v9.5A2.25 2.25 0 0 1 16.75 17H3.25A2.25 2.25 0 0 1 1 14.75v-9.5Zm1.5 5.81v3.69c0 .414.336.75.75.75h13.5a.75.75 0 0 0 .75-.75v-2.69l-2.22-2.219a.75.75 0 0 0-1.06 0l-1.91 1.909.47.47a.75.75 0 1 1-1.06 1.06L6.53 8.091a.75.75 0 0 0-1.06 0l-2.97 2.97ZM12 7a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Haz clic para subir una imagen
                                </p>
                                <p class="text-xs text-slate-400 dark:text-slate-500">PNG, JPG, WEBP hasta 10MB</p>
                            </div>
                            <input
                                type="file"
                                class="hidden"
                                accept="image/*"
                                @change="(e) => form.image = e.target.files[0]"
                            >
                        </label>

                        <div v-if="space.image" class="mt-4 flex items-center gap-4 rounded-2xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-700/50">
                            <img
                                :src="`/storage/${space.image}`"
                                alt="Imagen actual"
                                class="h-16 w-24 rounded-xl object-cover shadow-sm"
                            >
                            <div>
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    Imagen actual
                                </p>
                                <p class="text-xs text-slate-400 dark:text-slate-500">
                                    Sube una nueva para reemplazarla
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Disponibilidad -->
                <div class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -top-16 left-1/2 h-48 w-48 -translate-x-1/2 rounded-full bg-sky-100/60 blur-3xl dark:bg-sky-900/20"></div>
                        <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))] dark:bg-[linear-gradient(to_bottom_right,rgba(30,41,59,0.65),rgba(15,23,42,0.92))]"></div>
                    </div>
                    <div class="relative p-6 sm:p-8">
                        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                Disponibilidad semanal
                            </p>
                            <button
                                type="button"
                                @click="applyToAllDays"
                                class="flex items-center gap-1.5 rounded-xl border border-pink-200 bg-pink-50 px-3 py-1.5 text-xs font-semibold text-pink-600 transition duration-200 hover:bg-pink-100 dark:border-pink-800/50 dark:bg-pink-900/30 dark:text-pink-400 dark:hover:bg-pink-900/50"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                    <path fill-rule="evenodd" d="M8 2a.75.75 0 0 1 .75.75v4.5h4.5a.75.75 0 0 1 0 1.5h-4.5v4.5a.75.75 0 0 1-1.5 0v-4.5h-4.5a.75.75 0 0 1 0-1.5h4.5v-4.5A.75.75 0 0 1 8 2Z" clip-rule="evenodd" />
                                </svg>
                                Aplicar horario del lunes a todos
                            </button>
                        </div>

                        <!-- Cabecera de columnas (solo visible en sm+) -->
                        <div class="mb-2 hidden grid-cols-3 gap-4 px-1 sm:grid">
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">Día</span>
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">Apertura</span>
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">Cierre</span>
                        </div>

                        <div class="space-y-2">
                            <div
                                v-for="day in form.availability"
                                :key="day.day_of_week"
                                class="grid grid-cols-3 items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50/60 px-4 py-3 transition duration-200 hover:border-slate-200 hover:bg-white dark:border-slate-700/50 dark:bg-slate-700/30 dark:hover:border-slate-600 dark:hover:bg-slate-700/60"
                            >
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                    {{
                                        ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'][day.day_of_week]
                                    }}
                                </span>
                                <input
                                    v-model="day.start_time"
                                    type="time"
                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition duration-200 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                >
                                <input
                                    v-model="day.end_time"
                                    type="time"
                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none transition duration-200 focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                >
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- COLUMNA LATERAL -->
            <div class="space-y-4 lg:sticky lg:top-6 lg:self-start">

                <!-- Estado activo -->
                <div class="relative overflow-hidden rounded-[28px] border border-white/70 bg-white/80 shadow-[0_16px_50px_rgba(15,23,42,0.08)] backdrop-blur-xl p-6 dark:border-slate-700/50 dark:bg-slate-800/80">
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -top-10 right-0 h-32 w-32 rounded-full bg-emerald-100/70 blur-2xl dark:bg-emerald-900/20"></div>
                    </div>
                    <div class="relative">
                        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                            Estado
                        </p>
                        <label class="flex cursor-pointer items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 transition duration-200 hover:bg-slate-100/80 dark:border-slate-700 dark:bg-slate-700/50 dark:hover:bg-slate-700">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 items-center justify-center rounded-xl" :class="form.is_active ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-slate-100 dark:bg-slate-700'">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4" :class="form.is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400'">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                        {{ form.is_active ? 'Espacio activo' : 'Espacio inactivo' }}
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">
                                        {{ form.is_active ? 'Visible para reservas' : 'No disponible' }}
                                    </p>
                                </div>
                            </div>
                            <!-- Toggle switch -->
                            <div class="relative">
                                <input v-model="form.is_active" type="checkbox" class="peer sr-only">
                                <div class="h-6 w-11 rounded-full bg-slate-200 transition-colors duration-300 peer-checked:bg-emerald-500 dark:bg-slate-600 dark:peer-checked:bg-emerald-500"></div>
                                <div class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform duration-300 peer-checked:translate-x-5"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Resumen del espacio -->
                <div class="overflow-hidden rounded-[28px] border border-indigo-200/70 bg-gradient-to-br from-indigo-50 to-slate-50 p-6 shadow-[0_8px_24px_rgba(99,102,241,0.10)] dark:border-indigo-800/50 dark:from-indigo-900/30 dark:to-slate-800">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-400 dark:text-indigo-300">
                        Vista previa
                    </p>

                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 text-lg font-bold text-white shadow-md dark:from-slate-100 dark:to-indigo-400 dark:text-slate-900">
                            {{ form.name?.charAt(0) ?? '?' }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-slate-100">
                                {{ form.name || 'Sin nombre' }}
                            </p>
                            <p class="text-xs capitalize text-slate-500 dark:text-slate-400">
                                {{ form.type || 'Sin tipo' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2.5 rounded-2xl border border-indigo-100 bg-white/60 p-4 text-sm dark:border-indigo-800/30 dark:bg-slate-800/60">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">Capacidad</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ form.capacity || '—' }} personas</span>
                        </div>
                        <div class="border-t border-slate-100 dark:border-slate-700"></div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">Precio/hora</span>
                            <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                ${{ form.price_per_hour ? Number(form.price_per_hour).toLocaleString('es-CO') : '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Botón guardar -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-6 py-4 text-sm font-semibold text-white shadow-[0_12px_32px_rgba(15,23,42,0.20)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-slate-50"
                >
                    <svg v-if="form.processing" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                    </svg>
                    {{ form.processing ? 'Guardando...' : 'Guardar cambios' }}
                </button>

            </div>

        </form>

    </div>

</template>