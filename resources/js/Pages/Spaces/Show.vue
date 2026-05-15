<script setup>
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    space: Object,
    date: String,
    slots: Array,
    nextSlots: Array
})

const page = usePage()

const selectedStart = ref('')
const selectedEnd = ref('')

const typeIcons = {
    sala: '🏢',
    oficina: '💼',
    auditorio: '🎭',
    cabina: '🎙️',
    terraza: '🌿',
}

const getIcon = (type) => typeIcons[type?.toLowerCase()] ?? '📍'

const availableEndSlots = computed(() => {
    if (!selectedStart.value) return []

    const startIndex = props.slots.findIndex(
        slot => slot.full_start === selectedStart.value
    )

    if (startIndex === -1) return []

    const validSlots = []

    for (let i = startIndex; i < props.slots.length; i++) {
        const current = props.slots[i]
        const next = props.slots[i + 1]

        validSlots.push({
            label: current.end,
            value: current.full_end
        })

        if (next && current.full_end !== next.full_start) break
    }

    return validSlots
})

defineOptions({ layout: AppLayout })
</script>

<template>
    <div class="page-wrapper">

        <!-- FLASH MESSAGE -->
        <Transition name="slide-down">
            <div
                v-if="page.props.flash?.success"
                class="flash-message"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="flash-icon">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                </svg>
                {{ page.props.flash.success }}
            </div>
        </Transition>

        <!-- SPACE CARD -->
        <div class="card space-card">

            <!-- HERO IMAGE / PLACEHOLDER -->
            <div class="hero">
                <img
                    v-if="space.image"
                    :src="`/storage/${space.image}`"
                    :alt="space.name"
                    class="hero-img"
                >
                <div v-else class="hero-placeholder">
                    <span class="hero-emoji">{{ getIcon(space.type) }}</span>
                </div>
                <div class="hero-overlay" />
            </div>

            <div class="card-body">

                <span class="name-pill">{{ space.name }}</span>

                <p class="description">{{ space.description }}</p>

                <!-- USAGE RULES -->
                <div v-if="space.usage_rules" class="rules-box">
                    <h2 class="rules-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="rules-icon">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                        </svg>
                        Reglas de uso
                    </h2>
                    <p class="rules-text">{{ space.usage_rules }}</p>
                </div>

                <!-- STATS -->
                <div class="stats-grid">
                    <div class="stat stat-blue">
                        <p class="stat-label">Tipo de espacio</p>
                        <p class="stat-value capitalize">{{ space.type }}</p>
                    </div>
                    <div class="stat stat-green">
                        <p class="stat-label">Capacidad</p>
                        <p class="stat-value">{{ space.capacity }} personas</p>
                    </div>
                    <div class="stat stat-pink">
                        <p class="stat-label">Precio por hora</p>
                        <p class="stat-value">${{ space.price_per_hour }}</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- DATE FILTER -->
        <div class="card">
            <div class="card-body">
                <h2 class="section-title">Consultar disponibilidad</h2>
                <form method="GET" class="date-form">
                    <div class="field-group">
                        <label class="field-label">Fecha</label>
                        <input
                            type="date"
                            name="date"
                            :value="date"
                            class="date-input"
                        >
                    </div>
                    <button type="submit" class="btn-search">Buscar</button>
                </form>
            </div>
        </div>

        <!-- AVAILABLE SLOTS -->
        <div class="slots-section">
            <h2 class="section-title">Horarios disponibles</h2>

            <div v-if="slots.length" class="card">
                <div class="card-body">
                    <div class="slots-grid">

                        <!-- START TIME -->
                        <div class="field-group">
                            <label class="field-label">Hora inicio</label>
                            <select v-model="selectedStart" class="select-input">
                                <option value="">Selecciona una hora</option>
                                <option
                                    v-for="slot in slots"
                                    :key="slot.full_start"
                                    :value="slot.full_start"
                                >
                                    {{ slot.start }}
                                </option>
                            </select>
                        </div>

                        <!-- END TIME -->
                        <div class="field-group">
                            <label class="field-label">Hora fin</label>
                            <select
                                v-model="selectedEnd"
                                class="select-input"
                                :disabled="!selectedStart"
                            >
                                <option value="">Selecciona hora final</option>
                                <option
                                    v-for="slot in availableEndSlots"
                                    :key="slot.value"
                                    :value="slot.value"
                                >
                                    {{ slot.label }}
                                </option>
                            </select>
                        </div>

                    </div>

                    <div class="divider" />

                    <Link
                        v-if="selectedStart && selectedEnd"
                        :href="`/reservations/create?space_id=${space.id}&start_time=${selectedStart}&end_time=${selectedEnd}`"
                        class="btn-cta"
                    >
                        Continuar con la reserva
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="btn-icon">
                            <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                        </svg>
                    </Link>
                </div>
            </div>

            <div v-else class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="empty-icon">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" />
                </svg>
                No hay horarios disponibles para esta fecha.
            </div>
        </div>

        <!-- NEXT AVAILABLE -->
        <div v-if="nextSlots.length" class="next-section">
            <h2 class="section-title">Próximos horarios disponibles</h2>
            <div class="next-list">
                <div
                    v-for="day in nextSlots"
                    :key="day.date"
                    class="card day-card"
                >
                    <div class="card-body">
                        <h3 class="day-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="day-icon">
                                <path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd" />
                            </svg>
                            {{ day.date }}
                        </h3>
                        <div class="chips-row">
                            <span
                                v-for="slot in day.slots"
                                :key="slot.full_start"
                                class="chip"
                            >
                                {{ slot.start }} – {{ slot.end }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
/* ── Layout ── */
.page-wrapper {
    max-width: 860px;
    margin: 0 auto;
    padding: 28px 20px 48px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ── Cards ── */
.card {
    background: white;
    border: 0.5px solid #e4e4e0;
    border-radius: 16px;
    overflow: hidden;
}

.dark .card {
    background: #1e2130;
    border-color: #2e3347;
}

.card-body {
    padding: 24px;
}

/* ── Flash message ── */
.flash-message {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f0fdf4;
    color: #16a34a;
    border: 0.5px solid #bbf7d0;
    border-radius: 12px;
    padding: 14px 18px;
    font-size: 14px;
}

.dark .flash-message {
    background: rgba(22, 163, 74, 0.12);
    color: #4ade80;
    border-color: rgba(74, 222, 128, 0.25);
}

.flash-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

/* ── Hero ── */
.hero {
    position: relative;
    height: 260px;
    overflow: hidden;
}

.hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 55%, #4f46e5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.hero-emoji {
    font-size: 80px;
    position: relative;
    z-index: 1;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.15) 100%);
}

/* ── Space name ── */
.name-pill {
    display: inline-block;
    background: white;
    border: 0.5px solid #d4d4d0;
    border-radius: 10px;
    padding: 8px 18px;
    font-size: 22px;
    font-weight: 500;
    margin-bottom: 16px;
    color: #111;
}

.dark .name-pill {
    background: #1e2130;
    border-color: #3a3f55;
    color: #f0f0ee;
}

/* ── Description ── */
.description {
    font-size: 15px;
    color: #6b7280;
    line-height: 1.7;
    margin-bottom: 20px;
}

.dark .description {
    color: #9ca3af;
}

/* ── Rules ── */
.rules-box {
    background: #fffbeb;
    border: 0.5px solid #fde68a;
    border-radius: 10px;
    padding: 16px 18px;
    margin-bottom: 20px;
}

.dark .rules-box {
    background: rgba(251, 191, 36, 0.08);
    border-color: rgba(251, 191, 36, 0.25);
}

.rules-title {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 500;
    color: #92400e;
    margin-bottom: 8px;
}

.dark .rules-title {
    color: #fcd34d;
}

.rules-icon {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.rules-text {
    font-size: 13px;
    color: #78716c;
    line-height: 1.65;
    white-space: pre-line;
}

.dark .rules-text {
    color: #a8a29e;
}

/* ── Stats grid ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}

@media (max-width: 540px) {
    .stats-grid { grid-template-columns: 1fr; }
}

.stat {
    border-radius: 10px;
    padding: 16px;
    border: 0.5px solid transparent;
}

.stat-label {
    font-size: 12px;
    font-weight: 500;
    margin-bottom: 6px;
    letter-spacing: 0.01em;
}

.stat-value {
    font-size: 18px;
    font-weight: 500;
    color: #111827;
}

.dark .stat-value {
    color: #f3f4f6;
}

.stat-blue {
    background: #eff6ff;
    border-color: #bfdbfe;
}
.stat-blue .stat-label { color: #1d4ed8; }
.dark .stat-blue { background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.2); }
.dark .stat-blue .stat-label { color: #93c5fd; }

.stat-green {
    background: #f0fdf4;
    border-color: #bbf7d0;
}
.stat-green .stat-label { color: #15803d; }
.dark .stat-green { background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.2); }
.dark .stat-green .stat-label { color: #86efac; }

.stat-pink {
    background: #fff1f2;
    border-color: #fecdd3;
}
.stat-pink .stat-label { color: #be123c; }
.dark .stat-pink { background: rgba(244,63,94,0.1); border-color: rgba(244,63,94,0.2); }
.dark .stat-pink .stat-label { color: #fda4af; }

/* ── Sections ── */
.section-title {
    font-size: 18px;
    font-weight: 500;
    color: #111827;
    margin-bottom: 16px;
}

.dark .section-title {
    color: #f3f4f6;
}

/* ── Date form ── */
.date-form {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
}

.field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.field-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
}

.dark .field-label {
    color: #9ca3af;
}

.date-input {
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 9px 12px;
    font-size: 14px;
    background: white;
    color: #111827;
    outline: none;
    transition: border-color 0.15s;
}

.date-input:focus {
    border-color: #6366f1;
}

.dark .date-input {
    background: #2a2f45;
    border-color: #3a3f55;
    color: #f3f4f6;
}

.btn-search {
    background: #111827;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 9px 20px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: opacity 0.15s;
}

.btn-search:hover {
    opacity: 0.85;
}

.dark .btn-search {
    background: #f3f4f6;
    color: #111827;
}

/* ── Slots ── */
.slots-section,
.next-section {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.slots-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 0;
}

@media (max-width: 480px) {
    .slots-grid { grid-template-columns: 1fr; }
}

.select-input {
    width: 100%;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    background: white;
    color: #111827;
    outline: none;
    cursor: pointer;
    transition: border-color 0.15s;
}

.select-input:focus {
    border-color: #6366f1;
}

.select-input:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

.dark .select-input {
    background: #2a2f45;
    border-color: #3a3f55;
    color: #f3f4f6;
}

.divider {
    border: none;
    border-top: 0.5px solid #e5e7eb;
    margin: 20px 0;
}

.dark .divider {
    border-color: #2e3347;
}

.btn-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #111827;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    padding: 12px 22px;
    font-size: 15px;
    font-weight: 500;
    transition: opacity 0.15s, transform 0.15s;
}

.btn-cta:hover {
    opacity: 0.87;
    transform: translateY(-1px);
}

.dark .btn-cta {
    background: #f3f4f6;
    color: #111827;
}

.btn-icon {
    width: 18px;
    height: 18px;
}

/* ── Empty state ── */
.empty-state {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff1f2;
    border: 0.5px solid #fecdd3;
    border-radius: 12px;
    padding: 16px 20px;
    color: #be123c;
    font-size: 14px;
}

.dark .empty-state {
    background: rgba(244, 63, 94, 0.1);
    border-color: rgba(244, 63, 94, 0.25);
    color: #fda4af;
}

.empty-icon {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

/* ── Next slots ── */
.next-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 16px;
}

.day-card {
    overflow: visible;
}

.day-title {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 14px;
    font-weight: 500;
    color: #6b7280;
    margin-bottom: 12px;
}

.dark .day-title {
    color: #9ca3af;
}

.day-icon {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}

.chips-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.chip {
    background: #f3f4f6;
    border: 0.5px solid #e5e7eb;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 13px;
    color: #374151;
}

.dark .chip {
    background: #2a2f45;
    border-color: #3a3f55;
    color: #d1d5db;
}

/* ── Transitions ── */
.slide-down-enter-active {
    transition: all 0.3s ease;
}
.slide-down-enter-from {
    opacity: 0;
    transform: translateY(-8px);
}

/* ── Capitalize utility ── */
.capitalize {
    text-transform: capitalize;
}
</style>