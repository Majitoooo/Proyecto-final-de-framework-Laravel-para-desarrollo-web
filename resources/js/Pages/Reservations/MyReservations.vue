<script setup>

import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
    reservations: Array
})

const statusConfig = (status) => {

    return {

        pending: {
            label: 'Pendiente',
            class:
                'bg-amber-100 text-amber-700 border border-amber-200',
            icon:
                '⏳'
        },

        confirmed: {
            label: 'Confirmada',
            class:
                'bg-emerald-100 text-emerald-700 border border-emerald-200',
            icon:
                '✅'
        },

        rejected: {
            label: 'Rechazada',
            class:
                'bg-red-100 text-red-700 border border-red-200',
            icon:
                '❌'
        },

        cancelled: {
            label: 'Cancelada',
            class:
                'bg-slate-200 text-slate-700 border border-slate-300',
            icon:
                '🚫'
        },

        finished: {
            label: 'Finalizada',
            class:
                'bg-sky-100 text-sky-700 border border-sky-200',
            icon:
                '✨'
        }

    }[status]
}

const formatDate = (date) => {

    return new Date(date)
        .toLocaleString(
            'es-CO',
            {
                dateStyle: 'full',
                timeStyle: 'short'
            }
        )
}

defineOptions({
    layout: AppLayout
})

</script>

<template>

    <div class="max-w-7xl mx-auto px-6 py-10">

        <!-- HEADER -->

        <div class="mb-10">

            <h1
                class="
                    text-5xl
                    font-black
                    text-slate-900
                "
            >
                Mis reservas ✨
            </h1>

            <p
                class="
                    mt-3
                    text-slate-500
                    text-lg
                "
            >
                Consulta el estado de tus espacios reservados.
            </p>

        </div>

        <!-- LISTA -->

        <div
            v-if="reservations.length"
            class="
                grid
                grid-cols-1
                lg:grid-cols-2
                gap-6
            "
        >

            <div
                v-for="
                    reservation
                    in reservations
                "
                :key="
                    reservation.id
                "
                class="
                    relative
                    overflow-hidden
                    rounded-[36px]
                    border
                    border-white/70
                    bg-white/90
                    p-7
                    shadow-[0_20px_60px_rgba(15,23,42,0.08)]
                    backdrop-blur-xl
                    transition
                    duration-300
                    hover:-translate-y-1
                    hover:shadow-[0_25px_80px_rgba(236,72,153,0.12)]
                "
            >

                <!-- Glow -->

                <div
                    class="
                        absolute
                        top-0
                        right-0
                        h-40
                        w-40
                        rounded-full
                        bg-pink-200/30
                        blur-3xl
                    "
                />

                <div
                    class="
                        relative
                        flex
                        justify-between
                        items-start
                        gap-4
                    "
                >

                    <div>

                        <h2
                            class="
                                text-2xl
                                font-bold
                                text-slate-900
                            "
                        >
                            {{
                                reservation
                                    .space?.name
                            }}
                        </h2>

                        <p
                            class="
                                text-slate-500
                                mt-1
                            "
                        >
                            Reserva #{{ reservation.id }}
                        </p>

                    </div>

                    <span
                        class="
                            px-4
                            py-2
                            rounded-full
                            text-sm
                            font-semibold
                            flex
                            items-center
                            gap-2
                        "
                        :class="
                            statusConfig(
                                reservation.status
                            ).class
                        "
                    >
                        {{
                            statusConfig(
                                reservation.status
                            ).icon
                        }}

                        {{
                            statusConfig(
                                reservation.status
                            ).label
                        }}
                    </span>

                </div>

                <!-- INFO -->

                <div
                    class="
                        mt-8
                        space-y-5
                    "
                >

                    <div
                        class="
                            rounded-3xl
                            bg-slate-50
                            p-5
                        "
                    >

                        <p
                            class="
                                text-sm
                                text-slate-400
                                mb-1
                            "
                        >
                            Inicio
                        </p>

                        <p
                            class="
                                font-semibold
                                text-slate-800
                            "
                        >
                            {{
                                formatDate(
                                    reservation.start_time
                                )
                            }}
                        </p>

                    </div>

                    <div
                        class="
                            rounded-3xl
                            bg-slate-50
                            p-5
                        "
                    >

                        <p
                            class="
                                text-sm
                                text-slate-400
                                mb-1
                            "
                        >
                            Finaliza
                        </p>

                        <p
                            class="
                                font-semibold
                                text-slate-800
                            "
                        >
                            {{
                                formatDate(
                                    reservation.end_time
                                )
                            }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- EMPTY -->

        <div
            v-else
            class="
                flex
                flex-col
                items-center
                justify-center
                rounded-[40px]
                bg-white/80
                border
                border-white/70
                py-24
                shadow-[0_20px_60px_rgba(15,23,42,0.06)]
            "
        >

            <div class="text-7xl mb-6">
                📅
            </div>

            <h2
                class="
                    text-3xl
                    font-bold
                    text-slate-800
                "
            >
                Aún no tienes reservas
            </h2>

            <p
                class="
                    text-slate-500
                    mt-3
                "
            >
                Cuando reserves un espacio,
                aparecerá aquí.
            </p>

        </div>

    </div>

</template>