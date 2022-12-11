<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateSaleOrderForm from './Partials/CreateSaleOrderForm.vue';
import SaleOrderListTable from './Partials/SaleOrderListTable.vue';
import { Head } from '@inertiajs/inertia-vue3';

defineProps(['products', 'saleOrders', 'permissions']);
</script>

<template>
    <Head title="Sale Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sale Orders</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <CreateSaleOrderForm v-show="permissions.create" :products="products" />

                <SaleOrderListTable :sale-orders="saleOrders" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
