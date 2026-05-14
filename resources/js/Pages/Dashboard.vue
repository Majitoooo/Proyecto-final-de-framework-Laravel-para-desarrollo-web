<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import Welcome from '@/Components/Welcome.vue'
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

            <!-- Bienvenida hero -->
            <div class="relative mb-10 overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl p-8 sm:p-12">
                <!-- Decoración -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 h-64 w-64 rounded-full bg-fuchsia-200/40 blur-3xl"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.6),rgba(248,250,252,0.9))]"></div>
                </div>

                <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                            Panel de usuario
                        </div>
                        <h1 class="text-4xl font-bold tracking-[-0.04em] text-slate-900 sm:text-5xl">
                            Bienvenido de vuelta
                        </h1>
                        <p class="mt-3 max-w-lg text-base leading-7 text-slate-500">
                            Explora los espacios disponibles, gestiona tus reservas y encuentra el lugar perfecto para trabajar.
                        </p>
                    </div>

                    <!-- CTA rápido -->
                    <div class="flex shrink-0 flex-col gap-3 sm:flex-row lg:flex-col">
                        <a
                            href="/spaces"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 px-6 py-3.5 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" />
                            </svg>
                            Explorar espacios
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenido principal del Welcome component -->
            <div class="overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.06)] backdrop-blur-xl">
                <Welcome />
            </div>

        </div>
    </AppLayout>
</template>