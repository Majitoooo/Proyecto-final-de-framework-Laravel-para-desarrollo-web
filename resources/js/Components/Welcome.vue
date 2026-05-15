<script setup>
import { ref } from 'vue';

const features = [
    {
        icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        title: 'Reserva en segundos',
        desc: 'Consulta disponibilidad en tiempo real y reserva tu puesto con solo unos clics, sin llamadas ni filas.',
    },
    {
        icon: 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
        title: 'Espacios variados',
        desc: 'Desde cubículos individuales hasta salas de reuniones y auditorios. Encuentra el ambiente ideal para tu trabajo.',
    },
    {
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        title: 'Confirmación inmediata',
        desc: 'Recibe un correo de confirmación al instante. El administrador gestiona aprobaciones desde el panel central.',
    },
    {
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        title: 'Horarios flexibles',
        desc: 'Cada espacio tiene su propia disponibilidad semanal. Elige el bloque horario que mejor se adapte a tu agenda.',
    },
    {
        icon: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        title: 'Precio transparente',
        desc: 'Ve el costo por hora antes de confirmar. Sin sorpresas, sin cargos ocultos.',
    },
    {
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        title: 'Historial de reservas',
        desc: 'Consulta tus reservas pasadas y próximas. Cancela o modifica con total transparencia.',
    },
];

const steps = [
    { num: '01', label: 'Explora', desc: 'Navega el catálogo de espacios disponibles y filtra por tipo, capacidad o precio.' },
    { num: '02', label: 'Elige fecha', desc: 'Selecciona el día y la franja horaria que necesitas. El sistema valida disponibilidad en tiempo real.' },
    { num: '03', label: 'Reserva', desc: 'Ingresa tus datos básicos y envía la solicitud. Recibirás un correo de confirmación al instante.' },
    { num: '04', label: 'Disfruta', desc: 'Llega al espacio y trabaja. El panel administrativo gestiona todo lo demás.' },
];

const spaceTypes = ['Cubículo individual', 'Sala de reuniones', 'Auditorio', 'Laboratorio', 'Sala de videoconferencia', 'Zona abierta'];

const activeType = ref(null);
</script>

<template>
    <div class="cw-root">

        <!-- ───── HERO ───── -->
        <section class="hero">
            <div class="hero-bg-grid" aria-hidden="true"></div>
            <div class="hero-accent" aria-hidden="true"></div>

            <div class="container">
                <div class="hero-eyebrow">
                    <span class="dot"></span>
                    <span>Sistema de reservas · Espacios de trabajo</span>
                </div>

                <h1 class="hero-title">
                    Tu espacio ideal,<br>
                    <em>reservado en minutos</em>
                </h1>

                <p class="hero-sub">
                    Consulta disponibilidad, reserva salas, cubículos y auditorios, y gestiona todo
                    desde un solo lugar. Sin llamadas, sin papeles.
                </p>

                <div class="hero-actions">
                    <a href="/spaces" class="btn-primary">
                        Ver espacios disponibles
                        <svg viewBox="0 0 20 20" fill="currentColor" class="btn-icon"><path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="/login" class="btn-ghost">Panel administrativo</a>
                </div>

                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-num">3+</span>
                        <span class="stat-label">Espacios activos</span>
                    </div>
                    <div class="stat-divider" aria-hidden="true"></div>
                    <div class="stat">
                        <span class="stat-num">60 min</span>
                        <span class="stat-label">Duración mínima</span>
                    </div>
                    <div class="stat-divider" aria-hidden="true"></div>
                    <div class="stat">
                        <span class="stat-num">24/7</span>
                        <span class="stat-label">Consulta disponibilidad</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───── SPACE TYPES STRIP ───── -->
        <section class="types-strip">
            <div class="container">
                <p class="types-label">Tipos de espacios disponibles</p>
                <div class="types-list">
                    <button
                        v-for="t in spaceTypes"
                        :key="t"
                        class="type-chip"
                        :class="{ active: activeType === t }"
                        @click="activeType = activeType === t ? null : t"
                    >{{ t }}</button>
                </div>
            </div>
        </section>

        <!-- ───── HOW IT WORKS ───── -->
        <section class="section">
            <div class="container">
                <div class="section-header">
                    <p class="section-tag">¿Cómo funciona?</p>
                    <h2 class="section-title">Reservar es simple</h2>
                </div>
                <div class="steps-grid">
                    <div v-for="s in steps" :key="s.num" class="step-card">
                        <span class="step-num">{{ s.num }}</span>
                        <h3 class="step-title">{{ s.label }}</h3>
                        <p class="step-desc">{{ s.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───── FEATURES ───── -->
        <section class="section section-alt">
            <div class="container">
                <div class="section-header">
                    <p class="section-tag">Características</p>
                    <h2 class="section-title">Todo lo que necesitas</h2>
                    <p class="section-sub">Una plataforma pensada para usuarios y administradores, con flujos claros y notificaciones automáticas.</p>
                </div>
                <div class="features-grid">
                    <div v-for="f in features" :key="f.title" class="feature-card">
                        <div class="feature-icon-wrap">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="feature-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="f.icon"/>
                            </svg>
                        </div>
                        <h3 class="feature-title">{{ f.title }}</h3>
                        <p class="feature-desc">{{ f.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───── ADMIN PANEL HIGHLIGHT ───── -->
        <section class="section">
            <div class="container">
                <div class="admin-panel-row">
                    <div class="admin-text">
                        <p class="section-tag">Panel administrativo</p>
                        <h2 class="section-title">Control total desde un solo lugar</h2>
                        <p class="admin-desc">
                            Gestiona espacios, horarios, bloqueos y solicitudes de reserva con una interfaz clara
                            y protegida. Aprueba, rechaza o cancela reservas y el sistema notifica automáticamente al usuario por correo.
                        </p>
                        <ul class="admin-list">
                            <li>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="check-icon"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                CRUD completo de espacios con slug
                            </li>
                            <li>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="check-icon"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Calendario semanal con reservas y bloqueos
                            </li>
                            <li>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="check-icon"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Flujo de estados: pendiente → confirmada → finalizada
                            </li>
                            <li>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="check-icon"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Notificaciones por correo en cada cambio de estado
                            </li>
                            <li>
                                <svg viewBox="0 0 20 20" fill="currentColor" class="check-icon"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Bloqueo manual de horarios por mantenimiento
                            </li>
                        </ul>
                        <a href="/login" class="btn-primary" style="display:inline-flex; margin-top:2rem;">
                            Acceder al panel
                            <svg viewBox="0 0 20 20" fill="currentColor" class="btn-icon"><path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </a>
                    </div>

                    <div class="admin-visual" aria-hidden="true">
                        <div class="mock-panel">
                            <div class="mock-header">
                                <span class="mock-dot red"></span>
                                <span class="mock-dot amber"></span>
                                <span class="mock-dot green"></span>
                                <span class="mock-title">Panel · Reservas</span>
                            </div>
                            <div class="mock-body">
                                <div class="mock-stat-row">
                                    <div class="mock-stat pending">
                                        <span class="ms-num">5</span>
                                        <span class="ms-lbl">Pendientes</span>
                                    </div>
                                    <div class="mock-stat confirmed">
                                        <span class="ms-num">12</span>
                                        <span class="ms-lbl">Confirmadas</span>
                                    </div>
                                    <div class="mock-stat done">
                                        <span class="ms-num">38</span>
                                        <span class="ms-lbl">Finalizadas</span>
                                    </div>
                                </div>
                                <div class="mock-table">
                                    <div class="mock-row header-row">
                                        <span>Espacio</span><span>Horario</span><span>Estado</span>
                                    </div>
                                    <div class="mock-row">
                                        <span>Sala A</span><span>10:00–11:00</span>
                                        <span class="badge pending">Pendiente</span>
                                    </div>
                                    <div class="mock-row">
                                        <span>Cubículo 3</span><span>14:00–16:00</span>
                                        <span class="badge confirmed">Confirmada</span>
                                    </div>
                                    <div class="mock-row">
                                        <span>Auditorio</span><span>09:00–12:00</span>
                                        <span class="badge confirmed">Confirmada</span>
                                    </div>
                                    <div class="mock-row">
                                        <span>Lab. Digital</span><span>15:00–17:00</span>
                                        <span class="badge rejected">Rechazada</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ───── CTA ───── -->
        <section class="cta-section">
            <div class="cta-bg-accent" aria-hidden="true"></div>
            <div class="container cta-inner">
                <h2 class="cta-title">¿Listo para reservar tu espacio?</h2>
                <p class="cta-sub">Explora los espacios disponibles, elige tu horario y confirma en segundos.</p>
                <a href="/spaces" class="btn-cta">
                    Explorar espacios ahora
                    <svg viewBox="0 0 20 20" fill="currentColor" class="btn-icon"><path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </a>
            </div>
        </section>

        <!-- ───── FOOTER ───── -->
        <footer class="cw-footer">
            <div class="container footer-inner">
                <span class="footer-brand">CoWork Reservas</span>
                <span class="footer-copy">Desarrollado con Laravel 12 · Jetstream · Inertia · Vue</span>
            </div>
        </footer>

    </div>
</template>

<style scoped>
/* ── Variables ─────────────────────────────── */
:root {
    --teal-50: #e1f5ee;
    --teal-100: #9fe1cb;
    --teal-400: #1d9e75;
    --teal-600: #0f6e56;
    --teal-800: #085041;
    --teal-900: #04342c;
    --amber-50: #faeeda;
    --amber-100: #fac775;
    --amber-400: #ba7517;
    --amber-600: #854f0b;
    --gray-50: #f1efe8;
    --gray-100: #d3d1c7;
    --gray-200: #b4b2a9;
    --gray-400: #888780;
    --gray-700: #444441;
    --gray-900: #2c2c2a;
}

/* ── Reset ────────────────────────────────── */
.cw-root * { box-sizing: border-box; margin: 0; padding: 0; }
.cw-root { font-family: 'DM Sans', 'Segoe UI', sans-serif; color: var(--gray-900); background: #fafaf8; line-height: 1.6; }
.container { max-width: 1120px; margin: 0 auto; padding: 0 1.5rem; }

/* ── Hero ─────────────────────────────────── */
.hero { position: relative; overflow: hidden; padding: 6rem 0 5rem; background: var(--teal-900); color: #fff; }
.hero-bg-grid {
    position: absolute; inset: 0; opacity: .04;
    background-image: linear-gradient(rgba(255,255,255,.5) 1px, transparent 1px),
                      linear-gradient(90deg, rgba(255,255,255,.5) 1px, transparent 1px);
    background-size: 40px 40px;
}
.hero-accent {
    position: absolute; top: -160px; right: -120px;
    width: 520px; height: 520px; border-radius: 50%;
    background: var(--teal-600); opacity: .25;
}
.hero-eyebrow { display: flex; align-items: center; gap: .5rem; font-size: .8rem; letter-spacing: .12em; text-transform: uppercase; color: var(--teal-100); margin-bottom: 1.5rem; }
.dot { width: 6px; height: 6px; border-radius: 50%; background: var(--amber-100); flex-shrink: 0; }
.hero-title { font-size: clamp(2.4rem, 5vw, 3.6rem); font-weight: 700; line-height: 1.1; letter-spacing: -.02em; color: #fff; max-width: 640px; margin-bottom: 1.25rem; }
.hero-title em { font-style: normal; color: var(--amber-100); }
.hero-sub { font-size: 1.1rem; color: var(--teal-100); max-width: 540px; margin-bottom: 2.5rem; line-height: 1.7; }
.hero-actions { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 3.5rem; }

/* Buttons */
.btn-primary {
    display: inline-flex; align-items: center; gap: .4rem;
    background: var(--amber-100); color: var(--amber-600);
    font-size: .9rem; font-weight: 600; padding: .75rem 1.5rem;
    border-radius: 6px; text-decoration: none; transition: background .15s, transform .1s;
}
.btn-primary:hover { background: #fff; transform: translateY(-1px); }
.btn-ghost {
    display: inline-flex; align-items: center; gap: .4rem;
    color: var(--teal-100); font-size: .9rem; font-weight: 500;
    padding: .75rem 1.25rem; border: 1px solid rgba(255,255,255,.2);
    border-radius: 6px; text-decoration: none; transition: border-color .15s, color .15s;
}
.btn-ghost:hover { border-color: rgba(255,255,255,.5); color: #fff; }
.btn-icon { width: 16px; height: 16px; flex-shrink: 0; }

/* Stats */
.hero-stats { display: flex; align-items: center; gap: 2rem; flex-wrap: wrap; }
.stat { display: flex; flex-direction: column; gap: .2rem; }
.stat-num { font-size: 1.6rem; font-weight: 700; color: #fff; letter-spacing: -.02em; }
.stat-label { font-size: .78rem; color: var(--teal-100); text-transform: uppercase; letter-spacing: .08em; }
.stat-divider { width: 1px; height: 36px; background: rgba(255,255,255,.15); }

/* ── Types strip ────────────────────────── */
.types-strip { background: var(--teal-800); padding: 1.25rem 0; border-bottom: 1px solid var(--teal-900); }
.types-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .1em; color: var(--teal-100); margin-bottom: .75rem; }
.types-list { display: flex; flex-wrap: wrap; gap: .5rem; }
.type-chip {
    font-size: .82rem; padding: .35rem .9rem; border-radius: 100px;
    border: 1px solid rgba(255,255,255,.18); color: var(--teal-50);
    background: transparent; cursor: pointer; transition: all .15s;
}
.type-chip:hover { background: rgba(255,255,255,.1); }
.type-chip.active { background: var(--amber-100); color: var(--amber-600); border-color: var(--amber-100); font-weight: 600; }

/* ── Sections ───────────────────────────── */
.section { padding: 5rem 0; }
.section-alt { background: var(--gray-50); }
.section-header { text-align: center; margin-bottom: 3.5rem; }
.section-tag { font-size: .78rem; text-transform: uppercase; letter-spacing: .12em; color: var(--teal-400); font-weight: 600; margin-bottom: .5rem; }
.section-title { font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 700; letter-spacing: -.02em; color: var(--gray-900); margin-bottom: .75rem; }
.section-sub { font-size: 1rem; color: var(--gray-400); max-width: 540px; margin: 0 auto; }

/* ── Steps ──────────────────────────────── */
.steps-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
.step-card { padding: 2rem 1.5rem; border: 1px solid var(--gray-100); border-radius: 10px; background: #fff; transition: box-shadow .2s; }
.step-card:hover { box-shadow: 0 4px 20px rgba(4,52,44,.08); }
.step-num { font-size: 2.5rem; font-weight: 800; color: var(--teal-50); letter-spacing: -.04em; display: block; margin-bottom: .5rem; }
.step-title { font-size: 1.05rem; font-weight: 700; color: var(--teal-900); margin-bottom: .5rem; }
.step-desc { font-size: .88rem; color: var(--gray-400); line-height: 1.65; }

/* ── Features ───────────────────────────── */
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
.feature-card { display: flex; flex-direction: column; gap: .75rem; padding: 1.75rem; background: #fff; border: 1px solid var(--gray-100); border-radius: 10px; transition: border-color .2s; }
.feature-card:hover { border-color: var(--teal-100); }
.feature-icon-wrap { width: 44px; height: 44px; border-radius: 8px; background: var(--teal-50); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.feature-icon { width: 22px; height: 22px; color: var(--teal-600); }
.feature-title { font-size: .97rem; font-weight: 700; color: var(--gray-900); }
.feature-desc { font-size: .86rem; color: var(--gray-400); line-height: 1.65; }

/* ── Admin panel section ────────────────── */
.admin-panel-row { display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: center; }
@media (max-width: 768px) { .admin-panel-row { grid-template-columns: 1fr; } }
.admin-text .section-title { text-align: left; }
.admin-desc { font-size: .95rem; color: var(--gray-400); line-height: 1.75; margin: 1rem 0 1.5rem; }
.admin-list { list-style: none; display: flex; flex-direction: column; gap: .75rem; }
.admin-list li { display: flex; align-items: flex-start; gap: .6rem; font-size: .9rem; color: var(--gray-700); }
.check-icon { width: 16px; height: 16px; color: var(--teal-400); flex-shrink: 0; margin-top: 3px; }

/* Mock panel UI */
.mock-panel { background: #fff; border: 1px solid var(--gray-100); border-radius: 12px; overflow: hidden; box-shadow: 0 8px 32px rgba(4,52,44,.1); }
.mock-header { display: flex; align-items: center; gap: .4rem; padding: .75rem 1rem; background: var(--gray-50); border-bottom: 1px solid var(--gray-100); }
.mock-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.mock-dot.red { background: #ff5f57; }
.mock-dot.amber { background: #ffbd2e; }
.mock-dot.green { background: #28c840; }
.mock-title { font-size: .78rem; color: var(--gray-400); margin-left: auto; }
.mock-body { padding: 1rem; }
.mock-stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; margin-bottom: 1rem; }
.mock-stat { border-radius: 8px; padding: .75rem; display: flex; flex-direction: column; align-items: center; gap: .2rem; }
.mock-stat.pending { background: #fef3c7; }
.mock-stat.confirmed { background: var(--teal-50); }
.mock-stat.done { background: var(--gray-50); }
.ms-num { font-size: 1.4rem; font-weight: 700; }
.mock-stat.pending .ms-num { color: #92400e; }
.mock-stat.confirmed .ms-num { color: var(--teal-600); }
.mock-stat.done .ms-num { color: var(--gray-400); }
.ms-lbl { font-size: .68rem; text-transform: uppercase; letter-spacing: .06em; }
.mock-stat.pending .ms-lbl { color: #b45309; }
.mock-stat.confirmed .ms-lbl { color: var(--teal-400); }
.mock-stat.done .ms-lbl { color: var(--gray-400); }
.mock-table { font-size: .78rem; }
.mock-row { display: grid; grid-template-columns: 1.2fr 1.2fr .9fr; gap: .5rem; padding: .5rem .25rem; border-bottom: 1px solid var(--gray-50); align-items: center; }
.mock-row:last-child { border-bottom: none; }
.header-row { font-weight: 600; color: var(--gray-400); font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; }
.badge { display: inline-block; padding: .2rem .55rem; border-radius: 100px; font-size: .7rem; font-weight: 600; text-align: center; }
.badge.pending { background: #fef3c7; color: #92400e; }
.badge.confirmed { background: var(--teal-50); color: var(--teal-600); }
.badge.rejected { background: #fee2e2; color: #991b1b; }

/* ── CTA ────────────────────────────────── */
.cta-section { position: relative; overflow: hidden; background: var(--teal-600); padding: 5rem 0; text-align: center; }
.cta-bg-accent { position: absolute; bottom: -100px; left: -80px; width: 320px; height: 320px; border-radius: 50%; background: var(--teal-800); opacity: .4; }
.cta-inner { position: relative; z-index: 1; }
.cta-title { font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 700; color: #fff; letter-spacing: -.02em; margin-bottom: .75rem; }
.cta-sub { color: var(--teal-100); font-size: 1rem; margin-bottom: 2rem; }
.btn-cta {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #fff; color: var(--teal-600);
    font-size: .95rem; font-weight: 700; padding: .9rem 2rem;
    border-radius: 6px; text-decoration: none; transition: background .15s, transform .1s;
}
.btn-cta:hover { background: var(--amber-50); transform: translateY(-1px); }

/* ── Footer ─────────────────────────────── */
.cw-footer { background: var(--teal-900); padding: 1.5rem 0; }
.footer-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.footer-brand { font-size: .9rem; font-weight: 700; color: #fff; }
.footer-copy { font-size: .78rem; color: var(--teal-100); }

/* ── Responsive ─────────────────────────── */
@media (max-width: 640px) {
    .hero { padding: 4rem 0 3.5rem; }
    .hero-stats { gap: 1.25rem; }
    .stat-divider { display: none; }
    .section { padding: 3.5rem 0; }
    .steps-grid, .features-grid { grid-template-columns: 1fr; }
}
</style>