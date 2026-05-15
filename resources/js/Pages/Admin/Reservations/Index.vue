<script setup>
import { router } from '@inertiajs/vue3'
import { reactive, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    reservations: { type: Array,  default: () => [] },
    spaces:       { type: Array,  default: () => [] },
    filters:      { type: Object, default: () => ({}) },
})

const filters = reactive({
    status:   props.filters.status   || '',
    space_id: props.filters.space_id || '',
    date:     props.filters.date     || '',
})

const showReasonModal         = ref(false)
const reasonType              = ref('')
const selectedReservationId   = ref(null)
const adminReason             = ref('')

const confirmReservation = (id) => router.patch(`/admin/reservations/${id}/confirm`)
const finishReservation  = (id) => router.patch(`/admin/reservations/${id}/finish`)

const openReasonModal = (id, type) => {
    selectedReservationId.value = id
    reasonType.value            = type
    adminReason.value           = ''
    showReasonModal.value       = true
}

const submitReason = () => {
    if (!adminReason.value.trim()) return alert('Debes escribir un motivo')
    const route = reasonType.value === 'reject' ? 'reject' : 'cancel'
    router.patch(
        `/admin/reservations/${selectedReservationId.value}/${route}`,
        { admin_reason: adminReason.value },
        { onSuccess: () => { showReasonModal.value = false } }
    )
}

const applyFilters = () => router.get('/admin/reservations', filters, { preserveState: true, replace: true })

const clearFilters = () => {
    filters.status   = ''
    filters.space_id = ''
    filters.date     = ''
    applyFilters()
}

const formatDate = (dt) => new Date(dt).toLocaleDateString('es-CO', { day: 'numeric', month: 'short', year: 'numeric' })
const formatTime = (dt) => new Date(dt).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' })

const STATUS_MAP = {
    pending:   { label: 'Pendiente',  badge: 'badge-pending'  },
    confirmed: { label: 'Confirmada', badge: 'badge-confirmed'},
    rejected:  { label: 'Rechazada',  badge: 'badge-rejected' },
    cancelled: { label: 'Cancelada',  badge: 'badge-cancelled'},
    finished:  { label: 'Finalizada', badge: 'badge-finished' },
}

const initials = (name) =>
    (name ?? '').split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase()

defineOptions({ layout: AppLayout })
</script>

<template>

    <!-- ── MODAL ──────────────────────────────────────────────────────────── -->
    <Teleport to="body">
        <Transition name="modal-fade">
            <div
                v-if="showReasonModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-overlay"
                @click.self="showReasonModal = false"
            >
                <Transition name="modal-scale">
                    <div class="modal-box w-full max-w-md rounded-2xl p-6 shadow-2xl">

                        <!-- Modal header -->
                        <div class="flex items-start justify-between mb-5">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="h-2 w-2 rounded-full"
                                        :class="reasonType === 'reject' ? 'dot-rejected' : 'dot-cancelled'"
                                    ></span>
                                    <h2 class="text-base font-bold modal-title">
                                        {{ reasonType === 'reject' ? 'Rechazar reserva' : 'Cancelar reserva' }}
                                    </h2>
                                </div>
                                <p class="text-sm modal-muted">Escribe el motivo para informar al usuario.</p>
                            </div>
                            <button @click="showReasonModal = false" class="modal-close h-7 w-7 flex items-center justify-center rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Textarea -->
                        <textarea
                            v-model="adminReason"
                            rows="4"
                            class="modal-textarea w-full rounded-xl p-3.5 text-sm resize-none outline-none transition"
                            placeholder="Ej: El espacio estará en mantenimiento ese día."
                        />

                        <!-- Footer -->
                        <div class="flex justify-end gap-2 mt-4">
                            <button
                                @click="showReasonModal = false"
                                class="btn-secondary rounded-xl px-4 py-2 text-sm font-semibold transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                @click="submitReason"
                                class="rounded-xl px-4 py-2 text-sm font-semibold text-white transition-colors"
                                :class="reasonType === 'reject' ? 'btn-reject' : 'btn-cancel-submit'"
                            >
                                {{ reasonType === 'reject' ? 'Rechazar' : 'Cancelar reserva' }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>

    <!-- ── PAGE ───────────────────────────────────────────────────────────── -->
    <div class="page-bg min-h-screen">
        <div class="mx-auto max-w-screen-xl px-4 py-6 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold page-title tracking-tight">Reservas</h1>
                <p class="text-sm page-muted mt-0.5">Administración de todas las reservas</p>
            </div>

            <!-- Filters -->
            <div class="filter-bar calendar-wrap rounded-2xl p-4 mb-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                    <!-- Estado -->
                    <div class="filter-group relative">
                        <div class="filter-icon pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path fill-rule="evenodd" d="M1.75 2a.75.75 0 0 0 0 1.5h12.5a.75.75 0 0 0 0-1.5H1.75ZM3.5 6.75A.75.75 0 0 1 4.25 6h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 3.5 6.75ZM6 11a.75.75 0 0 0 0 1.5h4a.75.75 0 0 0 0-1.5H6Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <select v-model="filters.status" @change="applyFilters" class="filter-select w-full h-10 rounded-xl pl-9 pr-3 text-sm font-medium outline-none transition appearance-none">
                            <option value="">Todos los estados</option>
                            <option value="pending">Pendiente</option>
                            <option value="confirmed">Confirmada</option>
                            <option value="rejected">Rechazada</option>
                            <option value="cancelled">Cancelada</option>
                            <option value="finished">Finalizada</option>
                        </select>
                    </div>

                    <!-- Espacio -->
                    <div class="filter-group relative">
                        <div class="filter-icon pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <select v-model="filters.space_id" @change="applyFilters" class="filter-select w-full h-10 rounded-xl pl-9 pr-3 text-sm font-medium outline-none transition appearance-none">
                            <option value="">Todos los espacios</option>
                            <option v-for="space in spaces" :key="space.id" :value="space.id">{{ space.name }}</option>
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div class="filter-group relative">
                        <div class="filter-icon pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                <path fill-rule="evenodd" d="M4 1.75a.75.75 0 0 1 1.5 0V3h5V1.75a.75.75 0 0 1 1.5 0V3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2V1.75ZM4.5 6a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7Zm0 2.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1h-7Zm0 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4Z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input
                            v-model="filters.date"
                            @change="applyFilters"
                            type="date"
                            class="filter-select w-full h-10 rounded-xl pl-9 pr-3 text-sm font-medium outline-none transition"
                        >
                    </div>

                    <!-- Limpiar -->
                    <button
                        @click="clearFilters"
                        class="btn-clear h-10 rounded-xl px-4 text-sm font-semibold transition-colors flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm2.78-4.22a.75.75 0 0 1-1.06 0L8 9.06l-1.72 1.72a.75.75 0 1 1-1.06-1.06L6.94 8 5.22 6.28a.75.75 0 0 1 1.06-1.06L8 6.94l1.72-1.72a.75.75 0 1 1 1.06 1.06L9.06 8l1.72 1.72a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd"/>
                        </svg>
                        Limpiar filtros
                    </button>

                </div>
            </div>

            <!-- Empty state -->
            <div v-if="!reservations.length" class="empty-state flex flex-col items-center justify-center rounded-2xl border-2 border-dashed py-28 text-center">
                <div class="mb-4 h-14 w-14 rounded-2xl empty-icon flex items-center justify-center text-3xl">📋</div>
                <p class="font-semibold page-title">Sin reservas</p>
                <p class="mt-1 text-sm page-muted">No hay reservas que coincidan con los filtros.</p>
            </div>

            <!-- Desktop table -->
            <div v-else class="hidden md:block calendar-wrap rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="calendar-head">
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Espacio</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Usuario</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Inicio</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Fin</th>
                            <th class="px-4 py-3 text-left panel-label font-bold border-b calendar-border">Estado</th>
                            <th class="px-4 py-3 text-right panel-label font-bold border-b calendar-border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="r in reservations"
                            :key="r.id"
                            class="table-row border-b calendar-row-border last:border-b-0 transition-colors"
                        >
                            <!-- Espacio -->
                            <td class="px-4 py-3">
                                <p class="font-semibold page-title">{{ r.space.name }}</p>
                            </td>

                            <!-- Usuario -->
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-full avatar-bg flex items-center justify-center text-xs font-bold avatar-text flex-shrink-0">
                                        {{ initials(r.user_name) }}
                                    </div>
                                    <span class="page-title">{{ r.user_name }}</span>
                                </div>
                            </td>

                            <!-- Inicio -->
                            <td class="px-4 py-3">
                                <p class="page-title font-medium">{{ formatDate(r.start_time) }}</p>
                                <p class="text-xs page-muted">{{ formatTime(r.start_time) }}</p>
                            </td>

                            <!-- Fin -->
                            <td class="px-4 py-3">
                                <p class="page-title font-medium">{{ formatDate(r.end_time) }}</p>
                                <p class="text-xs page-muted">{{ formatTime(r.end_time) }}</p>
                            </td>

                            <!-- Estado -->
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full px-2.5 py-1"
                                    :class="STATUS_MAP[r.status]?.badge"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full" :class="`dot-${r.status}`"></span>
                                    {{ STATUS_MAP[r.status]?.label ?? r.status }}
                                </span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                    <button
                                        v-if="r.status === 'pending'"
                                        @click="confirmReservation(r.id)"
                                        class="action-btn action-confirm inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
                                        </svg>
                                        Confirmar
                                    </button>
                                    <button
                                        v-if="r.status === 'pending'"
                                        @click="openReasonModal(r.id, 'reject')"
                                        class="action-btn action-reject inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path d="M5.28 4.22a.75.75 0 0 0-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 1 0 1.06 1.06L8 9.06l2.72 2.72a.75.75 0 1 0 1.06-1.06L9.06 8l2.72-2.72a.75.75 0 0 0-1.06-1.06L8 6.94 5.28 4.22Z"/>
                                        </svg>
                                        Rechazar
                                    </button>
                                    <button
                                        v-if="r.status === 'confirmed'"
                                        @click="openReasonModal(r.id, 'cancel')"
                                        class="action-btn action-cancel inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM4.75 7.25a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z" clip-rule="evenodd"/>
                                        </svg>
                                        Cancelar
                                    </button>
                                    <button
                                        v-if="r.status === 'confirmed'"
                                        @click="finishReservation(r.id)"
                                        class="action-btn action-finish inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold transition-colors"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5">
                                            <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.086l4.25-5.5-.001.043Z" clip-rule="evenodd"/>
                                        </svg>
                                        Finalizar
                                    </button>
                                    <span v-if="['rejected','cancelled','finished'].includes(r.status)" class="text-xs page-muted italic">—</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile cards -->
            <div v-if="reservations.length" class="md:hidden space-y-3">
                <div
                    v-for="r in reservations"
                    :key="r.id"
                    class="calendar-wrap rounded-2xl p-4"
                >
                    <!-- Top -->
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <p class="font-bold page-title">{{ r.space.name }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <div class="h-5 w-5 rounded-full avatar-bg flex items-center justify-center text-[10px] font-bold avatar-text flex-shrink-0">
                                    {{ initials(r.user_name) }}
                                </div>
                                <p class="text-xs page-muted">{{ r.user_name }}</p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center gap-1.5 text-xs font-semibold rounded-full px-2.5 py-1 flex-shrink-0"
                            :class="STATUS_MAP[r.status]?.badge"
                        >
                            <span class="h-1.5 w-1.5 rounded-full" :class="`dot-${r.status}`"></span>
                            {{ STATUS_MAP[r.status]?.label ?? r.status }}
                        </span>
                    </div>

                    <!-- Times -->
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="mobile-stat rounded-xl p-3">
                            <p class="panel-label mb-0.5">Inicio</p>
                            <p class="text-sm font-semibold page-title">{{ formatDate(r.start_time) }}</p>
                            <p class="text-xs page-muted">{{ formatTime(r.start_time) }}</p>
                        </div>
                        <div class="mobile-stat rounded-xl p-3">
                            <p class="panel-label mb-0.5">Fin</p>
                            <p class="text-sm font-semibold page-title">{{ formatDate(r.end_time) }}</p>
                            <p class="text-xs page-muted">{{ formatTime(r.end_time) }}</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2" v-if="['pending','confirmed'].includes(r.status)">
                        <button
                            v-if="r.status === 'pending'"
                            @click="confirmReservation(r.id)"
                            class="action-btn action-confirm inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5"><path fill-rule="evenodd" d="M12.416 3.376a.75.75 0 0 1 .208 1.04l-5 7.5a.75.75 0 0 1-1.154.114l-3-3a.75.75 0 0 1 1.06-1.06l2.353 2.353 4.493-6.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/></svg>
                            Confirmar
                        </button>
                        <button
                            v-if="r.status === 'pending'"
                            @click="openReasonModal(r.id, 'reject')"
                            class="action-btn action-reject inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5"><path d="M5.28 4.22a.75.75 0 0 0-1.06 1.06L6.94 8l-2.72 2.72a.75.75 0 1 0 1.06 1.06L8 9.06l2.72 2.72a.75.75 0 1 0 1.06-1.06L9.06 8l2.72-2.72a.75.75 0 0 0-1.06-1.06L8 6.94 5.28 4.22Z"/></svg>
                            Rechazar
                        </button>
                        <button
                            v-if="r.status === 'confirmed'"
                            @click="openReasonModal(r.id, 'cancel')"
                            class="action-btn action-cancel inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14ZM4.75 7.25a.75.75 0 0 0 0 1.5h6.5a.75.75 0 0 0 0-1.5h-6.5Z" clip-rule="evenodd"/></svg>
                            Cancelar
                        </button>
                        <button
                            v-if="r.status === 'confirmed'"
                            @click="finishReservation(r.id)"
                            class="action-btn action-finish inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-3.5 w-3.5"><path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm3.844-8.791a.75.75 0 0 0-1.188-.918l-3.7 4.79-1.649-1.833a.75.75 0 1 0-1.114 1.004l2.25 2.5a.75.75 0 0 0 1.15-.086l4.25-5.5-.001.043Z" clip-rule="evenodd"/></svg>
                            Finalizar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<style scoped>

/* ── Transitions ─────────────────────────────────────────────────────────── */
.modal-fade-enter-active, .modal-fade-leave-active   { transition: opacity 0.15s ease; }
.modal-fade-enter-from,   .modal-fade-leave-to       { opacity: 0; }
.modal-scale-enter-active, .modal-scale-leave-active { transition: transform 0.15s cubic-bezier(0.4,0,0.2,1), opacity 0.15s ease; }
.modal-scale-enter-from,   .modal-scale-leave-to     { transform: scale(0.96); opacity: 0; }

/* ── Page ────────────────────────────────────────────────────────────────── */
.page-bg    { background: #F8FAFC; }
.page-title { color: #0F172A; }
.page-muted { color: #94A3B8; }

@media (prefers-color-scheme: dark) {
    .page-bg    { background: #0F172A; }
    .page-title { color: #F1F5F9; }
    .page-muted { color: #64748B; }
}

/* ── Panel label ─────────────────────────────────────────────────────────── */
.panel-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .14em;
    color: #94A3B8;
}
@media (prefers-color-scheme: dark) { .panel-label { color: #475569; } }

/* ── Card / table wrap ───────────────────────────────────────────────────── */
.calendar-wrap       { background: #fff; border: 1px solid #E2E8F0; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.calendar-head       { background: #F8FAFC; }
.calendar-border     { border-color: #E2E8F0; }
.calendar-row-border { border-color: #F1F5F9; }

@media (prefers-color-scheme: dark) {
    .calendar-wrap       { background: #1E293B; border-color: #334155; }
    .calendar-head       { background: #172032; }
    .calendar-border     { border-color: #293548; }
    .calendar-row-border { border-color: #243040; }
}

/* ── Table row hover ─────────────────────────────────────────────────────── */
.table-row:hover { background: #F8FAFC; }
@media (prefers-color-scheme: dark) { .table-row:hover { background: #172032; } }

/* ── Avatar ──────────────────────────────────────────────────────────────── */
.avatar-bg   { background: #EDE9FE; }
.avatar-text { color: #4C1D95; }
@media (prefers-color-scheme: dark) {
    .avatar-bg   { background: rgba(139,92,246,0.2); }
    .avatar-text { color: #C4B5FD; }
}

/* ── Filters ─────────────────────────────────────────────────────────────── */
.filter-bar   {}
.filter-group {}
.filter-icon  { color: #94A3B8; }
.filter-select {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    color: #1E293B;
}
.filter-select:focus { border-color: #60A5FA; box-shadow: 0 0 0 3px rgba(96,165,250,0.15); }

@media (prefers-color-scheme: dark) {
    .filter-icon   { color: #475569; }
    .filter-select { background: #172032; border-color: #293548; color: #E2E8F0; }
    .filter-select:focus { border-color: #3B82F6; }
}

/* ── Clear button ────────────────────────────────────────────────────────── */
.btn-clear { background: #FEF2F2; color: #991B1B; }
.btn-clear:hover { background: #FEE2E2; }
@media (prefers-color-scheme: dark) {
    .btn-clear { background: rgba(239,68,68,0.1); color: #FCA5A5; }
    .btn-clear:hover { background: rgba(239,68,68,0.18); }
}

/* ── Status badges ───────────────────────────────────────────────────────── */
.badge-pending   { background: #FFFBEB; color: #92400E; }
.badge-confirmed { background: #EFF6FF; color: #1E40AF; }
.badge-rejected  { background: #FEF2F2; color: #991B1B; }
.badge-cancelled { background: #F1F5F9; color: #475569; }
.badge-finished  { background: #F0FDF4; color: #166534; }

.dot-pending   { background: #FBBF24; }
.dot-confirmed { background: #3B82F6; }
.dot-rejected  { background: #EF4444; }
.dot-cancelled { background: #94A3B8; }
.dot-finished  { background: #22C55E; }

@media (prefers-color-scheme: dark) {
    .badge-pending   { background: rgba(251,191,36,0.1);  color: #FCD34D; }
    .badge-confirmed { background: rgba(59,130,246,0.12); color: #93C5FD; }
    .badge-rejected  { background: rgba(239,68,68,0.1);   color: #FCA5A5; }
    .badge-cancelled { background: rgba(100,116,139,0.12);color: #94A3B8; }
    .badge-finished  { background: rgba(34,197,94,0.1);   color: #86EFAC; }
}

/* ── Action buttons ──────────────────────────────────────────────────────── */
.action-btn { white-space: nowrap; }

.action-confirm       { background: #ECFDF5; color: #065F46; }
.action-confirm:hover { background: #D1FAE5; }

.action-reject        { background: #FEF2F2; color: #991B1B; }
.action-reject:hover  { background: #FEE2E2; }

.action-cancel        { background: #FFFBEB; color: #92400E; }
.action-cancel:hover  { background: #FEF3C7; }

.action-finish        { background: #EFF6FF; color: #1E40AF; }
.action-finish:hover  { background: #DBEAFE; }

@media (prefers-color-scheme: dark) {
    .action-confirm       { background: rgba(52,211,153,0.1);  color: #6EE7B7; }
    .action-confirm:hover { background: rgba(52,211,153,0.18); }

    .action-reject        { background: rgba(239,68,68,0.1);   color: #FCA5A5; }
    .action-reject:hover  { background: rgba(239,68,68,0.18);  }

    .action-cancel        { background: rgba(251,191,36,0.1);  color: #FCD34D; }
    .action-cancel:hover  { background: rgba(251,191,36,0.18); }

    .action-finish        { background: rgba(59,130,246,0.12); color: #93C5FD; }
    .action-finish:hover  { background: rgba(59,130,246,0.2);  }
}

/* ── Mobile stat blocks ──────────────────────────────────────────────────── */
.mobile-stat { background: #F8FAFC; }
@media (prefers-color-scheme: dark) { .mobile-stat { background: #172032; } }

/* ── Modal ───────────────────────────────────────────────────────────────── */
.modal-overlay { background: rgba(0,0,0,0.45); backdrop-filter: blur(2px); }
.modal-box     { background: #fff; border: 1px solid #E2E8F0; }
.modal-title   { color: #0F172A; }
.modal-muted   { color: #94A3B8; }
.modal-close   { color: #94A3B8; }
.modal-close:hover { background: #F1F5F9; color: #475569; }
.modal-textarea {
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    color: #0F172A;
}
.modal-textarea:focus { border-color: #60A5FA; box-shadow: 0 0 0 3px rgba(96,165,250,0.15); }

.btn-secondary       { background: #F1F5F9; color: #475569; }
.btn-secondary:hover { background: #E2E8F0; }

.btn-reject        { background: #EF4444; }
.btn-reject:hover  { background: #DC2626; }

.btn-cancel-submit        { background: #F59E0B; }
.btn-cancel-submit:hover  { background: #D97706; }

@media (prefers-color-scheme: dark) {
    .modal-box      { background: #1E293B; border-color: #334155; }
    .modal-title    { color: #F1F5F9; }
    .modal-muted    { color: #64748B; }
    .modal-close    { color: #64748B; }
    .modal-close:hover { background: #293548; color: #94A3B8; }
    .modal-textarea {
        background: #172032;
        border-color: #293548;
        color: #F1F5F9;
    }
    .modal-textarea::placeholder { color: #475569; }
    .btn-secondary       { background: #293548; color: #94A3B8; }
    .btn-secondary:hover { background: #334155; }
}

/* ── Empty state ─────────────────────────────────────────────────────────── */
.empty-state { border-color: #E2E8F0; }
.empty-icon  { background: #F1F5F9; }
@media (prefers-color-scheme: dark) {
    .empty-state { border-color: #293548; }
    .empty-icon  { background: #172032; }
}
</style>