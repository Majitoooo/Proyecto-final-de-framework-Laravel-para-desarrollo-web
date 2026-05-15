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
                class="group relative overflow-hidden rounded-[28px] border border-white/70 bg-white/85 shadow-[0_12px_40px_rgba(15,23,42,0.07)] backdrop-blur-xl transition duration-300 hover:shadow-[0_24px_60px_rgba(15,23,42,0.13)] hover:-translate-y-1 dark:bg-slate-800/85 dark:border-slate-700/50"
            >
                <!-- Decoración interna -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-indigo-100/60 blur-2xl opacity-0 transition duration-500 group-hover:opacity-100"></div>
                </div>

                 <!-- IMAGEN PRINCIPAL -->
                 <div class="relative h-64 w-full overflow-hidden bg-slate-100 dark:bg-slate-900">
                     <img
                         v-if="space.image"
                         :src="`/storage/${space.image}`"
                         :alt="space.name"
                         class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                     >
                     <div
                         v-else
                         class="h-full w-full flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 dark:from-slate-800 dark:via-slate-900 dark:to-indigo-900"
                     >
                         <span class="text-7xl">{{ getIcon(space.type) }}</span>
                     </div>
                     <!-- Badge flotante sobre la imagen -->
                     <div class="absolute top-3 right-3">
                         <div class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 backdrop-blur-sm shadow-md border border-white/50 dark:bg-slate-800/90 dark:border-slate-600">
                             <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                         </div>
                     </div>
                 </div>

                  <!-- Cabecera de la card -->
                  <div class="relative px-6 pb-6">
                      <div class="mt-4 flex items-start justify-between">
                          <div class="flex-1 space-y-1">
                              <h2 class="text-2xl font-bold tracking-[-0.02em] text-slate-900 leading-tight dark:text-slate-100">
                                  {{ space.name }}
                              </h2>
                              <div class="flex items-center gap-2 flex-wrap">
                                  <span class="inline-block rounded-full bg-indigo-50 px-3 py-1 text-sm font-semibold capitalize text-indigo-600 border border-indigo-100 dark:bg-indigo-900/40 dark:text-indigo-300 dark:border-indigo-800">
                                      {{ space.type }}
                                  </span>
                                  <span class="flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5">
                                          <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                                      </svg>
                                      {{ space.capacity }} personas
                                  </span>
                              </div>
                          </div>

                          <!-- Precio badge -->
                          <div class="shrink-0 -mt-1 bg-gradient-to-r from-pink-500 to-rose-500 text-white px-4 py-2 rounded-xl shadow-md dark:from-pink-600 dark:to-rose-600">
                              <div class="text-2xl font-bold tracking-tight">
                                  ${{ space.price_per_hour }}
                              </div>
                              <div class="text-[10px] uppercase tracking-wider opacity-90">/ hora</div>
                          </div>
                      </div>

                <!-- Info -->
                <div class="relative px-6 pt-4">
                    <p
                        v-if="space.description"
                        class="mb-5 line-clamp-2 text-sm leading-relaxed text-slate-500"
                    >
                        {{ space.description }}
                    </p>


                <!-- CTA PRINCIPAL -->
                <div class="mt-5">
                    <Link
                        :href="`/spaces/${space.slug}`"
                        class="flex w-full items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-slate-900 to-slate-800 px-5 py-4 text-sm font-semibold text-white shadow-lg transition duration-300 hover:from-slate-800 hover:to-slate-900 group-hover:shadow-xl hover:shadow-xl dark:from-slate-100 dark:to-slate-200 dark:text-slate-900 dark:hover:from-slate-50 dark:hover:to-slate-100"
                    >
                        Ver espacio y reservar
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 transition duration-300 group-hover:translate-x-0.5">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                        </svg>
                    </Link>
                </div>
                </div>
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