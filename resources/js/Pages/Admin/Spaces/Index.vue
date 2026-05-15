<script setup>
import { Link, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    spaces: { type: Array, default: () => [] }
})

const toggleSpace = (slug) => router.patch(`/admin/spaces/${slug}/toggle`)

const deleteSpace = (
    slug
) => {

    if (
        !confirm(
            '¿Seguro que deseas eliminar este espacio?'
        )
    ) {
        return
    }

    router.delete(
        route(
            'spaces.destroy',
            slug
        )
    )
}

defineOptions({ layout: AppLayout })
</script>

<template>
    <div class="page-bg min-h-screen">
        <div class="mx-auto max-w-screen-xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold page-title tracking-tight">Espacios</h1>
                    <p class="text-sm page-muted mt-0.5">Administración de espacios de coworking</p>
                </div>
                <Link
                    href="/admin/spaces/create"
                    class="btn-primary inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-colors self-start sm:self-auto"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                        <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/>
                    </svg>
                    Crear espacio
                </Link>
            </div>

            <!-- Empty state -->
            <div v-if="!spaces.length" class="empty-state flex flex-col items-center justify-center rounded-2xl border-2 border-dashed py-28 text-center">
                <div class="mb-4 h-14 w-14 rounded-2xl empty-icon flex items-center justify-center text-3xl">🏢</div>
                <p class="font-semibold page-title">Sin espacios aún</p>
                <p class="mt-1 text-sm page-muted">Crea tu primer espacio para comenzar.</p>
            </div>

            <!-- Desktop table -->
            <div v-else class="hidden md:block calendar-wrap rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="calendar-head">
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border w-20">Imagen</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Nombre</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Tipo</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Capacidad</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Precio/hr</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Estado</th>
                            <th class="px-4 py-3 text-right panel-label font-bold border-b calendar-border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="space in spaces"
                            :key="space.id"
                            class="table-row border-b calendar-row-border last:border-b-0 transition-colors"
                        >
                            <!-- Imagen -->
                            <td class="px-4 py-3">
                                <img
                                    v-if="space.image"
                                    :src="`/storage/${space.image}`"
                                    :alt="space.name"
                                    class="w-14 h-14 object-cover rounded-xl"
                                >
                                <div v-else class="w-14 h-14 rounded-xl no-image flex items-center justify-center text-xl">
                                    🏢
                                </div>
                            </td>

                            <!-- Nombre -->
                            <td class="px-4 py-3">
                                <p class="font-semibold page-title">{{ space.name }}</p>
                                <p v-if="space.description" class="text-xs page-muted mt-0.5 line-clamp-1">{{ space.description }}</p>
                            </td>

                            <!-- Tipo -->
                            <td class="px-4 py-3">
                                <span class="badge-type text-xs font-medium rounded-lg px-2.5 py-1">{{ space.type }}</span>
                            </td>

                            <!-- Capacidad -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-3.5 w-3.5 page-muted">
                                        <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z"/>
                                    </svg>
                                    <span class="page-title font-medium">{{ space.capacity }}</span>
                                </div>
                            </td>

                            <!-- Precio -->
                            <td class="px-4 py-3">
                                <span class="font-semibold page-title">
                                    ${{ Number(space.price_per_hour).toLocaleString('es-CO') }}
                                </span>
                            </td>

                            <!-- Estado -->
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full px-2.5 py-1"
                                    :class="space.is_active ? 'badge-active' : 'badge-inactive'"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full" :class="space.is_active ? 'dot-active' : 'dot-inactive'"></span>
                                    {{ space.is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <Link
                                        :href="`/admin/spaces/${space.slug}/edit`"
                                        class="action-btn action-edit inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/>
                                            <path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/>
                                        </svg>
                                        Editar
                                    </Link>

                                    <Link
                                        :href="`/admin/spaces/${space.slug}/blocked-slots/create`"
                                        class="action-btn action-block inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM4.75 7.25a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z" clip-rule="evenodd"/>
                                        </svg>
                                        Bloquear
                                    </Link>

                                    <Link
                                        :href="`/admin/spaces/${space.slug}/blocked-slots`"
                                        class="action-btn action-slots inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path fill-rule="evenodd" d="M4 1.75a.75.75 0 0 1 1.5 0V3h5V1.75a.75.75 0 0 1 1.5 0V3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2V1.75ZM4.5 6a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7Zm0 2.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7Zm0 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4Z" clip-rule="evenodd"/>
                                        </svg>
                                        Bloqueos
                                    </Link>

                                    <button
                                        @click="toggleSpace(space.slug)"
                                        class="action-btn inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                        :class="space.is_active ? 'action-deactivate' : 'action-activate'"
                                    >
                                        <svg v-if="space.is_active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM6.75 5.25a.75.75 0 0 0-1.5 0v5.5a.75.75 0 0 0 1.5 0v-5.5Zm3.5 0a.75.75 0 0 0-1.5 0v5.5a.75.75 0 0 0 1.5 0v-5.5Z" clip-rule="evenodd"/>
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.396-6.737a.75.75 0 0 0 0-1.269l-4.5-2.6A.75.75 0 0 0 5.75 5v5.201a.75.75 0 0 0 1.146.634l4.5-2.572Z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ space.is_active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                    <button
                                        @click="
                                            deleteSpace(
                                                space.slug
                                            )
                                        "
                                        class="
                                            action-btn
                                            inline-flex
                                            items-center
                                            gap-1.5
                                            rounded-lg
                                            px-2.5
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            transition-colors
                                            action-delete
                                        "
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 16 16"
                                            fill="currentColor"
                                            class="h-3.5 w-3.5"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M6.5 1.75a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 .75.75V2H12a.75.75 0 0 1 0 1.5h-.538l-.853 9.102A1.75 1.75 0 0 1 8.866 14H7.134a1.75 1.75 0 0 1-1.743-1.398L4.538 3.5H4a.75.75 0 0 1 0-1.5h2.5v-.25Z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>

                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile cards -->
            <div v-if="spaces.length" class="md:hidden space-y-3">
                <div
                    v-for="space in spaces"
                    :key="space.id"
                    class="mobile-card calendar-wrap rounded-2xl p-4"
                >
                    <div class="flex items-start gap-3 mb-4">
                        <img
                            v-if="space.image"
                            :src="`/storage/${space.image}`"
                            :alt="space.name"
                            class="w-14 h-14 object-cover rounded-xl flex-shrink-0"
                        >
                        <div v-else class="w-14 h-14 rounded-xl no-image flex items-center justify-center text-2xl flex-shrink-0">🏢</div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="font-bold page-title leading-tight">{{ space.name }}</p>
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-semibold rounded-full px-2 py-0.5 flex-shrink-0"
                                    :class="space.is_active ? 'badge-active' : 'badge-inactive'"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full" :class="space.is_active ? 'dot-active' : 'dot-inactive'"></span>
                                    {{ space.is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <p class="text-xs page-muted mt-0.5">{{ space.type }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="mobile-stat rounded-xl p-3">
                            <p class="panel-label mb-0.5">Capacidad</p>
                            <p class="font-bold page-title text-sm">{{ space.capacity }} personas</p>
                        </div>
                        <div class="mobile-stat rounded-xl p-3">
                            <p class="panel-label mb-0.5">Precio/hr</p>
                            <p class="font-bold page-title text-sm">${{ Number(space.price_per_hour).toLocaleString('es-CO') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <Link
                            :href="`/admin/spaces/${space.slug}/edit`"
                            class="action-btn action-edit inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path d="M13.488 2.513a1.75 1.75 0 0 0-2.475 0L6.75 6.774a2.75 2.75 0 0 0-.596.892l-.848 2.047a.75.75 0 0 0 .98.98l2.047-.848a2.75 2.75 0 0 0 .892-.596l4.261-4.262a1.75 1.75 0 0 0 0-2.474Z"/>
                                <path d="M4.75 3.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h6.5c.69 0 1.25-.56 1.25-1.25V9A.75.75 0 0 1 14 9v2.25A2.75 2.75 0 0 1 11.25 14h-6.5A2.75 2.75 0 0 1 2 11.25v-6.5A2.75 2.75 0 0 1 4.75 2H7a.75.75 0 0 1 0 1.5H4.75Z"/>
                            </svg>
                            Editar
                        </Link>
                        <Link
                            :href="`/admin/spaces/${space.slug}/blocked-slots/create`"
                            class="action-btn action-block inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM4.75 7.25a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z" clip-rule="evenodd"/>
                            </svg>
                            Bloquear
                        </Link>
                        <Link
                            :href="`/admin/spaces/${space.slug}/blocked-slots`"
                            class="action-btn action-slots inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path fill-rule="evenodd" d="M4 1.75a.75.75 0 0 1 1.5 0V3h5V1.75a.75.75 0 0 1 1.5 0V3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2V1.75ZM4.5 6a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7Zm0 2.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7Zm0 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4Z" clip-rule="evenodd"/>
                            </svg>
                            Bloqueos
                        </Link>
                        <button
                            @click="toggleSpace(space.slug)"
                            class="action-btn inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                            :class="space.is_active ? 'action-deactivate' : 'action-activate'"
                        >
                            <svg v-if="space.is_active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM6.75 5.25a.75.75 0 0 0-1.5 0v5.5a.75.75 0 0 0 1.5 0v-5.5Zm3.5 0a.75.75 0 0 0-1.5 0v5.5a.75.75 0 0 0 1.5 0v-5.5Z" clip-rule="evenodd"/>
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.396-6.737a.75.75 0 0 0 0-1.269l-4.5-2.6A.75.75 0 0 0 5.75 5v5.201a.75.75 0 0 0 1.146.634l4.5-2.572Z" clip-rule="evenodd"/>
                            </svg>
                            {{ space.is_active ? 'Desactivar' : 'Activar' }}
                        </button>
                        <button
                            @click="
                                deleteSpace(
                                    space.slug
                                )
                            "
                            class="
                                action-btn
                                action-delete
                                inline-flex
                                items-center
                                gap-1.5
                                rounded-lg
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                transition-colors
                            "
                        >
                            🗑️
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>

/* ── Page ────────────────────────────────────────────────────────────────── */
.page-bg    { background: #F8FAFC; }
.page-title { color: #0F172A; }
.page-muted { color: #94A3B8; }

@media (prefers-color-scheme: dark) {
    .page-bg    { background: #0F172A; }
    .page-title { color: #F1F5F9; }
    .page-muted { color: #64748B; }
}

/* ── Primary button ──────────────────────────────────────────────────────── */
.btn-primary {
    background: #2563EB;
    color: #fff;
}
.btn-primary:hover { background: #1D4ED8; }

@media (prefers-color-scheme: dark) {
    .btn-primary { background: #3B82F6; }
    .btn-primary:hover { background: #2563EB; }
}

/* ── Panel label ─────────────────────────────────────────────────────────── */
.panel-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .14em;
    color: #94A3B8;
}
@media (prefers-color-scheme: dark) {
    .panel-label { color: #475569; }
}

/* ── Calendar/card wrap ──────────────────────────────────────────────────── */
.calendar-wrap      { background: #fff; border: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.calendar-head      { background: #F8FAFC; }
.calendar-border    { border-color: #E2E8F0; }
.calendar-row-border{ border-color: #F1F5F9; }

@media (prefers-color-scheme: dark) {
    .calendar-wrap       { background: #1E293B; border-color: #334155; }
    .calendar-head       { background: #172032; }
    .calendar-border     { border-color: #293548; }
    .calendar-row-border { border-color: #243040; }
}

/* ── Table row hover ─────────────────────────────────────────────────────── */
.table-row:hover { background: #F8FAFC; }
@media (prefers-color-scheme: dark) {
    .table-row:hover { background: #172032; }
}

/* ── No image placeholder ────────────────────────────────────────────────── */
.no-image { background: #F1F5F9; }
@media (prefers-color-scheme: dark) {
    .no-image { background: #293548; }
}

/* ── Badge type ──────────────────────────────────────────────────────────── */
.badge-type { background: #EDE9FE; color: #4C1D95; }
@media (prefers-color-scheme: dark) {
    .badge-type { background: rgba(139,92,246,0.15); color: #C4B5FD; }
}

/* ── Status badges ───────────────────────────────────────────────────────── */
.badge-active   { background: #ECFDF5; color: #065F46; }
.badge-inactive { background: #F1F5F9; color: #475569; }
.dot-active     { background: #34D399; }
.dot-inactive   { background: #94A3B8; }

@media (prefers-color-scheme: dark) {
    .badge-active   { background: rgba(52,211,153,0.12); color: #6EE7B7; }
    .badge-inactive { background: rgba(100,116,139,0.12); color: #94A3B8; }
    .dot-active     { background: #34D399; }
    .dot-inactive   { background: #64748B; }
}

/* ── Action buttons ──────────────────────────────────────────────────────── */
.action-btn { white-space: nowrap; }

.action-edit       { background: #EFF6FF; color: #1E40AF; }
.action-edit:hover { background: #DBEAFE; }

.action-block       { background: #FFFBEB; color: #92400E; }
.action-block:hover { background: #FEF3C7; }

.action-slots       { background: #F5F3FF; color: #4C1D95; }
.action-slots:hover { background: #EDE9FE; }

.action-deactivate       { background: #FEF2F2; color: #991B1B; }
.action-deactivate:hover { background: #FEE2E2; }

.action-activate       { background: #ECFDF5; color: #065F46; }
.action-activate:hover { background: #D1FAE5; }

.action-delete {
    background: #FEF2F2;
    color: #991B1B;
}

.action-delete:hover {
    background: #FEE2E2;
}

@media (prefers-color-scheme: dark) {
    .action-edit       { background: rgba(59,130,246,0.12); color: #93C5FD; }
    .action-edit:hover { background: rgba(59,130,246,0.2); }

    .action-block       { background: rgba(251,191,36,0.1); color: #FCD34D; }
    .action-block:hover { background: rgba(251,191,36,0.18); }

    .action-slots       { background: rgba(139,92,246,0.12); color: #C4B5FD; }
    .action-slots:hover { background: rgba(139,92,246,0.2); }

    .action-deactivate       { background: rgba(239,68,68,0.1); color: #FCA5A5; }
    .action-deactivate:hover { background: rgba(239,68,68,0.18); }

    .action-activate       { background: rgba(52,211,153,0.1); color: #6EE7B7; }
    .action-activate:hover { background: rgba(52,211,153,0.18); }

    .action-delete {
        background:
            rgba(
                239,
                68,
                68,
                0.1
            );

        color: #FCA5A5;
    }

    .action-delete:hover {
        background:
            rgba(
                239,
                68,
                68,
                0.18
            );
}
}

/* ── Mobile cards ────────────────────────────────────────────────────────── */
.mobile-stat { background: #F8FAFC; }
@media (prefers-color-scheme: dark) {
    .mobile-stat { background: #172032; }
}

/* ── Empty state ─────────────────────────────────────────────────────────── */
.empty-state { border-color: #E2E8F0; }
.empty-icon  { background: #F1F5F9; }
@media (prefers-color-scheme: dark) {
    .empty-state { border-color: #293548; }
    .empty-icon  { background: #172032; }
}
</style>