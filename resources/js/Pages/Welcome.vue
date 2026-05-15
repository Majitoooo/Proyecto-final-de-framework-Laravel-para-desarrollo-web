<script setup>
import {
    Head,
    Link
} from '@inertiajs/vue3'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean
})
</script>

<template>
    <Head title="CoWorkly" />

    <div class="min-h-screen bg-white">

        <!-- Navbar -->
        <header
            class="
                sticky top-0 z-50
                bg-white/80
                shadow-sm
                backdrop-blur-lg
                border-b border-slate-200
            "
        >
            <div
                class="
                    max-w-7xl mx-auto
                    px-6 py-5
                    flex justify-between items-center
                "
            >
                <div class="flex items-center gap-4">
                    <div
                        class="
                            h-12 w-12
                            rounded-2xl
                            bg-slate-900
                            flex items-center
                            justify-center
                            text-white
                            font-bold
                            text-lg
                        "
                    >
                        C
                    </div>

                    <div>
                        <h1
                            class="
                                text-xl
                                font-bold
                                text-slate-900
                            "
                        >
                            CoWorkly
                        </h1>

                        <p
                            class="
                                text-sm
                                text-slate-500
                            "
                        >
                            Espacios inteligentes
                        </p>
                    </div>
                </div>

                <nav
                    v-if="canLogin"
                    class="flex gap-3"
                >
                    <template
                        v-if="
                            $page.props.auth.user
                        "
                    >
                        <Link
                            :href="
                                route(
                                    'dashboard'
                                )
                            "
                            class="
                                px-5 py-3
                                rounded-2xl
                                bg-slate-900
                                hover:bg-slate-800
                                text-white
                                transition
                                font-medium
                            "
                        >
                            Dashboard
                        </Link>
                    </template>

                    <template
                        v-else
                    >
                        <Link
                            :href="
                                route(
                                    'login'
                                )
                            "
                            class="
                                px-5 py-3
                                rounded-2xl
                                border
                                border-slate-300
                                text-slate-700
                                hover:bg-slate-100
                                transition
                                font-medium
                            "
                        >
                            Iniciar sesión
                        </Link>

                        <Link
                            v-if="
                                canRegister
                            "
                            :href="
                                route(
                                    'register'
                                )
                            "
                            class="
                                px-5 py-3
                                rounded-2xl
                                bg-blue-900
                                hover:bg-black
                                text-white
                                transition
                                font-medium
                            "
                        >
                            Registrarse
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Hero -->
        <section
            class="
                max-w-7xl mx-auto
                px-6
                py-24
                grid lg:grid-cols-2
                gap-16
                items-center
            "
        >
            <div>
                <span
                    class="
                        inline-flex
                        items-center
                        px-4 py-2
                        rounded-full
                        bg-slate-100
                        text-slate-900
                        text-sm
                        font-medium
                        mb-8
                    "
                >
                    Espacios de coworking inteligentes
                </span>

                <h1
                    class="
                        text-5xl
                        lg:text-7xl
                        font-black
                        leading-tight
                        text-slate-900
                    "
                >
                    Reserva tu
                    <span
                        class="
                            text-slate-950
                        "
                    >
                        espacio ideal
                    </span>
                    para trabajar
                </h1>

                <p
                    class="
                        mt-8
                        text-xl
                        text-slate-600
                        leading-relaxed
                        max-w-xl
                    "
                >
                    Encuentra puestos,
                    salas de reunión
                    y espacios
                    colaborativos
                    disponibles en
                    segundos.
                    Organiza tu
                    tiempo de forma
                    simple y rápida.
                </p>

                <div
                    class="
                        flex gap-4
                        mt-10
                        flex-wrap
                    "
                >
                    <Link
                        :href="
                            route(
                                'calendar.public'
                            )
                        "
                        class="
                            px-8 py-4
                            rounded-2xl
                            bg-slate-900
                            hover:bg-black
                            text-white
                            font-semibold
                            transition
                            shadow-lg
                        "
                    >
                        Reservar espacio
                    </Link>

                    <Link
                        :href="
                            route(
                                'spaces.index'
                            )
                        "
                        class="
                            px-8 py-4
                            rounded-2xl
                            border border-slate-300
                            hover:bg-slate-100
                            text-slate-700
                            font-semibold
                            transition
                        "
                    >
                        Ver espacios
                    </Link>
                </div>
            </div>

            <!-- Hero cards -->
            <div class="relative">

                <div
                    class="
                        bg-white
                        rounded-[36px]
                        shadow-xl
                        border border-slate-200
                        p-8
                    "
                >
                    <div
                        class="
                            grid grid-cols-2
                            gap-5
                        "
                    >
                        <div
                            class="
                                bg-slate-100
                                rounded-3xl
                                p-6
                            "
                        >
                            <h3
                                class="
                                    text-3xl
                                    font-bold
                                    text-slate-900
                                "
                            >
                                Moderno
                            </h3>

                            <p
                                class="
                                    text-slate-500
                                    mt-2
                                "
                            >
                                Acceso flexible
                            </p>
                        </div>

                        <div
                            class="
                                bg-white
                                border border-slate-200
                                shadow-sm
                                rounded-3xl
                                p-6
                            "
                        >
                            <h3
                                class="
                                    text-3xl
                                    font-bold
                                    text-blue-900
                                "
                            >
                                WiFi
                            </h3>

                            <p
                                class="
                                    text-slate-500
                                    mt-2
                                "
                            >
                                Alta velocidad
                            </p>
                        </div>

                        <div
                            class="
                                bg-slate-100
                                rounded-3xl
                                p-6
                            "
                        >
                            <h3
                                class="
                                    text-3xl
                                    font-bold
                                    text-blue-900
                                "
                            >
                                ☕
                            </h3>

                            <p
                                class="
                                    text-slate-500
                                    mt-2
                                "
                            >
                                Zona café
                            </p>
                        </div>

                        <div
                            class="
                                bg-slate-100
                                rounded-3xl
                                p-6
                            "
                        >
                            <h3
                                class="
                                    text-3xl
                                    font-bold
                                    text-blue-900
                                "
                            >
                                Salas
                            </h3>

                            <p
                                class="
                                    text-slate-500
                                    mt-2
                                "
                            >
                                Reuniones
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Beneficios -->
        <section
            class="
                max-w-7xl mx-auto
                px-6
                pb-24
            "
        >
            <div
                class="
                    text-center
                    mb-14
                "
            >
                <h2
                    class="
                        text-4xl
                        font-bold
                        text-slate-900
                    "
                >
                    ¿Por qué elegirnos?
                </h2>

                <p
                    class="
                        text-slate-500
                        mt-3
                        text-lg
                    "
                >
                    Diseñado para
                    freelancers,
                    startups
                    y equipos.
                </p>
            </div>

            <div
                class="
                    grid
                    md:grid-cols-3
                    gap-6
                "
            >
                <div
                    class="
                        bg-white
                        border border-slate-200
                        rounded-[30px]
                        p-8
                        shadow-sm
                        hover:shadow-md
                        transition
                    "
                >
                    <h3
                        class="
                            text-xl
                            font-bold
                            text-slate-900
                            mb-4
                        "
                    >
                        Reserva rápida
                    </h3>

                    <p
                        class="
                            text-slate-500
                        "
                    >
                        Agenda espacios
                        disponibles
                        en pocos clics.
                    </p>
                </div>

                <div
                    class="
                        bg-white
                        border border-slate-200
                        rounded-[30px]
                        p-8
                        shadow-sm
                        hover:shadow-md
                        transition
                    "
                >
                    <h3
                        class="
                            text-xl
                            font-bold
                            text-slate-900
                            mb-4
                        "
                    >
                        Espacios modernos
                    </h3>

                    <p
                        class="
                            text-slate-500
                        "
                    >
                        Diseñados para
                        creatividad,
                        enfoque
                        y colaboración.
                    </p>
                </div>

                <div
                    class="
                        bg-white
                        border border-slate-200
                        rounded-[30px]
                        p-8
                        shadow-sm
                        hover:shadow-md
                        transition
                    "
                >
                    <h3
                        class="
                            text-xl
                            font-bold
                            text-slate-900
                            mb-4
                        "
                    >
                        Flexibilidad total
                    </h3>

                    <p
                        class="
                            text-slate-500
                        "
                    >
                        Reserva por horas
                        según tus
                        necesidades.
                    </p>
                </div>
            </div>
        </section>
    </div>
</template>