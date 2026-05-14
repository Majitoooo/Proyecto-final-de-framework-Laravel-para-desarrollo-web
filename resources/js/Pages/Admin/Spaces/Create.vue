<script setup>

import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const form = useForm({
    name: '',
    type: '',
    capacity: 1,
    description: '',
    price_per_hour: '',
    usage_rules: '',
    is_active: true,
    availability: [
    {
        day_of_week: 1,
        start_time: '',
        end_time: ''
    },
    {
        day_of_week: 2,
        start_time: '',
        end_time: ''
    },
    {
        day_of_week: 3,
        start_time: '',
        end_time: ''
    },
    {
        day_of_week: 4,
        start_time: '',
        end_time: ''
    },
    {
        day_of_week: 5,
        start_time: '',
        end_time: ''
    },
    {
        day_of_week: 6,
        start_time: '',
        end_time: ''
    },
    {
        day_of_week: 0,
        start_time: '',
        end_time: ''
    }
]
})

const submit = () => {
    form.post('/admin/spaces')
}

const applyToAllDays = () => {

    const monday =
        form.availability.find(
            day =>
                day.day_of_week === 1
        )

    if (
        !monday.start_time
        ||
        !monday.end_time
    ) {

        alert(
            'Primero llena el horario del lunes'
        )

        return
    }

    form.availability
        .forEach(day => {

            day.start_time =
                monday.start_time

            day.end_time =
                monday.end_time
        })
}

defineOptions({
    layout: AppLayout
})

</script>

<template>

    <div class="p-6 max-w-xl">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold">
                Crear Espacio
            </h1>

        </div>

        <form @submit.prevent="submit">

            <div class="mb-4">

                <label class="block mb-1">
                    Nombre
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    class="w-full border rounded p-2"
                >

                <div
                    v-if="form.errors.name"
                    class="text-red-500 text-sm mt-1"
                >
                    {{ form.errors.name }}
                </div>

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Tipo
                </label>

                <input
                    v-model="form.type"
                    type="text"
                    class="w-full border rounded p-2"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Capacidad
                </label>

                <input
                    v-model="form.capacity"
                    type="number"
                    class="w-full border rounded p-2"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Descripción
                </label>

                <textarea
                    v-model="form.description"
                    class="w-full border rounded p-2"
                ></textarea>

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Reglas de uso
                </label>

                <textarea
                    v-model="form.usage_rules"
                    class="w-full border rounded p-2"
                    rows="4"
                ></textarea>

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Precio por hora
                </label>

                <input
                    v-model="form.price_per_hour"
                    type="number"
                    class="w-full border rounded p-2"
                >

            </div>

            <div class="mb-6">

                <h2
                    class="text-lg font-semibold mb-4"
                >
                    Disponibilidad semanal
                </h2>

                <button
                    type="button"
                    @click="applyToAllDays"
                    class="mb-4 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600"
                >
                    Aplicar horario del lunes
                </button>

                <div
                    v-for="day in form.availability"
                    :key="day.day_of_week"
                    class="grid grid-cols-3 gap-4 mb-3 items-center"
                >

                    <span>

                        {{
                            [
                                'Domingo',
                                'Lunes',
                                'Martes',
                                'Miércoles',
                                'Jueves',
                                'Viernes',
                                'Sábado'
                            ][day.day_of_week]
                        }}

                    </span>

                    <input
                        v-model="day.start_time"
                        type="time"
                        class="border rounded p-2"
                    >

                    <input
                        v-model="day.end_time"
                        type="time"
                        class="border rounded p-2"
                    >

                </div>

            </div>

            <div class="mb-6">

                <label class="flex items-center gap-2">

                    <input
                        v-model="form.is_active"
                        type="checkbox"
                    >

                    Activo

                </label>

            </div>

            <button type="submit">
                GUARDAR
            </button>

        </form>

    </div>

</template>