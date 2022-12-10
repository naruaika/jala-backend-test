<script setup>
import moment from 'moment';

defineProps(['purchaseOrders']);
</script>

<template>
    <div class="overflow-x-auto relative shadow-sm sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        Invoice No.
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Total Cost
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Created At
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-if="purchaseOrders.length === 0"
                    class="bg-white"
                >
                    <td colspan="3" class="py-4 px-6 text-center">
                        No data.
                    </td>
                </tr>
                <tr
                    v-else
                    v-for="(purchaseOrder, index) in purchaseOrders"
                    :key="purchaseOrder.id"
                    :class="{'border-b': index < purchaseOrders.length - 1}"
                    class="bg-white hover:bg-gray-50"
                >
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                        {{ purchaseOrder.invoice_number }}
                    </th>
                    <td class="py-4 px-6">
                        Rp {{ purchaseOrder.price }}
                    </td>
                    <td class="py-4 px-6">
                        {{ moment(purchaseOrder.created_at).format('Do MMM YYYY') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
