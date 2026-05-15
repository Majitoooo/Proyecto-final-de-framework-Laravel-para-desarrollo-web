<script setup>
import {
    Head,
    Link,
    useForm
} from '@inertiajs/vue3'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form =
    useForm({
        email: '',
        password: '',
        remember: false,
    })

const submit =
    () => {

        form
            .transform(
                data => ({
                    ...data,
                    remember:
                        form.remember
                            ? 'on'
                            : '',
                })
            )
            .post(
                route('login'),
                {
                    onFinish:
                        () =>
                            form.reset(
                                'password'
                            ),
                }
            )
    }
</script>

<template>
    <Head title="Iniciar sesión" />

    <div
        class="
            min-h-screen
            bg-slate-50
            flex
            items-center
            justify-center
            px-6
            py-10
        "
    >
        <div
            class="
                w-full
                max-w-6xl
                grid
                lg:grid-cols-2
                bg-white
                rounded-[40px]
                shadow-xl
                overflow-hidden
                border
                border-slate-200
            "
        >
            <!-- izquierda -->
            <div
                class="
                    hidden
                    lg:flex
                    flex-col
                    justify-between
                    p-14
                    bg-slate-900
                    text-white
                    relative
                    overflow-hidden
                "
            >
                <div
                    class="
                        absolute
                        top-0
                        right-0
                        h-80
                        w-80
                        rounded-full
                        bg-blue-500/20
                        blur-[120px]
                    "
                />

                <div class="relative z-10">

                    <div
                        class="
                            flex
                            items-center
                            gap-4
                            mb-10
                        "
                    >
                        <div
                            class="
                                h-14
                                w-14
                                rounded-2xl
                                bg-white
                                text-slate-900
                                flex
                                items-center
                                justify-center
                                font-black
                                text-xl
                            "
                        >
                            C
                        </div>

                        <div>
                            <h1
                                class="
                                    text-2xl
                                    font-bold
                                "
                            >
                                CoWorkly
                            </h1>

                            <p
                                class="
                                    text-slate-300
                                "
                            >
                                Reserva espacios inteligentes
                            </p>
                        </div>
                    </div>

                    <h2
                        class="
                            text-5xl
                            font-black
                            leading-tight
                        "
                    >
                        Bienvenido
                        de nuevo
                    </h2>

                    <p
                        class="
                            text-slate-300
                            mt-6
                            text-lg
                            leading-relaxed
                            max-w-md
                        "
                    >
                        Gestiona tus
                        reservas,
                        espacios y
                        reuniones
                        desde una sola
                        plataforma.
                    </p>
                </div>

                <div
                    class="
                        relative z-10
                        grid grid-cols-3
                        gap-4
                    "
                >
                    <div
                        class="
                            rounded-3xl
                            bg-white/10
                            p-5
                            backdrop-blur-sm
                        "
                    >
                        <h3
                            class="
                                text-2xl
                                font-bold
                            "
                        >
                            Moderno
                        </h3>

                        <p
                            class="
                                text-sm
                                text-slate-300
                            "
                        >
                            Acceso
                        </p>
                    </div>

                    <div
                        class="
                            rounded-3xl
                            bg-white/10
                            p-5
                        "
                    >
                        <h3
                            class="
                                text-2xl
                                font-bold
                            "
                        >
                            WiFi
                        </h3>

                        <p
                            class="
                                text-sm
                                text-slate-300
                            "
                        >
                            Rápido
                        </p>
                    </div>

                    <div
                        class="
                            rounded-3xl
                            bg-white/10
                            p-5
                        "
                    >
                        <h3
                            class="
                                text-2xl
                                font-bold
                            "
                        >
                            ☕
                        </h3>

                        <p
                            class="
                                text-sm
                                text-slate-300
                            "
                        >
                            Café
                        </p>
                    </div>
                </div>
            </div>

            <!-- derecha -->
            <div
                class="
                    p-10
                    lg:p-16
                    flex
                    items-center
                "
            >
                <div class="w-full">

                    <div class="mb-10">
                        <h2
                            class="
                                text-4xl
                                font-bold
                                text-slate-900
                            "
                        >
                            Iniciar sesión
                        </h2>

                        <p
                            class="
                                text-slate-500
                                mt-2
                            "
                        >
                            Ingresa a tu cuenta
                            de CoWorkly
                        </p>
                    </div>

                    <div
                        v-if="status"
                        class="
                            mb-6
                            rounded-2xl
                            bg-green-100
                            border
                            border-green-300
                            text-green-700
                            px-4
                            py-3
                        "
                    >
                        {{ status }}
                    </div>

                    <form
                        @submit.prevent="
                            submit
                        "
                        class="
                            space-y-5
                        "
                    >
                        <!-- email -->
                        <div>
                            <label
                                class="
                                    block
                                    mb-2
                                    text-sm
                                    font-medium
                                    text-slate-700
                                "
                            >
                                Correo electrónico
                            </label>

                            <input
                                v-model="
                                    form.email
                                "
                                type="email"
                                required
                                autofocus
                                autocomplete="username"
                                class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-300
                                    px-5
                                    py-4
                                    focus:border-blue-900
                                    focus:ring-blue-900
                                "
                            >

                            <p
                                v-if="
                                    form.errors.email
                                "
                                class="
                                    text-red-500
                                    text-sm
                                    mt-2
                                "
                            >
                                {{
                                    form.errors.email
                                }}
                            </p>
                        </div>

                        <!-- password -->
                        <div>
                            <label
                                class="
                                    block
                                    mb-2
                                    text-sm
                                    font-medium
                                    text-slate-700
                                "
                            >
                                Contraseña
                            </label>

                            <input
                                v-model="
                                    form.password
                                "
                                type="password"
                                required
                                autocomplete="current-password"
                                class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-300
                                    px-5
                                    py-4
                                    focus:border-blue-900
                                    focus:ring-blue-900
                                "
                            >
                        </div>

                        <!-- remember -->
                        <div
                            class="
                                flex
                                justify-between
                                items-center
                            "
                        >
                            <label
                                class="
                                    flex
                                    items-center
                                    gap-2
                                "
                            >
                                <input
                                    v-model="
                                        form.remember
                                    "
                                    type="checkbox"
                                    class="
                                        rounded
                                        border-slate-300
                                    "
                                >

                                <span
                                    class="
                                        text-sm
                                        text-slate-600
                                    "
                                >
                                    Recordarme
                                </span>
                            </label>

                            <Link
                                v-if="
                                    canResetPassword
                                "
                                :href="
                                    route(
                                        'password.request'
                                    )
                                "
                                class="
                                    text-sm
                                    text-blue-900
                                    hover:underline
                                "
                            >
                                ¿Olvidaste tu contraseña?
                            </Link>
                        </div>

                        <!-- submit -->
                        <button
                            type="submit"
                            :disabled="
                                form.processing
                            "
                            class="
                                w-full
                                rounded-2xl
                                bg-blue-900
                                hover:bg-blue-800
                                text-white
                                py-4
                                font-semibold
                                transition
                                shadow-lg
                            "
                        >
                            Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>