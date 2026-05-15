<script setup>
import {
    Head,
    Link,
    useForm
} from '@inertiajs/vue3'

const form =
    useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
    })

const submit =
    () => {

        form.post(
            route('register'),
            {
                onFinish:
                    () =>
                        form.reset(
                            'password',
                            'password_confirmation'
                        ),
            }
        )
    }
</script>

<template>
    <Head title="Crear cuenta" />

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
                        Crea tu cuenta
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
                        Regístrate para
                        comenzar a reservar
                        espacios de coworking,
                        salas y puestos
                        de trabajo.
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
                            Crear cuenta
                        </h2>

                        <p
                            class="
                                text-slate-500
                                mt-2
                            "
                        >
                            Únete a CoWorkly
                        </p>
                    </div>

                    <form
                        @submit.prevent="
                            submit
                        "
                        class="
                            space-y-5
                        "
                    >
                        <!-- nombre -->
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
                                Nombre completo
                            </label>

                            <input
                                v-model="
                                    form.name
                                "
                                type="text"
                                required
                                autofocus
                                autocomplete="name"
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
                                    form.errors.name
                                "
                                class="
                                    text-red-500
                                    text-sm
                                    mt-2
                                "
                            >
                                {{
                                    form.errors.name
                                }}
                            </p>
                        </div>

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
                                autocomplete="username"
                                class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-300
                                    px-5
                                    py-4
                                "
                            >
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
                                autocomplete="new-password"
                                class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-300
                                    px-5
                                    py-4
                                "
                            >
                        </div>

                        <!-- confirm -->
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
                                Confirmar contraseña
                            </label>

                            <input
                                v-model="
                                    form.password_confirmation
                                "
                                type="password"
                                required
                                autocomplete="new-password"
                                class="
                                    w-full
                                    rounded-2xl
                                    border
                                    border-slate-300
                                    px-5
                                    py-4
                                "
                            >
                        </div>

                        <!-- terms -->
                        <div
                            v-if="
                                $page.props.jetstream.hasTermsAndPrivacyPolicyFeature
                            "
                            class="
                                flex
                                items-start
                                gap-3
                            "
                        >
                            <input
                                v-model="
                                    form.terms
                                "
                                type="checkbox"
                                required
                                class="
                                    mt-1
                                    rounded
                                "
                            >

                            <div
                                class="
                                    text-sm
                                    text-slate-600
                                "
                            >
                                Acepto los

                                <a
                                    target="_blank"
                                    :href="
                                        route(
                                            'terms.show'
                                        )
                                    "
                                    class="
                                        text-blue-900
                                        font-medium
                                    "
                                >
                                    Términos del servicio
                                </a>

                                y la

                                <a
                                    target="_blank"
                                    :href="
                                        route(
                                            'policy.show'
                                        )
                                    "
                                    class="
                                        text-blue-900
                                        font-medium
                                    "
                                >
                                    Política de privacidad
                                </a>
                            </div>
                        </div>

                        <!-- actions -->
                        <div
                            class="
                                flex
                                items-center
                                justify-between
                                pt-4
                            "
                        >
                            <Link
                                :href="
                                    route(
                                        'login'
                                    )
                                "
                                class="
                                    text-sm
                                    text-slate-600
                                    hover:text-slate-900
                                "
                            >
                                ¿Ya tienes cuenta?
                            </Link>

                            <button
                                type="submit"
                                :disabled="
                                    form.processing
                                "
                                class="
                                    bg-blue-900
                                    hover:bg-blue-800
                                    text-white
                                    px-8
                                    py-4
                                    rounded-2xl
                                    transition
                                    font-semibold
                                    shadow-lg
                                "
                            >
                                Crear cuenta
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>