<template>
    <div class="relative min-h-screen overflow-hidden bg-[#f6f7fb] flex items-center justify-center p-6">

        <!-- Fondo atmosférico -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,0.10),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,0.10),transparent_24%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_bottom,rgba(255,255,255,0.80),rgba(246,247,251,0.97))]"></div>
            <div class="absolute -top-32 -left-20 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl"></div>
            <div class="absolute -bottom-24 -right-16 h-80 w-80 rounded-full bg-fuchsia-200/40 blur-3xl"></div>
        </div>

        <!-- Card -->
        <div class="relative w-full max-w-lg overflow-hidden rounded-[36px] border border-white/70 bg-white/85 shadow-[0_30px_80px_rgba(15,23,42,0.10)] backdrop-blur-xl">
            <!-- Decoración interna -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-12 -right-12 h-48 w-48 rounded-full bg-emerald-100/70 blur-3xl"></div>
                <div class="absolute -bottom-12 -left-12 h-48 w-48 rounded-full bg-indigo-100/60 blur-3xl"></div>
            </div>

            <div class="relative p-10 text-center sm:p-14">
                <!-- Ícono principal -->
                <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-emerald-400 to-teal-500 shadow-[0_16px_40px_rgba(16,185,129,0.35)]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="h-12 w-12">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Badge -->
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3.5 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Calificación enviada
                </div>

                <h1 class="text-4xl font-bold tracking-[-0.04em] text-slate-900 sm:text-5xl">
                    ¡Muchas gracias!
                </h1>

                <p class="mx-auto mt-4 max-w-sm text-base leading-relaxed text-slate-500">
                    Tu opinión es muy valiosa y nos ayuda a mejorar continuamente nuestros espacios y servicios.
                </p>

                <!-- Estrellas decorativas -->
                <div class="my-8 flex justify-center gap-2">
                    <span v-for="n in 5" :key="n" class="text-3xl text-yellow-400">★</span>
                </div>

                <div class="mt-2 rounded-2xl border border-slate-200 bg-slate-50/80 px-6 py-4 text-sm text-slate-500">
                    Puedes cerrar esta ventana de forma segura.
                </div>

                <button
                    onclick="window.close()"
                    class="mt-6 w-full rounded-2xl bg-slate-900 px-6 py-4 text-sm font-semibold text-white shadow-[0_12px_32px_rgba(15,23,42,0.18)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800"
                >
                    Cerrar ventana
                </button>
            </div>
        </div>

    </div>
</template>