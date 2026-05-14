<script setup>

import {
    Link
}
from '@inertiajs/vue3'

import {
    usePage
}
from '@inertiajs/vue3'

import {
    computed,
    ref
}
from 'vue'

import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    space: Object,
    date: String,
    slots: Array,
    nextSlots: Array
})

const page = usePage()

const selectedStart =
    ref('')

const selectedEnd =
    ref('')

const availableEndSlots =
    computed(() => {

        if (
            !selectedStart.value
        ) {

            return []
        }

        const startIndex =
            props.slots.findIndex(
                slot =>
                    slot.full_start
                    ===
                    selectedStart.value
            )

        if (
            startIndex === -1
        ) {

            return []
        }

        const validSlots = []

        for (
            let i =
            startIndex;
            i <
            props.slots.length;
            i++
        ) {

            const current =
                props.slots[i]

            const next =
                props.slots[
                    i + 1
                ]

            validSlots.push({

                label:
                    current.end,

                value:
                    current.full_end
            })

            if (
                next
                &&
                current.full_end
                !==
                next.full_start
            ) {

                break
            }
        }

        return validSlots
    })

defineOptions({
    layout: AppLayout
})

</script>

<template>

    <div class="p-6 max-w-6xl mx-auto">

        <!-- FLASH MESSAGE -->

        <div
            v-if="page.props.flash?.success"
            class="bg-green-100 text-green-700 p-4 rounded-xl mb-6"
        >
            {{ page.props.flash.success }}
        </div>

        <!-- SPACE INFO -->

        <div
            class="bg-white rounded-2xl shadow-sm p-6 mb-8"
        >

            <h1
                class="text-3xl font-bold mb-3"
            >
                {{ space.name }}
            </h1>

            <p
                class="text-gray-600 mb-6"
            >
                {{ space.description }}
            </p>

            <div
                v-if="space.usage_rules"
                class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-8"
            >

                <h2
                    class="text-lg font-semibold mb-2"
                >
                    Reglas de uso
                </h2>

                <p
                    class="text-gray-700 whitespace-pre-line"
                >
                    {{ space.usage_rules }}
                </p>

            </div>
            <div
                class="grid grid-cols-1 md:grid-cols-3 gap-4"
            >

                <div
                    class="bg-gray-50 rounded-xl p-4"
                >

                    <p
                        class="text-sm text-gray-500"
                    >
                        Tipo
                    </p>

                    <p
                        class="font-semibold"
                    >
                        {{ space.type }}
                    </p>

                </div>

                <div
                    class="bg-gray-50 rounded-xl p-4"
                >

                    <p
                        class="text-sm text-gray-500"
                    >
                        Capacidad
                    </p>

                    <p
                        class="font-semibold"
                    >
                        {{ space.capacity }}
                        personas
                    </p>

                </div>

                <div
                    class="bg-gray-50 rounded-xl p-4"
                >

                    <p
                        class="text-sm text-gray-500"
                    >
                        Precio
                    </p>

                    <p
                        class="font-semibold"
                    >
                        $

                        {{
                            space.price_per_hour
                        }}

                        / hora
                    </p>

                </div>

            </div>

        </div>

        <!-- DATE FILTER -->

        <div
            class="bg-white rounded-2xl shadow-sm p-6 mb-8"
        >

            <h2
                class="text-xl font-semibold mb-4"
            >
                Consultar disponibilidad
            </h2>

            <form
                method="GET"
                class="flex flex-col md:flex-row gap-4 items-start md:items-end"
            >

                <div>

                    <label
                        class="block text-sm text-gray-500 mb-1"
                    >
                        Fecha
                    </label>

                    <input
                        type="date"
                        name="date"
                        :value="date"
                        class="border rounded-lg p-2"
                    >

                </div>

                <button
                    class="bg-pink-500 text-white px-5 py-2 rounded-lg hover:bg-pink-600 transition"
                >
                    Buscar
                </button>

            </form>

        </div>

        <!-- AVAILABLE SLOTS -->

        <div>

            <h2
                class="text-2xl font-bold mb-5"
            >
                Horarios disponibles
            </h2>

            <div
                v-if="slots.length"
                class="bg-white rounded-2xl shadow-sm p-6"
            >

                <div
                    class="grid md:grid-cols-2 gap-5"
                >

                    <!-- HORA INICIO -->

                    <div>

                        <label
                            class="block text-sm text-gray-500 mb-2"
                        >
                            Hora inicio
                        </label>

                        <select
                            v-model="selectedStart"
                            class="w-full border rounded-xl p-3"
                        >

                            <option value="">
                                Selecciona una hora
                            </option>

                            <option
                                v-for="slot in slots"
                                :key="slot.full_start"
                                :value="slot.full_start"
                            >
                                {{ slot.start }}
                            </option>

                        </select>

                    </div>

                    <!-- HORA FIN -->

                    <div>

                        <label
                            class="block text-sm text-gray-500 mb-2"
                        >
                            Hora fin
                        </label>

                        <select
                            v-model="selectedEnd"
                            class="w-full border rounded-xl p-3"
                            :disabled="!selectedStart"
                        >

                            <option value="">
                                Selecciona hora final
                            </option>

                            <option
                                v-for="
                                    slot
                                    in
                                    availableEndSlots
                                "
                                :key="slot.value"
                                :value="slot.value"
                            >
                                {{ slot.label }}
                            </option>

                        </select>

                    </div>

                </div>

                <Link
                    v-if="
                        selectedStart
                        &&
                        selectedEnd
                    "
                    :href="`/reservations/create?space_id=${space.id}&start_time=${selectedStart}&end_time=${selectedEnd}`"
                    class="mt-6 inline-block bg-pink-500 text-white px-5 py-3 rounded-xl hover:bg-pink-600 transition"
                >
                    Continuar reserva
                </Link>

            </div>

            <div
                v-else
                class="bg-red-50 border border-red-200 p-5 rounded-xl text-red-700"
            >

                No hay horarios disponibles
                para esta fecha.

            </div>

        </div>

        <!-- NEXT AVAILABLE -->

        <div
            class="mt-10"
        >

            <h2
                class="text-2xl font-bold mb-5"
            >
                Próximos horarios disponibles
            </h2>

            <div
                v-if="nextSlots.length"
                class="space-y-5"
            >

                <div
                    v-for="day in nextSlots"
                    :key="day.date"
                    class="bg-white rounded-2xl shadow-sm p-5"
                >

                    <h3
                        class="font-semibold text-lg mb-4"
                    >
                        {{ day.date }}
                    </h3>

                    <div
                        class="flex flex-wrap gap-3"
                    >

                        <div
                            v-for="slot in day.slots"
                            :key="slot.full_start"
                            class="bg-pink-50 border border-pink-200 rounded-lg px-4 py-2"
                        >

                            {{ slot.start }}
                            -
                            {{ slot.end }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>