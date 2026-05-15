<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
})

const classes = computed(() => {
    return props.active
        ? `
        flex items-center
        w-full
        rounded-2xl
        px-4 py-3
        text-sm font-semibold
        bg-sky-50
        text-sky-700
        border border-sky-200
        transition-all duration-200

        dark:bg-sky-500/10
        dark:text-sky-300
        dark:border-sky-500/20
        `
        : `
        flex items-center
        w-full
        rounded-2xl
        px-4 py-3
        text-sm font-medium
        text-slate-600
        transition-all duration-200

        hover:bg-slate-100
        hover:text-slate-900

        dark:text-slate-300
        dark:hover:bg-slate-800
        dark:hover:text-white
        `
})
</script>

<template>
    <div>
        <button
            v-if="as === 'button'"
            :class="classes"
            class="w-full text-left"
        >
            <slot />
        </button>

        <a
            v-else-if="as === 'a'"
            :href="href"
            :class="classes"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            :class="classes"
        >
            <slot />
        </Link>
    </div>
</template>