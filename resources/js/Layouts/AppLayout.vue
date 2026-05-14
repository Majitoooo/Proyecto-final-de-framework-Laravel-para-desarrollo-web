<script setup>
import { ref } from 'vue'

import {
    Head,
    Link,
    router
} from '@inertiajs/vue3'

import ApplicationMark from '@/Components/ApplicationMark.vue'
import Banner from '@/Components/Banner.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'

defineProps({
    title: String,
})

const showingNavigationDropdown = ref(false)

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-[#f6f7fb] text-slate-900">
        <Head :title="title" />

        <Banner />

        <!-- Background luxury SaaS -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,0.10),transparent_28%),radial-gradient(circle_at_top_right,rgba(168,85,247,0.10),transparent_24%),radial-gradient(circle_at_bottom,rgba(14,165,233,0.08),transparent_30%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_bottom,rgba(255,255,255,0.82),rgba(246,247,251,0.96))]"></div>
            <div class="absolute -top-24 left-[-80px] h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl"></div>
            <div class="absolute top-10 right-[-60px] h-72 w-72 rounded-full bg-fuchsia-200/35 blur-3xl"></div>
            <div class="absolute bottom-[-100px] left-1/3 h-80 w-80 rounded-full bg-sky-200/30 blur-3xl"></div>
        </div>

        <div class="relative min-h-screen">
            <!-- NAVBAR -->
            <nav class="sticky top-0 z-50">
                <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                    <div class="rounded-[24px] border border-white/70 bg-white/72 shadow-[0_10px_40px_rgba(15,23,42,0.08)] backdrop-blur-xl">
                        <div class="flex h-20 items-center justify-between px-4 sm:px-6">
                            <!-- LEFT -->
                            <div class="flex items-center gap-4 lg:gap-8">
                                <!-- LOGO -->
                                <div class="shrink-0 flex items-center">
                                    <Link
                                        :href="route('spaces.index')"
                                        class="group flex items-center gap-3"
                                    >
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200/80 bg-white shadow-[0_6px_24px_rgba(15,23,42,0.08)] transition duration-300 group-hover:scale-[1.03] group-hover:shadow-[0_10px_30px_rgba(15,23,42,0.12)]">
                                            <ApplicationMark class="block h-7 w-auto" />
                                        </div>

                                        <div class="hidden sm:block">
                                            <div class="text-sm font-semibold tracking-[-0.02em] text-slate-900">
                                                Coworking Reserve
                                            </div>
                                            <div class="text-xs font-medium text-slate-500">
                                                Workspace booking platform
                                            </div>
                                        </div>
                                    </Link>
                                </div>

                                <!-- NAVIGATION -->
                                <div class="hidden sm:flex sm:items-center">
                                    <!-- CLIENTE / INVITADO -->
                                    <template
                                        v-if="
                                            !$page.props.auth.user
                                            ||
                                            $page.props.auth.user.role !== 'admin'
                                        "
                                    >
                                        <div class="rounded-full border border-slate-200/80 bg-slate-50/80 px-2 py-1 shadow-inner">
                                            <NavLink
                                                :href="route('spaces.index')"
                                                :active="route().current('spaces.*')"
                                            >
                                                Espacios
                                            </NavLink>
                                        </div>
                                    </template>

                                    <!-- ADMIN -->
                                    <template v-if="$page.props.auth.user?.role === 'admin'">
                                        <div class="flex items-center gap-1 rounded-full border border-slate-200/80 bg-slate-50/80 px-2 py-1 shadow-inner">
                                            <NavLink
                                                :href="route('admin.dashboard')"
                                                :active="route().current('admin.dashboard')"
                                            >
                                                Dashboard
                                            </NavLink>

                                            <NavLink
                                                :href="route('admin.spaces.index')"
                                                :active="route().current('admin.spaces.*')"
                                            >
                                                Administrar Espacios
                                            </NavLink>

                                            <NavLink
                                                :href="route('reservations.index')"
                                                :active="route().current('reservations.*')"
                                            >
                                                Reservas
                                            </NavLink>

                                            <NavLink
                                                :href="route('admin.calendar.show')"
                                                :active="route().current('admin.calendar.*')"
                                            >
                                                Calendario
                                            </NavLink>

                                            <NavLink
                                                :href="route('spaces.create')"
                                                :active="route().current('spaces.create')"
                                            >
                                                Crear Espacio
                                            </NavLink>

                                            <NavLink
                                                :href="route('spaces.index')"
                                                :active="route().current('spaces.show')"
                                            >
                                                Ver Sitio
                                            </NavLink>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <!-- USER -->
                                <div
                                    v-if="$page.props.auth.user"
                                    class="ms-3 relative"
                                >
                                    <Dropdown
                                        align="right"
                                        width="48"
                                    >
                                        <template #trigger>
                                            <span class="inline-flex rounded-2xl">
                                                <button
                                                    type="button"
                                                    class="group inline-flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 shadow-[0_6px_24px_rgba(15,23,42,0.06)] transition duration-300 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:outline-none"
                                                >
                                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 text-xs font-semibold text-white shadow-md">
                                                        {{ $page.props.auth.user.name?.charAt(0) }}
                                                    </span>

                                                    <span class="max-w-[140px] truncate font-semibold">
                                                        {{ $page.props.auth.user.name }}
                                                    </span>

                                                    <svg
                                                        class="size-4 text-slate-400 transition duration-300 group-hover:text-slate-600"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                        />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <div class="px-4 py-3">
                                                <div class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                    Mi cuenta
                                                </div>
                                                <div class="mt-1 text-sm font-semibold text-slate-800">
                                                    {{ $page.props.auth.user.name }}
                                                </div>
                                            </div>

                                            <DropdownLink :href="route('profile.show')">
                                                Perfil
                                            </DropdownLink>

                                            <div class="border-t border-gray-200 dark:border-gray-600" />

                                            <form @submit.prevent="logout">
                                                <DropdownLink as="button">
                                                    Cerrar sesión
                                                </DropdownLink>
                                            </form>
                                        </template>
                                    </Dropdown>
                                </div>

                                <!-- INVITADO -->
                                <div
                                    v-else
                                    class="flex items-center gap-3"
                                >
                                    <Link
                                        :href="route('login')"
                                        class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 transition duration-300 hover:bg-white hover:text-slate-900"
                                    >
                                        Iniciar sesión
                                    </Link>

                                    <Link
                                        :href="route('register')"
                                        class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-[0_10px_30px_rgba(15,23,42,0.18)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800"
                                    >
                                        Registrarse
                                    </Link>
                                </div>
                            </div>

                            <!-- MOBILE BUTTON -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-[0_6px_24px_rgba(15,23,42,0.06)] transition duration-300 hover:bg-slate-50 hover:text-slate-900"
                                    @click="
                                        showingNavigationDropdown =
                                        !showingNavigationDropdown
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                        class="size-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- MOBILE MENU -->
                        <div
                            :class="{
                                block: showingNavigationDropdown,
                                hidden: !showingNavigationDropdown
                            }"
                            class="border-t border-slate-200/80 px-4 pb-4 pt-3 sm:hidden"
                        >
                            <div class="space-y-2 rounded-2xl border border-slate-200 bg-slate-50/80 p-3">
                                <template v-if="$page.props.auth.user?.role === 'admin'">
                                    <ResponsiveNavLink :href="route('admin.dashboard')">
                                        Dashboard
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink :href="route('spaces.index')">
                                        Espacios
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink :href="route('reservations.index')">
                                        Reservas
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink :href="route('admin.calendar.show')">
                                        Calendario
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink :href="route('spaces.create')">
                                        Crear Espacio
                                    </ResponsiveNavLink>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- CONTENT -->
            <main class="relative py-8 sm:py-10">
                <slot />
            </main>
        </div>
    </div>
</template>
