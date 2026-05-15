<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link, router } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

const props = defineProps({ users: Array })

const deleting = ref(null)

const toggleRole = (user) => {
    const confirmText = user.role === 'admin' ? 'usuario' : 'administrador'
    if (!window.confirm(`¿Cambiar el rol de ${user.name} a ${confirmText}?`)) return
    router.patch(route('users.toggle.role', user.id))
}

const confirmDelete = (user) => {
    if (!window.confirm(`¿Eliminar a ${user.name}?`)) return
    deleting.value = user.id
    router.delete(route('users.destroy', user.id))
}
</script>

<template>
    <div class="px-4 py-10 sm:px-6 lg:px-8">

        <!-- Encabezado -->
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="mb-2 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                    Administración
                </div>
                <h1 class="text-3xl font-bold tracking-[-0.03em] text-slate-900 dark:text-slate-100">
                    Gestión de Usuarios
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Administra usuarios y permisos del sistema
                </p>
            </div>
            <Link
                :href="route('users.create')"
                class="flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_32px_rgba(15,23,42,0.20)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-slate-50"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                    <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
                </svg>
                Nuevo Usuario
            </Link>
        </div>

        <!-- TABLA (md+) -->
        <div class="hidden md:block">
            <div class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
                <!-- Decoración -->
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-indigo-100/40 blur-3xl dark:bg-indigo-900/10"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))] dark:bg-[linear-gradient(to_bottom_right,rgba(30,41,59,0.65),rgba(15,23,42,0.92))]"></div>
                </div>

                <div class="relative overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200/70 dark:border-slate-700/70">
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 dark:text-slate-500">Usuario</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 dark:text-slate-500">Rol</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 dark:text-slate-500">Reservas</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-[0.15em] text-slate-400 dark:text-slate-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="group transition duration-200 hover:bg-slate-50/80 dark:hover:bg-slate-700/30"
                            >
                                <!-- Usuario -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <img
                                            :src="user.profile_photo_url"
                                            :alt="user.name"
                                            class="h-11 w-11 rounded-2xl object-cover shadow-sm ring-2 ring-white dark:ring-slate-700"
                                        >
                                        <div>
                                            <p class="font-semibold text-slate-800 dark:text-slate-200">{{ user.name }}</p>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ user.email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Rol -->
                                <td class="px-6 py-4">
                                    <span :class="[
                                        'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold',
                                        user.role === 'admin'
                                            ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                            : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
                                    ]">
                                        <span :class="['h-1.5 w-1.5 rounded-full', user.role === 'admin' ? 'bg-indigo-500' : 'bg-emerald-500']"></span>
                                        {{ user.role === 'admin' ? 'Administrador' : 'Usuario' }}
                                    </span>
                                </td>

                                <!-- Reservas -->
                                <td class="px-6 py-4">
                                    <span class="rounded-xl bg-slate-100 px-3 py-1 text-sm font-bold text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                        {{ user.reservations_count }}
                                    </span>
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link
                                            :href="route('users.edit', user.id)"
                                            class="rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 shadow-sm transition duration-200 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600"
                                        >
                                            Editar
                                        </Link>
                                        <button
                                            @click="toggleRole(user)"
                                            class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition duration-200 hover:bg-amber-100 dark:border-amber-800/50 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-900/40"
                                        >
                                            {{ user.role === 'admin' ? 'Hacer Usuario' : 'Hacer Admin' }}
                                        </button>
                                        <button
                                            @click="confirmDelete(user)"
                                            :disabled="deleting === user.id"
                                            class="rounded-xl border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition duration-200 hover:bg-red-100 disabled:opacity-50 dark:border-red-800/50 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40"
                                        >
                                            {{ deleting === user.id ? 'Eliminando...' : 'Eliminar' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Vacío -->
                            <tr v-if="!users.length">
                                <td colspan="4" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-7 w-7 text-slate-400">
                                                <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No hay usuarios registrados</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- CARDS (móvil, < md) -->
        <div class="space-y-3 md:hidden">

            <div v-if="!users.length" class="rounded-[28px] border border-white/70 bg-white/80 p-10 text-center shadow backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80">
                <p class="text-sm text-slate-400 dark:text-slate-500">No hay usuarios registrados</p>
            </div>

            <div
                v-for="user in users"
                :key="user.id"
                class="relative overflow-hidden rounded-[28px] border border-white/70 bg-white/80 shadow-[0_8px_24px_rgba(15,23,42,0.07)] backdrop-blur-xl dark:border-slate-700/50 dark:bg-slate-800/80"
            >
                <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.6),rgba(248,250,252,0.9))] dark:bg-[linear-gradient(to_bottom_right,rgba(30,41,59,0.6),rgba(15,23,42,0.9))]"></div>
                <div class="relative p-5">

                    <!-- Header de la card -->
                    <div class="flex items-center gap-4">
                        <img
                            :src="user.profile_photo_url"
                            :alt="user.name"
                            class="h-12 w-12 rounded-2xl object-cover shadow-sm ring-2 ring-white dark:ring-slate-700"
                        >
                        <div class="flex-1 min-w-0">
                            <p class="truncate font-semibold text-slate-800 dark:text-slate-200">{{ user.name }}</p>
                            <p class="truncate text-sm text-slate-500 dark:text-slate-400">{{ user.email }}</p>
                        </div>
                        <span :class="[
                            'shrink-0 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold',
                            user.role === 'admin'
                                ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300'
                                : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300'
                        ]">
                            <span :class="['h-1.5 w-1.5 rounded-full', user.role === 'admin' ? 'bg-indigo-500' : 'bg-emerald-500']"></span>
                            {{ user.role === 'admin' ? 'Admin' : 'Usuario' }}
                        </span>
                    </div>

                    <!-- Stats -->
                    <div class="mt-4 flex items-center gap-2 rounded-2xl border border-slate-100 bg-slate-50/80 px-4 py-2.5 dark:border-slate-700/50 dark:bg-slate-700/30">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-slate-400">
                            <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-xs text-slate-500 dark:text-slate-400">Reservas:</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ user.reservations_count }}</span>
                    </div>

                    <!-- Acciones -->
                    <div class="mt-4 grid grid-cols-3 gap-2">
                        <Link
                            :href="route('users.edit', user.id)"
                            class="flex items-center justify-center rounded-xl border border-slate-200 bg-white py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-700 dark:text-slate-300"
                        >
                            Editar
                        </Link>
                        <button
                            @click="toggleRole(user)"
                            class="rounded-xl border border-amber-200 bg-amber-50 py-2 text-xs font-semibold text-amber-700 transition hover:bg-amber-100 dark:border-amber-800/50 dark:bg-amber-900/20 dark:text-amber-400"
                        >
                            {{ user.role === 'admin' ? '→ Usuario' : '→ Admin' }}
                        </button>
                        <button
                            @click="confirmDelete(user)"
                            :disabled="deleting === user.id"
                            class="rounded-xl border border-red-200 bg-red-50 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100 disabled:opacity-50 dark:border-red-800/50 dark:bg-red-900/20 dark:text-red-400"
                        >
                            {{ deleting === user.id ? '...' : 'Eliminar' }}
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>