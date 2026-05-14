<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

defineProps({
    spaces: Array
})

const typeIcons = {
    sala: '🏢',
    oficina: '💼',
    auditorio: '🎭',
    cabina: '🎙️',
    terraza: '🌿',
}

const getIcon = (type) => typeIcons[type?.toLowerCase()] ?? '📍'
</script>

<template>
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        <!-- HEADER -->
        <div class="relative mb-10 overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl p-8 sm:p-10">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-20 right-0 h-72 w-72 rounded-full bg-fuchsia-200/40 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 h-56 w-56 rounded-full bg-sky-200/35 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))]"></div>
            </div>

            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Disponibles ahora
                    </div>
                    <h1 class="text-4xl font-bold tracking-[-0.04em] text-slate-900 sm:text-5xl">
                        Espacios
                    </h1>
                    <p class="mt-2 text-slate-500">
                        Encuentra el espacio ideal para tu jornada de trabajo.
                    </p>
                </div>

                <div class="shrink-0 text-sm font-medium text-slate-400">
                    {{ spaces?.length ?? 0 }} espacio{{ spaces?.length !== 1 ? 's' : '' }} disponible{{ spaces?.length !== 1 ? 's' : '' }}
                </div>
            </div>
        </div>

        <!-- GRID DE ESPACIOS -->
        <div
            v-if="spaces?.length"
            class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3"
        >
            <div
                v-for="space in spaces"
                :key="space.id"
                class="group relative overflow-hidden rounded-[28px] border border-white/70 bg-white/85 shadow-[0_12px_40px_rgba(15,23,42,0.07)] backdrop-blur-xl transition duration-300 hover:shadow-[0_24px_60px_rgba(15,23,42,0.13)] hover:-translate-y-1"
            >
                <!-- Decoración interna -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-indigo-100/60 blur-2xl opacity-0 transition duration-500 group-hover:opacity-100"></div>
                </div>

                <!-- Cabecera de la card -->
                <div class="relative flex items-start justify-between border-b border-slate-100 p-6 pb-5">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 text-2xl shadow-[0_8px_20px_rgba(15,23,42,0.2)]">
                            {{ getIcon(space.type) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-bold tracking-[-0.02em] text-slate-900 leading-tight">
                                {{ space.name }}
                            </h2>
                            <span class="inline-block mt-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold capitalize text-slate-500">
                                {{ space.type }}
                            </span>
                        </div>
                    </div>

                    <!-- Precio -->
                    <div class="shrink-0 text-right">
                        <div class="text-xl font-bold tracking-tight text-slate-900">
                            ${{ space.price_per_hour }}
                        </div>
                        <div class="text-xs text-slate-400">/ hora</div>
                    </div>
                </div>

                <!-- Info -->
                <div class="relative px-6 py-5">
                    <p
                        v-if="space.description"
                        class="mb-5 line-clamp-2 text-sm leading-relaxed text-slate-500"
                    >
                        {{ space.description }}
                    </p>

                    <!-- Stats -->
                    <div class="mb-5 flex items-center gap-3">
                        <div class="flex items-center gap-1.5 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                            </svg>
                            <span class="font-semibold text-slate-700">{{ space.capacity }}</span>
                            <span class="text-slate-400">personas</span>
                        </div>

                        <div class="flex h-7 w-7 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        </div>
                    </div>

                    <!-- CTA -->
                    <Link
                        :href="`/spaces/${space.slug}`"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 py-3.5 text-sm font-semibold text-white shadow-[0_8px_24px_rgba(15,23,42,0.18)] transition duration-300 hover:bg-slate-800 group-hover:shadow-[0_12px_32px_rgba(15,23,42,0.24)]"
                    >
                        Ver disponibilidad
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 transition duration-300 group-hover:translate-x-0.5">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                        </svg>
                    </Link>
                </div>
            </div>
        </div>

        <!-- ESTADO VACÍO -->
        <div
            v-else
            class="flex flex-col items-center justify-center rounded-[32px] border border-white/70 bg-white/80 py-24 shadow-[0_20px_60px_rgba(15,23,42,0.06)] backdrop-blur-xl"
        >
            <div class="mb-5 flex h-20 w-20 items-center justify-center rounded-3xl bg-slate-100 text-4xl">
                🏢
            </div>
            <h3 class="text-xl font-bold text-slate-800">Sin espacios disponibles</h3>
            <p class="mt-2 text-slate-500">Vuelve pronto, estamos preparando nuevos espacios.</p>
        </div>

    </div>
</template>