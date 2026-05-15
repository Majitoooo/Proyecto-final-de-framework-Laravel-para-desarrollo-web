<template>
    <div class="py-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Editar Usuario</h2>
            <Link :href="route('admin.users.index')" class="btn-secondary">
                Volver a la lista
            </Link>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form @submit.prevent="submit" class="w-full max-w-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input 
                    v-model="form.name"
                    id="name"
                    type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :placeholder="user.name"
                >
                @if ($errors->first('name'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input 
                    v-model="form.email"
                    id="email"
                    type="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :placeholder="user.email"
                >
                @if ($errors->first('email'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña (dejar vacío para no cambiar)</label>
                <input 
                    v-model="form.password"
                    id="password"
                    type="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ingrese nueva contraseña si desea cambiarla"
                >
                @if ($errors->first('password'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                <input 
                    v-model="form.password_confirmation"
                    id="password_confirmation"
                    type="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Confirme la nueva contraseña"
                >
                @if ($errors->first('password_confirmation'))
                    <p class="mt-1 text-sm text-red-600">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select 
                    v-model="form.role"
                    id="role"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="user">Usuario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <button 
                    type="button"
                    @click="$inertia.get(route('admin.users.index'))"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300"
                >
                    Cancelar
                </button>
                <button 
                    type="submit"
                    :disabled="processing"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    {{ processing ? 'Actualizando...' : 'Actualizar Usuario' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Link from '@/Shared/Link'

export default {
    components: {
        Link
    },
    props: {
        user: Object
    },
    setup(props) {
        const form = useForm({
            name: props.user.name,
            email: props.user.email,
            password: '',
            password_confirmation: '',
            role: props.user.role
        })

        const processing = ref(false)

        const submit = () => {
            processing.value = true
            form.put(route('admin.users.update', props.user.id), {
                onSuccess: () => {
                    processing.value = false
                },
                onError: () => {
                    processing.value = false
                }
            })
        }

        return {
            form,
            processing,
            submit
        }
    }
}
</script>