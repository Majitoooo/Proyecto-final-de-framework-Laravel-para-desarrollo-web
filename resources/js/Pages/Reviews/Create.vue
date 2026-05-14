<script setup>

import {
    ref
}
from 'vue'

import {
    useForm
}
from '@inertiajs/vue3'

const props =
    defineProps({

        reservation:
            Object
    })

const selectedRating =
    ref(0)

const hoverRating =
    ref(0)

const form =
    useForm({

        rating: null,

        comment: ''
    })

const setRating = (
    rating
) => {

    selectedRating.value =
        rating

    form.rating =
        rating
}

const submit = () => {

    form.post(

        `/reservations/${props.reservation.id}/review`
    )
}

</script>

<template>

<div
    class="
        min-h-screen
        bg-pink-50
        flex
        items-center
        justify-center
        p-6
    "
>

    <div
        class="
            bg-white
            rounded-[32px]
            shadow-xl
            p-10
            w-full
            max-w-2xl
        "
    >

        <h1
            class="
                text-4xl
                font-bold
                text-center
                mb-3
            "
        >
            ¿Cómo fue tu experiencia?
        </h1>

        <p
            class="
                text-center
                text-gray-500
                mb-10
            "
        >
            Tu opinión nos ayuda
            a mejorar 💖
        </p>

        <div
            class="
                text-center
                mb-8
            "
        >

            <h2
                class="
                    text-lg
                    font-semibold
                    mb-2
                "
            >
                {{
                    reservation
                        .space
                        .name
                }}
            </h2>

            <p
                class="
                    text-gray-500
                "
            >
                Califícanos
                de 1 a 5 estrellas
            </p>

        </div>

        <!-- estrellas -->

        <div
            class="
                flex
                justify-center
                gap-3
                mb-10
            "
        >

            <button
                v-for="
                    star
                    in
                    5
                "
                :key="
                    star
                "
                type="button"
                @click="
                    setRating(
                        star
                    )
                "
                @mouseenter="
                    hoverRating
                    =
                    star
                "
                @mouseleave="
                    hoverRating
                    =
                    0
                "
                class="
                    text-6xl
                    transition
                    hover:scale-110
                "
            >

                <span
                    :class="

                        (
                            hoverRating
                            || selectedRating
                        )
                        >=
                        star

                        ?

                        'text-yellow-400'

                        :

                        'text-gray-300'
                    "
                >
                    ★
                </span>

            </button>

        </div>

        <textarea
            v-model="
                form.comment
            "
            rows="5"
            placeholder="
                Cuéntanos cómo fue tu experiencia...
            "
            class="
                w-full
                border
                rounded-3xl
                p-5
                outline-none
                focus:ring-2
                focus:ring-pink-300
                resize-none
            "
        />

        <div
            v-if="
                form.errors.rating
            "
            class="
                text-red-500
                mt-3
            "
        >
            {{
                form.errors
                    .rating
            }}
        </div>

        <button
            @click="
                submit
            "
            class="
                w-full
                mt-8
                bg-pink-500
                hover:bg-pink-600
                text-white
                font-semibold
                py-4
                rounded-3xl
                transition
            "
        >
            Enviar calificación
        </button>

    </div>

</div>

</template>