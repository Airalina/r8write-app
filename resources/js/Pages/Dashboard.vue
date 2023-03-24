<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-blue border-b border-slate-200">
                        <ApplicationLogo class="block h-12 w-auto" />
                        <h1 class="mt-8 text-2xl font-medium text-slate-900">
                            Sistema de gestion de cotizaciones
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
