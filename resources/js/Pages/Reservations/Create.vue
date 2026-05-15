<script setup>
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    space: {
        type: Object,
        default: () => ({})
    },
    start_time: String,
    end_time: String,
})

const form = useForm({
    space_id: props.space?.id ?? '',
    start_time: props.start_time ?? '',
    end_time: props.end_time ?? '',
    user_name: '',
    user_email: '',
    notes: '',
})

const submit = () => {
    form.post('/reservations')
}

const formatDateTime = (datetime) =>
    new Date(datetime).toLocaleString('es-CO', {
        dateStyle: 'long',
        timeStyle: 'short',
    })

const formatDuration = () => {
    const diff = (new Date(props.end_time) - new Date(props.start_time)) / 3600000
    return `${diff} hora${diff !== 1 ? 's' : ''}`
}

const estimatedPrice = () => {
    const diff = (new Date(props.end_time) - new Date(props.start_time)) / 3600000
    return (diff * props.space.price_per_hour).toLocaleString('es-CO')
}

defineOptions({ layout: AppLayout })
</script>

<template>
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_360px]">

            <!-- FORMULARIO -->
            <div class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
                <!-- Decoración -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-indigo-100/60 blur-3xl dark:bg-indigo-900/20"></div>
                    <div class="absolute -bottom-12 -left-12 h-40 w-40 rounded-full bg-fuchsia-100/50 blur-3xl dark:bg-fuchsia-900/20"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))] dark:bg-[linear-gradient(to_bottom_right,rgba(30,41,59,0.65),rgba(15,23,42,0.92))]"></div>
                </div>

                <div class="relative p-6 sm:p-8">
                    <!-- Header -->
                    <div class="mb-8 border-b border-slate-200/70 pb-6 dark:border-slate-700/70">
                        <div class="mb-3 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-400">
                            Nueva reserva
                        </div>
                        <h1 class="text-3xl font-bold tracking-[-0.03em] text-slate-900 dark:text-slate-100">
                            Completa tu reserva
                        </h1>
                        <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                            Ingresa tus datos para confirmar la reserva en
                            <span class="font-semibold text-slate-700 dark:text-slate-200">{{ space.name }}</span>.
                        </p>
                    </div>

                    <!-- Formulario -->
                    <form @submit.prevent="submit" class="space-y-5">

                        <!-- Nombre -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Nombre completo
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                    </svg>
                                </div>
                                <input
                                    v-model="form.user_name"
                                    type="text"
                                    placeholder="Tu nombre"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                >
                            </div>
                            <p v-if="form.errors.user_name" class="text-xs text-red-500">
                                {{ form.errors.user_name }}
                            </p>
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Correo electrónico
                            </label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                        <path d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                                        <path d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                                    </svg>
                                </div>
                                <input
                                    v-model="form.user_email"
                                    type="email"
                                    placeholder="tu@email.com"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                >
                            </div>
                            <p v-if="form.errors.user_email" class="text-xs text-red-500">
                                {{ form.errors.user_email }}
                            </p>
                        </div>

                        <!-- Notas -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Notas adicionales
                                <span class="ml-1 font-normal text-slate-400 dark:text-slate-500">(opcional)</span>
                            </label>
                            <textarea
                                v-model="form.notes"
                                rows="4"
                                placeholder="¿Algo que debamos saber? Equipos especiales, accesibilidad, etc."
                                class="w-full resize-none rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                            ></textarea>
                        </div>

                        <!-- Error general -->
                        <div
                            v-if="form.errors.reservation"
                            class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-600 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400"
                        >
                            {{ form.errors.reservation }}
                        </div>

                        <!-- Submit -->
                        <div class="border-t border-slate-200/70 pt-5">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-6 py-4 text-sm font-semibold text-white shadow-[0_12px_32px_rgba(15,23,42,0.20)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800 disabled:opacity-60 disabled:cursor-not-allowed dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-slate-50"
                            >
                                <svg v-if="form.processing" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                {{ form.processing ? 'Procesando...' : 'Confirmar reserva' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RESUMEN LATERAL -->
            <div class="space-y-4">

                <!-- Card del espacio -->
                <div class="relative overflow-hidden rounded-[28px] border border-white/70 bg-white/80 shadow-[0_16px_50px_rgba(15,23,42,0.08)] backdrop-blur-xl p-6 dark:border-slate-700/50 dark:bg-slate-800/80">
                    <div class="pointer-events-none absolute inset-0">
                        <div class="absolute -top-10 right-0 h-32 w-32 rounded-full bg-sky-100/70 blur-2xl dark:bg-sky-900/20"></div>
                    </div>
                    <div class="relative">
                        <p class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">Resumen</p>

                        <div class="mb-4 flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 text-lg font-bold text-white shadow-md dark:from-slate-100 dark:to-indigo-400 dark:text-slate-900">
                                {{ space.name?.charAt(0) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 dark:text-slate-100">{{ space.name }}</p>
                                <p class="text-xs capitalize text-slate-500 dark:text-slate-400">{{ space.type }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 rounded-2xl border border-slate-200 bg-slate-50/80 p-4 text-sm dark:border-slate-700 dark:bg-slate-700/50">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">Inicio</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ formatDateTime(start_time) }}</span>
                            </div>
                            <div class="border-t border-slate-200 dark:border-slate-600"></div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">Fin</span>
                                <span class="font-semibold text-slate-800 dark:text-slate-200">{{ formatDateTime(end_time) }}</span>
                            </div>
                            <div class="border-t border-slate-200 dark:border-slate-600"></div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium uppercase tracking-wide text-slate-400 dark:text-slate-500">Duración</span>
                                <span class="rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">{{ formatDuration() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Precio estimado -->
                <div class="overflow-hidden rounded-[28px] border border-indigo-200/70 bg-gradient-to-br from-indigo-50 to-slate-50 p-6 shadow-[0_8px_24px_rgba(99,102,241,0.10)] dark:border-indigo-800/50 dark:from-indigo-900/30 dark:to-slate-800">
                    <p class="mb-1 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-400 dark:text-indigo-300">Total estimado</p>
                    <p class="text-4xl font-bold tracking-[-0.04em] text-slate-900 dark:text-slate-100">
                        ${{ estimatedPrice() }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        {{ formatDuration() }} × ${{ space.price_per_hour }}/hora
                    </p>
                </div>

                <!-- Info de capacidad -->
                <div class="flex items-center gap-3 rounded-[24px] border border-slate-200 bg-white/80 p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 text-slate-500">
                            <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ space.capacity }} personas</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Capacidad máxima</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>