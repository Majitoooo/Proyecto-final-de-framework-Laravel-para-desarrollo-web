<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({ user: Object })

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role: props.user.role
})

const submit = () => {
    form.put(route('users.update', props.user.id))
}
</script>

<template>
    <div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">

        <!-- Encabezado -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <div class="mb-2 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                    Administración
                </div>
                <h1 class="text-3xl font-bold tracking-[-0.03em] text-slate-900 dark:text-slate-100">
                    Editar Usuario
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Modifica la información del usuario
                </p>
            </div>
            <Link
                :href="route('users.index')"
                class="flex items-center gap-1.5 rounded-2xl border border-slate-200 bg-white/80 px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm backdrop-blur-sm transition duration-200 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800/80 dark:text-slate-300 dark:hover:bg-slate-700"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path fill-rule="evenodd" d="M17 10a.75.75 0 0 1-.75.75H5.612l4.158 3.96a.75.75 0 1 1-1.04 1.08l-5.5-5.25a.75.75 0 0 1 0-1.08l5.5-5.25a.75.75 0 1 1 1.04 1.08L5.612 9.25H16.25A.75.75 0 0 1 17 10Z" clip-rule="evenodd" />
                </svg>
                Volver
            </Link>
        </div>

        <!-- Avatar + nombre del usuario -->
        <div class="mb-6 flex items-center gap-4 rounded-[28px] border border-white/70 bg-white/80 p-5 shadow-[0_8px_24px_rgba(15,23,42,0.06)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
            <img
                :src="user.profile_photo_url"
                class="h-14 w-14 rounded-2xl object-cover shadow-sm"
                :alt="user.name"
            >
            <div>
                <p class="font-bold text-slate-900 dark:text-slate-100">{{ user.name }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ user.email }}</p>
            </div>
            <span :class="[
                'ml-auto rounded-full px-3 py-1 text-xs font-semibold',
                user.role === 'admin'
                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                    : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
            ]">
                {{ user.role === 'admin' ? 'Administrador' : 'Usuario' }}
            </span>
        </div>

        <!-- Card principal -->
        <div class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
            <!-- Decoración -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-indigo-100/60 blur-3xl dark:bg-indigo-900/20"></div>
                <div class="absolute -bottom-12 -left-12 h-40 w-40 rounded-full bg-fuchsia-100/50 blur-3xl dark:bg-fuchsia-900/20"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))] dark:bg-[linear-gradient(to_bottom_right,rgba(30,41,59,0.65),rgba(15,23,42,0.92))]"></div>
            </div>

            <div class="relative p-6 sm:p-8">
                <p class="mb-6 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                    Información
                </p>

                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Nombre -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nombre</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                    <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                </svg>
                            </div>
                            <input
                                v-model="form.name"
                                type="text"
                                placeholder="Nombre completo"
                                class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                            >
                        </div>
                        <p v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <!-- Correo -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Correo electrónico</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                    <path d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                                    <path d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                                </svg>
                            </div>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="usuario@email.com"
                                class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                            >
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-red-500">{{ form.errors.email }}</p>
                    </div>

                    <!-- Contraseñas -->
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-4 dark:border-slate-700/50 dark:bg-slate-700/20">
                        <p class="mb-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 dark:text-slate-500">
                            Cambiar contraseña
                            <span class="ml-1 font-normal normal-case text-slate-400 dark:text-slate-500">— dejar vacío para mantener la actual</span>
                        </p>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Nueva contraseña</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.password"
                                        type="password"
                                        placeholder="••••••••"
                                        class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                    >
                                </div>
                                <p v-if="form.errors.password" class="text-xs text-red-500">{{ form.errors.password }}</p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Confirmar contraseña</label>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input
                                        v-model="form.password_confirmation"
                                        type="password"
                                        placeholder="••••••••"
                                        class="w-full rounded-2xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-slate-900 shadow-[0_4px_16px_rgba(15,23,42,0.04)] outline-none transition duration-300 placeholder:text-slate-400 focus:border-indigo-300 focus:ring-4 focus:ring-indigo-100 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-200 dark:placeholder:text-slate-500 dark:focus:border-indigo-500 dark:focus:ring-indigo-900/50"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rol -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Rol</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label :class="['flex cursor-pointer items-center gap-3 rounded-2xl border px-4 py-3 transition duration-200', form.role === 'user' ? 'border-indigo-300 bg-indigo-50 dark:border-indigo-600 dark:bg-indigo-900/30' : 'border-slate-200 bg-slate-50/80 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-700/30 dark:hover:border-slate-600']">
                                <input v-model="form.role" type="radio" value="user" class="sr-only">
                                <div :class="['flex h-9 w-9 items-center justify-center rounded-xl', form.role === 'user' ? 'bg-indigo-100 dark:bg-indigo-900/50' : 'bg-slate-100 dark:bg-slate-700']">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" :class="['h-4 w-4', form.role === 'user' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400']">
                                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                    </svg>
                                </div>
                                <div>
                                    <p :class="['text-sm font-semibold', form.role === 'user' ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-300']">Usuario</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">Acceso estándar</p>
                                </div>
                            </label>
                            <label :class="['flex cursor-pointer items-center gap-3 rounded-2xl border px-4 py-3 transition duration-200', form.role === 'admin' ? 'border-indigo-300 bg-indigo-50 dark:border-indigo-600 dark:bg-indigo-900/30' : 'border-slate-200 bg-slate-50/80 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-700/30 dark:hover:border-slate-600']">
                                <input v-model="form.role" type="radio" value="admin" class="sr-only">
                                <div :class="['flex h-9 w-9 items-center justify-center rounded-xl', form.role === 'admin' ? 'bg-indigo-100 dark:bg-indigo-900/50' : 'bg-slate-100 dark:bg-slate-700']">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" :class="['h-4 w-4', form.role === 'admin' ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400']">
                                        <path fill-rule="evenodd" d="M9.661 2.237a.531.531 0 0 1 .678 0 11.947 11.947 0 0 0 7.078 2.749.5.5 0 0 1 .479.425c.069.52.104 1.05.104 1.589 0 5.162-3.26 9.563-7.89 11.2a.46.46 0 0 1-.31 0C5.26 16.564 2 12.163 2 7c0-.538.035-1.069.104-1.589a.5.5 0 0 1 .48-.425 11.947 11.947 0 0 0 7.077-2.749Zm4.196 5.954a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p :class="['text-sm font-semibold', form.role === 'admin' ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-300']">Administrador</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">Acceso total</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="flex items-center justify-end gap-3 border-t border-slate-200/70 pt-5 dark:border-slate-700/70">
                        <Link
                            :href="route('users.index')"
                            class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition duration-200 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_32px_rgba(15,23,42,0.20)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-slate-50"
                        >
                            <svg v-if="form.processing" class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                            </svg>
                            {{ form.processing ? 'Guardando...' : 'Actualizar' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</template>