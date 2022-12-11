<script setup>
import moment from 'moment';

defineProps(['saleOrders']);
</script>

<template>
    <div class="overflow-x-auto relative shadow-sm sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        Customer Name
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Total Cost
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Status
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Created At
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-if="saleOrders.length === 0"
                    class="bg-white"
                >
                    <td colspan="4" class="py-4 px-6 text-center">
                        No data.
                    </td>
                </tr>
                <tr
                    v-else
                    v-for="(saleOrder, index) in saleOrders"
                    :key="saleOrder.id"
                    :class="{'border-b': index < saleOrders.length - 1}"
                    class="bg-white hover:bg-gray-50"
                >
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                        {{ saleOrder.customer_name }}
                    </th>
                    <td class="py-4 px-6">
                        Rp {{ Number(saleOrder.price).toLocaleString() }}
                    </td>
                    <td class="py-4 px-6">
                        <span
                            class="text-xs font-semibold px-2.5 py-0.5 rounded"
                            :class="saleOrder.status === 'pending'
                                ? 'bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900'
                                : 'bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900'"
                        >
                            {{ saleOrder.status.charAt(0).toUpperCase() + saleOrder.status.slice(1) }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        {{ moment(saleOrder.created_at).format('DD/MM/YYYY HH:mm:ss') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
