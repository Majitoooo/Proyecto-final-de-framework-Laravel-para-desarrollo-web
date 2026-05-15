<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    href: String,
    active: Boolean,
})

const classes = computed(() => {
    return props.active
        ? `
        inline-flex items-center
        rounded-xl
        px-4 py-2.5
        text-sm font-semibold
        bg-sky-50
        text-sky-700
        border border-sky-200
        shadow-sm
        transition-all duration-200

        dark:bg-sky-500/10
        dark:text-sky-300
        dark:border-sky-500/20
        `
        : `
        inline-flex items-center
        rounded-xl
        px-4 py-2.5
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
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>