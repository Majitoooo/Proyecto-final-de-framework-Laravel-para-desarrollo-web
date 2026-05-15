<script setup>

import { useForm, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    space: Object
})

const getAvailability = (day) => {

    const availability =
        props.space.availabilities?.find(
            item =>
                item.day_of_week === day
        )

    return {
        day_of_week: day,
        start_time:
            availability?.start_time?.slice(0, 5)
            ?? '',
        end_time:
            availability?.end_time?.slice(0, 5)
            ?? ''
    }
}

const form = useForm({

    name: props.space.name,
    type: props.space.type,
    capacity: props.space.capacity,

    description:
        props.space.description,

    usage_rules:
        props.space.usage_rules,

    price_per_hour:
        props.space.price_per_hour,

    is_active:
        props.space.is_active,

    image: null,

    availability: [

        getAvailability(1),
        getAvailability(2),
        getAvailability(3),
        getAvailability(4),
        getAvailability(5),
        getAvailability(6),
        getAvailability(0)

    ]
})

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

const submit = () => {

    form.put(
        `/admin/spaces/${props.space.slug}`
    )
}

defineOptions({
    layout: AppLayout
})

</script>

<template>

    <div class="p-6 max-w-3xl">

        <div
            class="flex justify-between items-center mb-6"
        >

            <h1
                class="text-2xl font-bold"
            >
                Editar Espacio
            </h1>

            <Link
                href="/admin/spaces"
                class="text-blue-500"
            >
                Volver
            </Link>

        </div>

        <form @submit.prevent="submit" enctype="multipart/form-data">

            <div class="mb-4">

                <label class="block mb-1">
                    Nombre
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    class="w-full border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Tipo
                </label>

                <input
                    v-model="form.type"
                    type="text"
                    class="w-full border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Capacidad
                </label>

                <input
                    v-model="form.capacity"
                    type="number"
                    class="w-full border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Descripción
                </label>

                <textarea
                    v-model="form.description"
                    class="w-full border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                ></textarea>

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Reglas de uso
                </label>

                <textarea
                    v-model="form.usage_rules"
                    class="w-full border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
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
                    class="w-full border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-1">
                    Imagen del espacio
                </label>

                <input
                    type="file"
                    class="w-full border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                    accept="image/*"
                    @change="(e) => form.image = e.target.files[0]"
                >

                <div
                    v-if="space.image"
                    class="mt-2"
                >
                    <p class="text-sm text-gray-600 mb-1 dark:text-slate-400">
                        Imagen actual:
                    </p>
                    <img
                        :src="`/storage/${space.image}`"
                        alt="Imagen actual"
                        class="w-48 h-32 object-cover rounded"
                    >
                </div>

            </div>

            <!-- DISPONIBILIDAD -->

            <div class="mb-6">

                <h2
                    class="text-lg font-semibold mb-4"
                >
                    Disponibilidad semanal
                </h2>

                <button
                    type="button"
                    @click="applyToAllDays"
                    class="mb-4 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600 dark:bg-pink-600 dark:hover:bg-pink-700"
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
                        class="border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                    >

                    <input
                        v-model="day.end_time"
                        type="time"
                        class="border rounded p-2 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-200"
                    >

                </div>

            </div>

            <div class="mb-6">

                <label
                    class="flex items-center gap-2"
                >

                    <input
                        v-model="form.is_active"
                        type="checkbox"
                    >

                    Activo

                </label>

            </div>

            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded dark:bg-blue-600 dark:hover:bg-blue-700"
            >
                Actualizar
            </button>

        </form>

    </div>

</template>