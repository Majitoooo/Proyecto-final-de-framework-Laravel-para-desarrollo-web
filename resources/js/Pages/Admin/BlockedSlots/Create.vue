<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    space: Object
})

const form = useForm({
    start_time: '',
    end_time: '',
    reason: ''
})

const submit = () => {
    form.post(
        `/admin/spaces/${props.space.slug}/blocked-slots`
    )
}

defineOptions({
    layout: AppLayout
})
</script>

<template>
    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl">
            <!-- Fondo decorativo -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 right-0 h-48 w-48 rounded-full bg-fuchsia-100/70 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 h-56 w-56 rounded-full bg-indigo-100/70 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.7),rgba(248,250,252,0.92))]"></div>
            </div>

            <div class="relative p-6 sm:p-8">
                <!-- Header -->
                <div class="mb-8 flex flex-col gap-4 border-b border-slate-200/70 pb-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="mb-3 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Gestión de disponibilidad
                        </div>

                        <h1 class="text-3xl font-semibold tracking-[-0.03em] text-slate-900">
                            Bloquear horario
                        </h1>

                        <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">
                            Define una franja no disponible para este espacio con una presentación visual más cuidada y consistente con el estilo premium del panel.
                        </p>
                    </div>

                    <Link
                        href="/admin/spaces"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-[0_8px_24px_rgba(15,23,42,0.05)] transition duration-300 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                    >
                        Volver
                    </Link>
                </div>

                <!-- Card info -->
                <div class="mb-6 rounded-[24px] border border-slate-200/80 bg-slate-50/80 p-5 shadow-inner">
                    <p class="text-sm text-slate-500">
                        Espacio
                    </p>

                    <div class="mt-2 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 text-sm font-bold text-white shadow-md">
                            {{ space.name?.charAt(0) }}
                        </div>

                        <div>
                            <p class="text-base font-semibold text-slate-900">
                                {{ space.name }}
                            </p>
                            <p class="text-sm text-slate-500">
                                Intervalo bloqueado manualmente por administración
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form
                    @submit.prevent="submit"
                    class="space-y-6"
                >
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">
                                Inicio
                            </label>

                            <input
                                v-model="form.start_time"
                                type="datetime-local"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-[0_8px_24px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700">
                                Fin
                            </label>

                            <input
                                v-model="form.end_time"
                                type="datetime-local"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-[0_8px_24px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100"
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700">
                            Motivo
                        </label>

                        <textarea
                            v-model="form.reason"
                            rows="5"
                            class="w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-[0_8px_24px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100"
                        ></textarea>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-200/70 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-sm text-slate-500">
                            Esta acción reservará internamente esa franja como no disponible.
                        </p>

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.18)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800"
                        >
                            Bloquear horario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
