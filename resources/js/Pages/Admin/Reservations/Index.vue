<script setup>

import { router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({

    reservations: {
        type: Array,
        default: () => []
    },

    spaces: {
        type: Array,
        default: () => []
    },

    filters: {
        type: Object,
        default: () => ({})
    }
})

const filters = reactive({

    status:
        props.filters.status || '',

    space_id:
        props.filters.space_id || '',

    date:
        props.filters.date || ''
})

const showReasonModal =
    ref(false)

const reasonType =
    ref('')

const selectedReservationId =
    ref(null)

const adminReason =
    ref('')

const confirmReservation = (id) => {
    router.patch(`/admin/reservations/${id}/confirm`)
}

const openReasonModal = (
    id,
    type
) => {

    selectedReservationId.value =
        id

    reasonType.value =
        type

    adminReason.value =
        ''

    showReasonModal.value =
        true
}

const submitReason = () => {

    if (
        !adminReason.value
            .trim()
    ) {

        return alert(
            'Debes escribir un motivo'
        )
    }

    const route =
        reasonType.value
        ===
        'reject'
            ? 'reject'
            : 'cancel'

    router.patch(

        `/admin/reservations/${selectedReservationId.value}/${route}`,

        {
            admin_reason:
                adminReason.value
        },

        {
            onSuccess:
                () => {

                showReasonModal.value =
                    false
            }
        }
    )
}

const finishReservation = (id) => {
    router.patch(`/admin/reservations/${id}/finish`)
}

const applyFilters = () => {

    router.get(
        '/admin/reservations',
        filters,
        {
            preserveState: true,
            replace: true
        }
    )
}

const formatDateTime = (
    datetime
) => {

    return new Date(
        datetime
    ).toLocaleString(
        'es-CO',
        {

            dateStyle: 'short',

            timeStyle: 'short'
        }
    )
}

defineOptions({
    layout: AppLayout
})

</script>

<template>

    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            Administración de Reservas
        </h1>

        <div
            class="bg-white p-5 rounded-xl shadow-sm mb-6 grid md:grid-cols-4 gap-4"
        >

            <!-- ESTADO -->

            <select
                v-model="filters.status"
                @change="applyFilters"
                class="border rounded-lg p-2"
            >

                <option value="">
                    Todos los estados
                </option>

                <option value="pending">
                    Pendiente
                </option>

                <option value="confirmed">
                    Confirmada
                </option>

                <option value="rejected">
                    Rechazada
                </option>

                <option value="cancelled">
                    Cancelada
                </option>

                <option value="finished">
                    Finalizada
                </option>

            </select>

            <!-- ESPACIO -->

            <select
                v-model="filters.space_id"
                @change="applyFilters"
                class="border rounded-lg p-2"
            >

                <option value="">
                    Todos los espacios
                </option>

                <option
                    v-for="space in spaces"
                    :key="space.id"
                    :value="space.id"
                >
                    {{ space.name }}
                </option>

            </select>

            <!-- FECHA -->

            <input
                v-model="filters.date"
                @change="applyFilters"
                type="date"
                class="border rounded-lg p-2"
            >

            <!-- LIMPIAR -->

            <button
                @click="
                    filters.status = '';
                    filters.space_id = '';
                    filters.date = '';
                    applyFilters()
                "
                class="bg-pink-500 text-white rounded-lg px-4"
            >
                Limpiar
            </button>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full border border-gray-300">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 border">
                            Espacio
                        </th>

                        <th class="p-3 border">
                            Usuario
                        </th>

                        <th class="p-3 border">
                            Inicio
                        </th>

                        <th class="p-3 border">
                            Fin
                        </th>

                        <th class="p-3 border">
                            Estado
                        </th>

                        <th class="p-3 border">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr
                        v-for="reservation in props.reservations"
                        :key="reservation.id"
                    >

                        <td class="p-3 border">
                            {{ reservation.space.name }}
                        </td>

                        <td class="p-3 border">
                            {{ reservation.user_name }}
                        </td>

                        <td class="p-3 border">
                            {{
                                formatDateTime(
                                    reservation.start_time
                                )
                            }}
                        </td>

                        <td class="p-3 border">
                            {{
                                formatDateTime(
                                    reservation.end_time
                                )
                            }}
                        </td>

                        <td class="p-3 border">
                            <span
                                class="px-3 py-1 rounded-full text-sm font-semibold"
                                :class="{

                                    'bg-yellow-100 text-yellow-700':
                                        reservation.status === 'pending',

                                    'bg-green-100 text-green-700':
                                        reservation.status === 'confirmed',

                                    'bg-red-100 text-red-700':
                                        reservation.status === 'rejected',

                                    'bg-gray-200 text-gray-700':
                                        reservation.status === 'cancelled',

                                    'bg-blue-100 text-blue-700':
                                        reservation.status === 'finished',
                                }"
                            >
                                {{
                                    {
                                        pending: 'Pendiente',
                                        confirmed: 'Confirmada',
                                        rejected: 'Rechazada',
                                        cancelled: 'Cancelada',
                                        finished: 'Finalizada'
                                    }[
                                        reservation.status
                                    ]
                                }}
                            </span>
                        </td>

                        <td class="p-3 border">

                        <div class="flex gap-2">

                            <button
                                v-if="reservation.status === 'pending'"
                                @click="confirmReservation(reservation.id)"
                                class="text-green-600"
                            >
                                Confirmar
                            </button>

                            <button
                                v-if="reservation.status === 'pending'"
                                @click="
                                    openReasonModal(
                                        reservation.id,
                                        'reject'
                                    )
                                "
                                class="text-red-600"
                            >
                                Rechazar
                            </button>

                            <button
                                v-if="reservation.status === 'confirmed'"
                                @click="
                                    openReasonModal(
                                        reservation.id,
                                        'cancel'
                                    )
                                "
                                class="text-orange-600"
                            >
                                Cancelar
                            </button>

                            <button
                                v-if="reservation.status === 'confirmed'"
                                @click="finishReservation(reservation.id)"
                                class="text-blue-600"
                            >
                                Finalizar
                            </button>

                        </div>

                    </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    <div
        v-if="showReasonModal"
        class="
            fixed inset-0
            bg-black/40
            flex items-center
            justify-center
            z-50
        "
    >

        <div
            class="
                bg-white
                rounded-3xl
                w-full
                max-w-lg
                p-8
                shadow-2xl
            "
        >

            <h2
                class="
                    text-2xl
                    font-bold
                    mb-4
                "
            >
                {{
                    reasonType === 'reject'
                        ? 'Rechazar reserva'
                        : 'Cancelar reserva'
                }}
            </h2>

            <p
                class="
                    text-gray-500
                    mb-5
                "
            >
                Escribe el motivo
                para informar
                al usuario.
            </p>

            <textarea
                v-model="
                    adminReason
                "
                rows="5"
                class="
                    w-full border
                    rounded-2xl
                    p-4
                "
                placeholder="
                    Ej:
                    El espacio
                    estará en
                    mantenimiento
                "
            />

            <div
                class="
                    flex justify-end
                    gap-3 mt-6
                "
            >

                <button
                    @click="
                        showReasonModal
                        =
                        false
                    "
                    class="
                        px-5 py-2
                        rounded-xl
                        border
                    "
                >
                    Cancelar
                </button>

                <button
                    @click="
                        submitReason
                    "
                    class="
                        px-5 py-2
                        rounded-xl
                        bg-pink-500
                        text-white
                    "
                >
                    Enviar
                </button>

            </div>

        </div>

    </div>
    
</template>