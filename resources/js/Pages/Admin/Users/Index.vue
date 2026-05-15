<template>
    <div class="py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestión de Usuarios</h2>
            <Link :href="route('admin.users.create')" class="btn-primary">
                Nuevo Usuario
            </Link>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reservas
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="user in users" :key="user.id" class="bg-white hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <img 
                                    :src="user.profile_photo_url" 
                                    alt="" 
                                    class="h-10 w-10 rounded-full"
                                >
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                                    <p class="text-sm text-gray-500">{{ user.email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span 
                                :class="[
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    user.role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'
                                ]"
                            >
                                {{ user.role === 'admin' ? 'Administrador' : 'Usuario' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.reservations_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <Link 
                                :href="route('admin.users.edit', user.id)" 
                                class="text-blue-600 hover:text-blue-900 mr-3"
                            >
                                Editar
                            </Link>
                            
                            <form 
                                @submit.prevent
                                :action="route('admin.users.destroy', user.id)"
                                method="POST"
                                @submit="confirmDelete(user)"
                            >
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="text-red-600 hover:text-red-900"
                                    :disabled="deleting === user.id"
                                >
                                    {{ deleting === user.id ? 'Eliminando...' : 'Eliminar' }}
                                </button>
                            </form>
                            
                            <Link 
                                :href="route('admin.users.toggle.role', user.id)" 
                                class="text-yellow-600 hover:text-yellow-900"
                                @click.prevent="toggleRole(user)"
                            >
                                {{ user.role === 'admin' ? 'Hacer Usuario' : 'Hacer Admin' }}
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Link from '@/Shared/Link'

export default {
    components: {
        Link
    },
    setup() {
        const deleting = ref(null)
        const page = usePage()

        const toggleRole = (user) => {
            if (!confirm(`¿Estás seguro de que quieres cambiar el rol de ${user.name} a ${user.role === 'admin' ? 'usuario' : 'administrador'}?`)) {
                return
            }

            window.Inertia.patch(
                route('admin.users.toggle.role', user.id),
                {},
                {
                    onSuccess: () => {
                        // Update the user role locally without full page refresh
                        user.role = user.role === 'admin' ? 'user' : 'admin'
                    }
                }
            )
        }

        const confirmDelete = (user) => {
            if (window.confirm(`¿Estás seguro de que deseas eliminar a ${user.name}? Esta acción no se puede deshacer.`)) {
                deleting.value = user.id
            }
        }

        return {
            deleting,
            toggleRole,
            confirmDelete
        }
    }
}
</script>