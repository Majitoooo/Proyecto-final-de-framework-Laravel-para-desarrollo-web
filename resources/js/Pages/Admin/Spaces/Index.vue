<script setup>

import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    spaces: {
        type: Array,
        default: () => []
    }
})

const toggleSpace = (slug) => {

    router.patch(`/admin/spaces/${slug}/toggle`)
}

defineOptions({
    layout: AppLayout
})

</script>

<template>

    <div class="p-6">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-bold">
                Administración de Espacios
            </h1>

            <Link
                href="/admin/spaces/create"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                Crear Espacio
            </Link>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full border border-gray-300">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-3 border">
                            Nombre
                        </th>

                        <th class="p-3 border">
                            Tipo
                        </th>

                        <th class="p-3 border">
                            Capacidad
                        </th>

                        <th class="p-3 border">
                            Precio
                        </th>

                        <th class="p-3 border">
                            Activo
                        </th>

                        <th class="p-3 border">
                            Acciones
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr
                        v-for="space in props.spaces"
                        :key="space.id"
                    >

                        <td class="p-3 border">
                            {{ space.name }}
                        </td>

                        <td class="p-3 border">
                            {{ space.type }}
                        </td>

                        <td class="p-3 border">
                            {{ space.capacity }}
                        </td>

                        <td class="p-3 border">
                            {{ space.price_per_hour }}
                        </td>

                        <td class="p-3 border">

                            <span v-if="space.is_active">
                                Sí
                            </span>

                            <span v-else>
                                No
                            </span>

                        </td>

                        <td class="p-3 border">

                            <div class="flex gap-3">

                                <Link
                                    :href="`/admin/spaces/${space.slug}/edit`"
                                    class="text-blue-500"
                                >
                                    Editar
                                </Link>

                                <Link
                                    :href="`/admin/spaces/${space.slug}/blocked-slots/create`"
                                    class="text-orange-500"
                                >
                                    Bloquear
                                </Link>

                                <Link
                                    :href="`/admin/spaces/${space.slug}/blocked-slots`"
                                    class="text-yellow-600"
                                >
                                    Ver bloqueos
                                </Link>

                                <button
                                    @click="toggleSpace(space.slug)"
                                    class="text-red-500"
                                >
                                    {{ space.is_active ? 'Desactivar' : 'Activar' }}
                                </button>

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</template>