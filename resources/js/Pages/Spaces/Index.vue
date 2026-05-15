<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AppLayout })

defineProps({ spaces: Array })

const typeIcons = {
    sala: '🏢', oficina: '💼', auditorio: '🎭', cabina: '🎙️', terraza: '🌿',
}
const getIcon = (type) => typeIcons[type?.toLowerCase()] ?? '📍'

const placeholderGrads = [
    'linear-gradient(135deg,#0f0c29,#302b63,#24243e)',
    'linear-gradient(135deg,#0d2818,#1a472a,#2d6a4f)',
    'linear-gradient(135deg,#1a0533,#3b0764,#4c1d95)',
    'linear-gradient(135deg,#1c0a0a,#7f1d1d,#991b1b)',
    'linear-gradient(135deg,#0c1a2e,#1e3a5f,#1e40af)',
    'linear-gradient(135deg,#111827,#1f2937,#374151)',
]
const getGrad = (i) => placeholderGrads[i % placeholderGrads.length]

const formatPrice = (p) => {
    const n = Number(p)
    return n % 1 === 0
        ? n.toLocaleString('es-CO')
        : n.toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })
}
</script>

<template>
    <div class="min-h-screen bg-white">

        <!-- HERO -->
        <section
            class="
                border-b
                border-slate-200
                bg-white
            "
        >
            <div
                class="
                    mx-auto
                    max-w-7xl
                    px-6
                    lg:px-10
                    py-16
                "
            >
                <div
                    class="
                        flex
                        flex-col
                        lg:flex-row
                        lg:items-end
                        lg:justify-between
                        gap-8
                    "
                >
                    <!-- Left -->
                    <div
                        class="
                            max-w-3xl
                        "
                    >
                        <p
                            class="
                                text-sm
                                uppercase
                                tracking-[0.25em]
                                font-bold
                                text-slate-400
                                mb-4
                            "
                        >
                            CoWorkly
                        </p>

                        <h1
                            class="
                                text-5xl
                                md:text-6xl
                                font-black
                                tracking-tight
                                leading-[0.95]
                                text-slate-900
                            "
                        >
                            Espacios
                            disponibles
                        </h1>

                        <p
                            class="
                                mt-5
                                text-lg
                                text-slate-500
                                leading-relaxed
                                max-w-2xl
                            "
                        >
                            Encuentra el
                            lugar ideal para
                            trabajar, reunirte
                            o impulsar tus ideas
                            en un entorno
                            profesional.
                        </p>
                    </div>

                    <!-- Right -->
                    <div
                        class="
                            flex
                            items-end
                            gap-4
                        "
                    >
                        <div
                            class="
                                text-right
                            "
                        >
                            <p
                                class="
                                    text-5xl
                                    font-black
                                    text-slate-900
                                    leading-none
                                "
                            >
                                {{
                                    String(
                                        spaces?.length ?? 0
                                    ).padStart(
                                        2,
                                        '0'
                                    )
                                }}
                            </p>

                            <p
                                class="
                                    mt-2
                                    text-sm
                                    text-slate-400
                                    font-medium
                                "
                            >
                                espacios disponibles
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SPACES -->
        <section
            v-if="spaces?.length"
            class="
                mx-auto
                max-w-7xl
                px-6
                lg:px-10
                py-16
            "
        >
            <div
                class="
                    grid
                    md:grid-cols-2
                    xl:grid-cols-3
                    gap-8
                "
            >
                <Link
                    v-for="(space, index) in spaces"
                    :key="space.id"
                    :href="`/spaces/${space.slug}`"
                    class="
                        group
                        overflow-hidden
                        rounded-[34px]
                        border
                        border-slate-200
                        bg-white
                        shadow-sm
                        hover:shadow-2xl
                        hover:-translate-y-2
                        transition-all
                        duration-500
                    "
                >
                    <!-- IMAGE -->
                    <div
                        class="
                            relative
                            h-[280px]
                            overflow-hidden
                        "
                    >
                        <img
                            v-if="space.image"
                            :src="`/storage/${space.image}`"
                            :alt="space.name"
                            class="
                                h-full
                                w-full
                                object-cover
                                transition
                                duration-700
                                group-hover:scale-[1.08]
                            "
                        >

                        <div
                            v-else
                            class="
                                h-full
                                flex
                                items-center
                                justify-center
                            "
                            :style="
                                `background:${getGrad(index)}`
                            "
                        >
                            <span
                                class="
                                    text-8xl
                                "
                            >
                                {{
                                    getIcon(
                                        space.type
                                    )
                                }}
                            </span>
                        </div>

                        <!-- overlay -->
                        <div
                            class="
                                absolute
                                inset-0
                                bg-gradient-to-t
                                from-black/40
                                via-transparent
                                to-transparent
                            "
                        />

                        <!-- available -->
                        <div
                            class="
                                absolute
                                top-5
                                left-5
                                rounded-full
                                bg-white/95
                                backdrop-blur-md
                                px-4
                                py-2
                                shadow-lg
                            "
                        >
                            <div
                                class="
                                    flex
                                    items-center
                                    gap-2
                                "
                            >
                                <span
                                    class="
                                        h-2
                                        w-2
                                        rounded-full
                                        bg-emerald-500
                                    "
                                />

                                <span
                                    class="
                                        text-xs
                                        font-bold
                                        text-slate-700
                                    "
                                >
                                    Disponible ahora
                                </span>
                            </div>
                        </div>

                        <!-- type -->
                        <div
                            class="
                                absolute
                                top-5
                                right-5
                                rounded-full
                                bg-black/70
                                px-4
                                py-2
                                backdrop-blur-md
                            "
                        >
                            <span
                                class="
                                    text-xs
                                    font-bold
                                    uppercase
                                    text-white
                                "
                            >
                                {{
                                    space.type
                                }}
                            </span>
                        </div>
                    </div>

                    <!-- BODY -->
                    <div
                        class="
                            p-7
                        "
                    >
                        <h2
                            class="
                                text-2xl
                                font-black
                                text-slate-900
                                tracking-tight
                            "
                        >
                            {{
                                space.name
                            }}
                        </h2>

                        <p
                            v-if="
                                space.description
                            "
                            class="
                                mt-4
                                text-slate-500
                                leading-relaxed
                                line-clamp-3
                                min-h-[72px]
                            "
                        >
                            {{
                                space.description
                            }}
                        </p>

                        <div
                            class="
                                mt-6
                                flex
                                items-center
                                justify-between
                            "
                        >
                            <div>
                                <p
                                    class="
                                        text-sm
                                        text-slate-400
                                    "
                                >
                                    Desde
                                </p>

                                <p
                                    class="
                                        text-3xl
                                        font-black
                                        text-slate-900
                                        leading-none
                                    "
                                >
                                    $
                                    {{
                                        formatPrice(
                                            space.price_per_hour
                                        )
                                    }}
                                </p>

                                <span
                                    class="
                                        text-sm
                                        text-slate-400
                                    "
                                >
                                    /hora
                                </span>
                            </div>

                            <div
                                class="
                                    text-right
                                "
                            >
                                <p
                                    class="
                                        text-sm
                                        text-slate-400
                                    "
                                >
                                    Capacidad
                                </p>

                                <p
                                    class="
                                        text-lg
                                        font-bold
                                        text-slate-700
                                    "
                                >
                                    {{
                                        space.capacity
                                    }}
                                    {{
                                        space.capacity === 1
                                            ? 'persona'
                                            : 'personas'
                                    }}
                                </p>
                            </div>
                        </div>

                        <!-- CTA -->
                        <div
                            class="
                                mt-7
                            "
                        >
                            <div
                                class="
                                    w-full
                                    rounded-2xl
                                    bg-slate-900
                                    py-4
                                    text-center
                                    font-semibold
                                    text-white
                                    transition
                                    group-hover:bg-black
                                "
                            >
                                Ver espacio
                            </div>
                        </div>
                    </div>
                </Link>
            </div>
        </section>

        <!-- EMPTY -->
        <section
            v-else
            class="
                py-40
                text-center
                px-6
            "
        >
            <div
                class="
                    mx-auto
                    flex
                    h-24
                    w-24
                    items-center
                    justify-center
                    rounded-[32px]
                    bg-slate-100
                    text-5xl
                "
            >
                🏢
            </div>

            <h2
                class="
                    mt-8
                    text-3xl
                    font-black
                    text-slate-900
                "
            >
                No hay espacios disponibles
            </h2>

            <p
                class="
                    mt-3
                    text-slate-500
                "
            >
                Estamos preparando nuevos espacios para ti.
            </p>
        </section>

    </div>
</template>