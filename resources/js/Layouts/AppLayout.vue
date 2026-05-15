<script setup>
import { ref, onMounted } from 'vue'

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

/*
|--------------------------------------------------------------------------
| DARK MODE
|--------------------------------------------------------------------------
*/

const darkMode = ref(false)

onMounted(() => {
    const savedTheme = localStorage.getItem('theme')

    if (
        savedTheme === 'dark'
        ||
        (
            !savedTheme
            &&
            window.matchMedia('(prefers-color-scheme: dark)').matches
        )
    ) {
        darkMode.value = true
        document.documentElement.classList.add('dark')
    }
})

const toggleDarkMode = () => {
    darkMode.value = !darkMode.value

    if (darkMode.value) {
        document.documentElement.classList.add('dark')
        localStorage.setItem('theme', 'dark')
    } else {
        document.documentElement.classList.remove('dark')
        localStorage.setItem('theme', 'light')
    }
}
</script>

<template>
    <div
        class="
            min-h-screen
            bg-slate-50
            text-slate-900
            transition-colors
            duration-300
            dark:bg-[#0B1120]
            dark:text-slate-100
        "
    >
        <Head :title="title" />

        <Banner />

        <!-- BACKGROUND -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div
                class="
                    absolute
                    left-[-120px]
                    top-[-120px]
                    h-[420px]
                    w-[420px]
                    rounded-full
                    bg-sky-100/50
                    blur-[120px]
                    dark:bg-sky-900/10
                "
            />

            <div
                class="
                    absolute
                    bottom-[-140px]
                    right-[-120px]
                    h-[420px]
                    w-[420px]
                    rounded-full
                    bg-slate-200/40
                    blur-[120px]
                    dark:bg-slate-700/10
                "
            />
        </div>

        <div class="relative">
            <!-- NAVBAR -->
            <nav class="sticky top-0 z-50">
                <div class="mx-auto max-w-7xl px-4 pt-5 sm:px-6 lg:px-8">
                    <div
                        class="
                            rounded-[32px]
                            border
                            border-slate-200/70
                            bg-white/90
                            shadow-[0_12px_40px_rgba(15,23,42,0.06)]
                            backdrop-blur-md
                            dark:border-slate-800
                            dark:bg-slate-900/85
                        "
                    >
                        <div
                            class="
                                flex
                                h-[84px]
                                items-center
                                justify-between
                                px-6
                            "
                        >
                            <!-- LEFT -->
                            <div class="flex items-center gap-10">
                                <!-- LOGO -->
                                <Link
                                    :href="
                                        $page.props.auth.user
                                            ? (
                                                $page.props.auth.user.role === 'admin'
                                                    ? route('admin.dashboard')
                                                    : route('spaces.index')
                                            )
                                            : route('spaces.index')
                                    "
                                    class="flex items-center gap-4"
                                >
                                    <div
                                        class="
                                            flex
                                            h-11
                                            w-11
                                            items-center
                                            justify-center
                                            rounded-2xl
                                            bg-slate-900
                                            shadow-md
                                            dark:bg-slate-800
                                        "
                                    >
                                        <ApplicationMark
                                            class="h-6 w-auto text-white"
                                        />
                                    </div>

                                    <div class="hidden sm:block">
                                        <h1
                                            class="
                                                text-sm
                                                font-bold
                                                tracking-tight
                                            "
                                        >
                                            Coworking Reserve
                                        </h1>

                                        <p
                                            class="
                                                text-xs
                                                text-slate-500
                                                dark:text-slate-400
                                            "
                                        >
                                            Workspace management
                                        </p>
                                    </div>
                                </Link>

                                <!-- NAVIGATION -->
                                <div class="hidden lg:flex items-center gap-2">
                                    <!-- CLIENT -->
                                    <template
                                        v-if="
                                            !$page.props.auth.user
                                            ||
                                            $page.props.auth.user.role !== 'admin'
                                        "
                                    >
                                        <NavLink
                                            :href="route('spaces.index')"
                                            :active="route().current('spaces.*')"
                                        >
                                            Espacios
                                        </NavLink>

                                        <NavLink
                                            :href="
                                                route(
                                                    'my.reservations'
                                                )
                                            "
                                        >
                                            Mis reservas
                                        </NavLink>

                                        <NavLink
                                            :href="
                                                route(
                                                    'calendar.public'
                                                )
                                            "
                                        >
                                            Disponibilidad
                                        </NavLink>
                                        
                                    </template>

                                    <!-- ADMIN -->
                                    <template
                                        v-if="$page.props.auth.user?.role === 'admin'"
                                    >
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
                                            Espacios
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
                                            :href="route('users.index')"
                                            :active="route().current('users.*')"
                                        >
                                            Usuarios
                                        </NavLink>

                                        <NavLink
                                            :href="route('spaces.create')"
                                            :active="route().current('spaces.create')"
                                        >
                                            Crear
                                        </NavLink>
                                    </template>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="hidden sm:flex items-center gap-4">
                                <!-- THEME -->
                                <button
                                    @click="toggleDarkMode"
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        items-center
                                        justify-center
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-white
                                        text-slate-700
                                        transition-all
                                        duration-200
                                        hover:border-sky-200
                                        hover:bg-slate-100
                                        dark:border-slate-700
                                        dark:bg-slate-900
                                        dark:text-slate-200
                                        dark:hover:border-sky-700
                                        dark:hover:bg-slate-800
                                    "
                                >
                                    <span v-if="darkMode">☀️</span>
                                    <span v-else>🌙</span>
                                </button>

                                <!-- USER -->
                                <div
                                    v-if="$page.props.auth.user"
                                    class="relative"
                                >
                                    <Dropdown
                                        align="right"
                                        width="56"
                                    >
                                        <template #trigger>
                                            <button
                                                type="button"
                                                class="
                                                    flex
                                                    items-center
                                                    gap-3
                                                    rounded-2xl
                                                    border
                                                    border-slate-200
                                                    bg-white
                                                    px-3
                                                    py-2
                                                    shadow-sm
                                                    transition
                                                    duration-300
                                                    hover:border-slate-300
                                                    hover:shadow-md
                                                    dark:border-slate-700
                                                    dark:bg-slate-900
                                                "
                                            >
                                                <div
                                                    class="
                                                        flex
                                                        h-10
                                                        w-10
                                                        items-center
                                                        justify-center
                                                        rounded-full
                                                        bg-slate-900
                                                        text-sm
                                                        font-bold
                                                        text-white
                                                        dark:bg-slate-800
                                                    "
                                                >
                                                    {{
                                                        $page.props.auth.user.name?.charAt(0)
                                                    }}
                                                </div>

                                                <div class="hidden md:block text-left">
                                                    <div
                                                        class="
                                                            max-w-[140px]
                                                            truncate
                                                            text-sm
                                                            font-semibold
                                                        "
                                                    >
                                                        {{
                                                            $page.props.auth.user.name
                                                        }}
                                                    </div>

                                                    <div
                                                        class="
                                                            text-xs
                                                            text-slate-500
                                                            dark:text-slate-400
                                                        "
                                                    >
                                                        {{
                                                            $page.props.auth.user.role === 'admin'
                                                                ? 'Administrador'
                                                                : 'Usuario'
                                                        }}
                                                    </div>
                                                </div>
                                            </button>
                                        </template>

                                        <template #content>
                                            <div
                                                class="
                                                    border-b
                                                    border-slate-200
                                                    px-4
                                                    py-3
                                                    dark:border-slate-700
                                                "
                                            >
                                                <div
                                                    class="
                                                        text-xs
                                                        uppercase
                                                        tracking-[0.18em]
                                                        text-slate-400
                                                    "
                                                >
                                                    Mi cuenta
                                                </div>

                                                <div class="mt-1 font-semibold">
                                                    {{
                                                        $page.props.auth.user.name
                                                    }}
                                                </div>
                                            </div>

                                            <DropdownLink
                                                :href="route('profile.show')"
                                            >
                                                Perfil
                                            </DropdownLink>

                                            <form @submit.prevent="logout">
                                                <DropdownLink as="button">
                                                    Cerrar sesión
                                                </DropdownLink>
                                            </form>
                                        </template>
                                    </Dropdown>
                                </div>

                                <!-- GUEST -->
                                <div
                                    v-else
                                    class="flex items-center gap-3"
                                >
                                    <Link
                                        :href="route('login')"
                                        class="
                                            rounded-xl
                                            px-4
                                            py-2
                                            text-sm
                                            font-semibold
                                            transition
                                            hover:bg-slate-100
                                            dark:hover:bg-slate-800
                                        "
                                    >
                                        Iniciar sesión
                                    </Link>

                                    <Link
                                        :href="route('register')"
                                        class="
                                            rounded-xl
                                            bg-sky-600
                                            px-5
                                            py-2.5
                                            text-sm
                                            font-semibold
                                            text-white
                                            transition
                                            hover:bg-sky-700
                                            hover:translate-y-[-1px]
                                            dark:bg-sky-600
                                        "
                                    >
                                        Registrarse
                                    </Link>
                                </div>
                            </div>

                            <!-- MOBILE -->
                            <div class="flex items-center gap-2 sm:hidden">
                                <button
                                    @click="toggleDarkMode"
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        items-center
                                        justify-center
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-white
                                        dark:border-slate-700
                                        dark:bg-slate-900
                                    "
                                >
                                    <span v-if="darkMode">☀️</span>
                                    <span v-else>🌙</span>
                                </button>

                                <button
                                    class="
                                        flex
                                        h-11
                                        w-11
                                        items-center
                                        justify-center
                                        rounded-xl
                                        border
                                        border-slate-200
                                        bg-white
                                        dark:border-slate-700
                                        dark:bg-slate-900
                                    "
                                    @click="
                                        showingNavigationDropdown =
                                        !showingNavigationDropdown
                                    "
                                >
                                    ☰
                                </button>
                            </div>
                        </div>

                        <!-- MOBILE MENU -->
                        <div
                            v-show="showingNavigationDropdown"
                            class="
                                border-t
                                border-slate-200
                                px-4
                                pb-4
                                pt-3
                                dark:border-slate-800
                            "
                        >
                            <div class="space-y-2">
                                <ResponsiveNavLink
                                    :href="route('admin.dashboard')"
                                >
                                    Dashboard
                                </ResponsiveNavLink>

                                <ResponsiveNavLink
                                    :href="route('admin.spaces.index')"
                                >
                                    Espacios
                                </ResponsiveNavLink>

                                <ResponsiveNavLink
                                    :href="route('reservations.index')"
                                >
                                    Reservas
                                </ResponsiveNavLink>

                                 <ResponsiveNavLink
                                     :href="route('admin.calendar.show')"
                                 >
                                     Calendario
                                 </ResponsiveNavLink>

                                 <ResponsiveNavLink
                                     :href="route('users.index')"
                                 >
                                     Usuarios
                                 </ResponsiveNavLink>
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