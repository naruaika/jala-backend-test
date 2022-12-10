<script setup>
defineProps(['products']);
</script>

<template>
    <div class="overflow-x-auto relative shadow-sm sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6">
                        SKU
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Name
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Unit Price
                    </th>
                    <th scope="col" class="py-3 px-6">
                        Stock
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-if="products.length === 0"
                    class="bg-white"
                >
                    <td colspan="4" class="py-4 px-6 text-center">
                        No data.
                    </td>
                </tr>
                <tr
                    v-else
                    v-for="(product, index) in products"
                    :key="product.id"
                    :class="{'border-b': index < products.length - 1}"
                    class="bg-white hover:bg-gray-50"
                >
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                        {{ product.sku }}
                    </th>
                    <td class="py-4 px-6">
                        {{ product.name }}
                    </td>
                    <td class="py-4 px-6">
                        Rp {{ Number(product.price).toLocaleString() }}
                    </td>
                    <td class="py-4 px-6">
                        {{ product.stock }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
