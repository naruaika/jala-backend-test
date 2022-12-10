<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateProductForm from './Partials/CreateProductForm.vue';
import ProductListTable from './Partials/ProductListTable.vue';
import { Head } from '@inertiajs/inertia-vue3';

defineProps(['products', 'permissions']);
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <CreateProductForm v-if="permissions.create" />

                <ProductListTable :products="products" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
