<script setup>
import {
    Link,
    router,
    usePage
} from '@inertiajs/vue3'

import AppLayout from '@/Layouts/AppLayout.vue'

const props =
    defineProps({

        space: Object,

        blockedSlots: {
            type: Array,
            default: () => []
        }
    })

const page =
    usePage()

const removeBlock =
(
    id
) => {

    if (
        confirm(
            '¿Eliminar este bloqueo?'
        )
    ) {

        router.delete(
            `/admin/blocked-slots/${id}`
        )
    }
}

const formatDateTime =
(
    datetime
) => {

    return new Date(
        datetime
    ).toLocaleString(
        'es-CO',
        {

            timeZone:
                'America/Bogota',

            dateStyle:
                'short',

            timeStyle:
                'short'
        }
    )
}

defineOptions({
    layout:
        AppLayout
})
</script>

<template>
    <div
        class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
    >
        <div
            class="relative overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl"
        >
            <!-- Fondo decorativo -->
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -top-20 left-0 h-56 w-56 rounded-full bg-indigo-100/70 blur-3xl"></div>
                <div class="absolute right-0 top-0 h-48 w-48 rounded-full bg-fuchsia-100/70 blur-3xl"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.72),rgba(248,250,252,0.94))]"></div>
            </div>

            <div class="relative p-6 sm:p-8">
                <!-- HEADER -->
                <div
                    class="mb-8 flex flex-col gap-5 border-b border-slate-200/70 pb-6 lg:flex-row lg:items-start lg:justify-between"
                >
                    <div>
                        <div class="mb-3 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Gestión de bloqueos
                        </div>

                        <h1
                            class="text-3xl font-semibold tracking-[-0.03em] text-slate-900"
                        >
                            Bloqueos
                        </h1>

                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 text-sm font-bold text-white shadow-md">
                                {{ space.name?.charAt(0) }}
                            </div>

                            <div>
                                <p
                                    class="text-base font-semibold text-slate-900"
                                >
                                    {{ space.name }}
                                </p>

                                <p
                                    class="text-sm text-slate-500"
                                >
                                    Gestión de franjas no disponibles para este espacio
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col gap-3 sm:flex-row"
                    >
                        <Link
                            :href="`/admin/spaces/${space.slug}/blocked-slots/create`"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-[0_12px_30px_rgba(15,23,42,0.18)] transition duration-300 hover:translate-y-[-1px] hover:bg-slate-800"
                        >
                            Nuevo bloqueo
                        </Link>

                        <Link
                            href="/admin/spaces"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-[0_8px_24px_rgba(15,23,42,0.05)] transition duration-300 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                        >
                            Volver
                        </Link>
                    </div>
                </div>

                <!-- ALERTA -->
                <div
                    v-if="
                        page.props.flash
                        &&
                        page.props.flash.success
                    "
                    class="mb-6 rounded-[24px] border border-emerald-200 bg-emerald-50/90 p-4 text-emerald-800 shadow-sm"
                >
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-white shadow-sm">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="h-5 w-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4.5 12.75l6 6 9-13.5"
                                />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-semibold">
                                Operación completada
                            </p>
                            <p class="mt-1 text-sm">
                                {{
                                    page.props.flash.success
                                }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- TABLA / CARD -->
                <div
                    class="overflow-hidden rounded-[28px] border border-slate-200/80 bg-white/90 shadow-[0_10px_30px_rgba(15,23,42,0.05)]"
                >
                    <div class="border-b border-slate-200/70 bg-slate-50/80 px-6 py-4">
                        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">
                                    Horarios bloqueados
                                </h2>
                                <p class="text-sm text-slate-500">
                                    Consulta y elimina bloqueos creados manualmente
                                </p>
                            </div>

                            <div class="text-sm font-medium text-slate-500">
                                Total: {{ blockedSlots.length }}
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full"
                        >
                            <thead
                                class="bg-slate-50/70"
                            >
                                <tr class="border-b border-slate-200/70">
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        Inicio
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        Fin
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        Motivo
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">
                                        Acción
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200/70">
                                <tr
                                    v-if="!blockedSlots.length"
                                >
                                    <td
                                        colspan="4"
                                        class="px-6 py-14 text-center"
                                    >
                                        <div class="mx-auto flex max-w-md flex-col items-center">
                                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.8"
                                                    stroke="currentColor"
                                                    class="h-6 w-6"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M8.25 6.75h12m-12 5.25h12m-12 5.25h12M3.75 6.75h.008v.008H3.75V6.75Zm0 5.25h.008v.008H3.75V12Zm0 5.25h.008v.008H3.75v-.008Z"
                                                    />
                                                </svg>
                                            </div>

                                            <p class="text-base font-semibold text-slate-800">
                                                No hay horarios bloqueados
                                            </p>

                                            <p class="mt-1 text-sm text-slate-500">
                                                Cuando añadas un bloqueo, aparecerá aquí con su intervalo y motivo.
                                            </p>
                                        </div>
                                    </td>
                                </tr>

                                <tr
                                    v-for="
                                        blockedSlot
                                        in blockedSlots
                                    "
                                    :key="
                                        blockedSlot.id
                                    "
                                    class="transition duration-200 hover:bg-slate-50/80"
                                >
                                    <td class="px-6 py-5 align-top">
                                        <div class="inline-flex rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700">
                                            {{
                                                formatDateTime(
                                                    blockedSlot.start_time
                                                )
                                            }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 align-top">
                                        <div class="inline-flex rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700">
                                            {{
                                                formatDateTime(
                                                    blockedSlot.end_time
                                                )
                                            }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 align-top">
                                        <div class="max-w-xl text-sm leading-6 text-slate-600">
                                            {{
                                                blockedSlot.reason
                                                ||
                                                'Sin motivo'
                                            }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-5 align-top">
                                        <button
                                            @click="
                                                removeBlock(
                                                    blockedSlot.id
                                                )
                                            "
                                            class="inline-flex items-center rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 transition duration-300 hover:border-red-300 hover:bg-red-100 hover:text-red-700"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
