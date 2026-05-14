<template>
    <div class="relative min-h-screen overflow-hidden bg-[#f6f7fb] flex items-center justify-center p-6">

        <!-- Fondo atmosférico -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.09),transparent_28%),radial-gradient(circle_at_bottom_left,rgba(245,158,11,0.08),transparent_24%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_bottom,rgba(255,255,255,0.80),rgba(246,247,251,0.97))]"></div>
            <div class="absolute -top-28 -right-20 h-80 w-80 rounded-full bg-amber-200/35 blur-3xl"></div>
            <div class="absolute -bottom-20 -left-16 h-72 w-72 rounded-full bg-indigo-200/35 blur-3xl"></div>
        </div>

        <!-- Card -->
        <div class="relative w-full max-w-lg overflow-hidden rounded-[36px] border border-white/70 bg-white/85 shadow-[0_30px_80px_rgba(15,23,42,0.10)] backdrop-blur-xl">
            <!-- Decoración interna -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-12 right-0 h-48 w-48 rounded-full bg-amber-100/70 blur-3xl"></div>
                <div class="absolute -bottom-12 left-0 h-48 w-48 rounded-full bg-slate-100/80 blur-3xl"></div>
            </div>

            <div class="relative p-10 text-center sm:p-14">
                <!-- Ícono principal -->
                <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-amber-400 to-orange-500 shadow-[0_16px_40px_rgba(245,158,11,0.30)]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="h-12 w-12">
                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Badge -->
                <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3.5 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                    Ya calificada
                </div>

                <h1 class="text-4xl font-bold tracking-[-0.04em] text-slate-900 sm:text-5xl">
                    Ya calificaste
                </h1>

                <p class="mx-auto mt-4 max-w-sm text-base leading-relaxed text-slate-500">
                    Esta reserva ya fue calificada anteriormente. Solo se permite una calificación por reserva.
                </p>

                <!-- Estrellas decorativas (tenues) -->
                <div class="my-8 flex justify-center gap-2">
                    <span v-for="n in 5" :key="n" class="text-3xl text-slate-300">★</span>
                </div>

                <div class="mt-2 rounded-2xl border border-slate-200 bg-slate-50/80 px-6 py-4 text-sm text-slate-500">
                    ¡Gracias por tu retroalimentación!
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