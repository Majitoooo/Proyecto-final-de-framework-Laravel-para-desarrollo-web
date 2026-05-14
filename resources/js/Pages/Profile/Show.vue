<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import SectionBorder from '@/Components/SectionBorder.vue'
import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
})
</script>

<template>
    <AppLayout title="Perfil">
        <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="relative mb-10 overflow-hidden rounded-[32px] border border-white/70 bg-white/80 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur-xl p-8">
                <div class="pointer-events-none absolute inset-0">
                    <div class="absolute -top-16 -right-12 h-52 w-52 rounded-full bg-indigo-200/40 blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 h-44 w-44 rounded-full bg-fuchsia-200/35 blur-3xl"></div>
                    <div class="absolute inset-0 bg-[linear-gradient(to_bottom_right,rgba(255,255,255,0.65),rgba(248,250,252,0.92))]"></div>
                </div>

                <div class="relative flex items-center gap-5">
                    <!-- Avatar -->
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-700 text-2xl font-bold text-white shadow-[0_8px_24px_rgba(15,23,42,0.22)]">
                        {{ $page.props.auth.user?.name?.charAt(0) }}
                    </div>
                    <div>
                        <div class="mb-1 inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-0.5 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Mi cuenta
                        </div>
                        <h1 class="text-2xl font-bold tracking-[-0.03em] text-slate-900">
                            {{ $page.props.auth.user?.name }}
                        </h1>
                        <p class="text-sm text-slate-500">
                            {{ $page.props.auth.user?.email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN: Información del perfil -->
            <div
                v-if="$page.props.jetstream.canUpdateProfileInformation"
                class="mb-6 overflow-hidden rounded-[28px] border border-white/70 bg-white/80 shadow-[0_12px_40px_rgba(15,23,42,0.06)] backdrop-blur-xl"
            >
                <div class="border-b border-slate-200/60 bg-slate-50/60 px-7 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">
                        Información personal
                    </h2>
                </div>
                <div class="p-7">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" />
                </div>
            </div>

            <!-- SECCIÓN: Contraseña -->
            <div
                v-if="$page.props.jetstream.canUpdatePassword"
                class="mb-6 overflow-hidden rounded-[28px] border border-white/70 bg-white/80 shadow-[0_12px_40px_rgba(15,23,42,0.06)] backdrop-blur-xl"
            >
                <div class="border-b border-slate-200/60 bg-slate-50/60 px-7 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">
                        Cambiar contraseña
                    </h2>
                </div>
                <div class="p-7">
                    <UpdatePasswordForm />
                </div>
            </div>

            <!-- SECCIÓN: 2FA -->
            <div
                v-if="$page.props.jetstream.canManageTwoFactorAuthentication"
                class="mb-6 overflow-hidden rounded-[28px] border border-white/70 bg-white/80 shadow-[0_12px_40px_rgba(15,23,42,0.06)] backdrop-blur-xl"
            >
                <div class="border-b border-slate-200/60 bg-slate-50/60 px-7 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">
                        Autenticación en dos pasos
                    </h2>
                </div>
                <div class="p-7">
                    <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />
                </div>
            </div>

            <!-- SECCIÓN: Sesiones -->
            <div class="mb-6 overflow-hidden rounded-[28px] border border-white/70 bg-white/80 shadow-[0_12px_40px_rgba(15,23,42,0.06)] backdrop-blur-xl">
                <div class="border-b border-slate-200/60 bg-slate-50/60 px-7 py-4">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">
                        Sesiones activas
                    </h2>
                </div>
                <div class="p-7">
                    <LogoutOtherBrowserSessionsForm :sessions="sessions" />
                </div>
            </div>

            <!-- SECCIÓN: Eliminar cuenta (zona de peligro) -->
            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                <div class="overflow-hidden rounded-[28px] border border-red-200/60 bg-red-50/40 shadow-[0_12px_40px_rgba(239,68,68,0.06)] backdrop-blur-xl">
                    <div class="border-b border-red-200/50 bg-red-50/60 px-7 py-4">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.16em] text-red-500">
                            Zona de peligro
                        </h2>
                    </div>
                    <div class="p-7">
                        <DeleteUserForm />
                    </div>
                </div>
            </template>

        </div>
    </AppLayout>
</template>