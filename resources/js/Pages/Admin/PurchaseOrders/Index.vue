<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreatePurchaseOrderForm from './Partials/CreatePurchaseOrderForm.vue';
import PurchaseOrderListTable from './Partials/PurchaseOrderListTable.vue';
import { Head } from '@inertiajs/inertia-vue3';

defineProps(['products', 'purchaseOrders', 'permissions']);
</script>

<template>
    <Head title="Purchase Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Purchase Orders</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <CreatePurchaseOrderForm v-show="permissions.create" :products="products" />

                <PurchaseOrderListTable :purchase-orders="purchaseOrders" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
