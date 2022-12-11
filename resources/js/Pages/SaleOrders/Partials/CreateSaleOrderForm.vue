<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { nextTick, ref } from 'vue';

defineProps(['products']);

const showingModal = ref(false);
const customerNameInput = ref(null);

var form = useForm({
    customer_name: '',
    products: [],
});

const addNewProductForm = () => {
    form.products.push({
        sku: '',
        quantity: '',
        price: '',
    });
}

const removeProductForm = (product) => {
    const index = form.products.indexOf(product);
    if (index > -1) {
        form.products.splice(index, 1);
    }
}

const showModal = () => {
    showingModal.value = true;

    addNewProductForm();

    nextTick(() => customerNameInput.value.focus());
};

const createSaleOrder = () => {
    form.post(route('sale-orders.store'), {
        preserveScroll: true,
        onBefore: () => form.clearErrors(),
        onSuccess: () => closeModal(),
        onError: () => {},
        onFinish: () => {},
    });
};

const changeProduct = (index, product) => {
    document.getElementById(`quantity-${index}`).max = product.stock;
    form.products[index].quantity = '';
    form.products[index].price = product.price;
}

const closeModal = () => {
    showingModal.value = false;

    form.reset();
    form.clearErrors();
};
</script>

<template>
    <section class="mb-6">
        <PrimaryButton @click="showModal"> + Add New Sale Order </PrimaryButton>

        <Modal :show="showingModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Add New Sale Order
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    To avoid data duplication, please ensure that the sale order you want to add doesn't already exist.
                </p>

                <div class="mt-6">
                    <InputLabel for="customer-name" value="Customer Name" />

                    <TextInput
                        id="customer-name"
                        ref="customerNameInput"
                        v-model="form.customer_name"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder=""
                        @keyup.enter="createSaleOrder"
                    />

                    <InputError :message="form.errors.customer_name" class="mt-2" />
                </div>

                <template v-for="(product, index) in form.products">
                    <h3 class="mt-6">Product #{{ index + 1 }}</h3>

                    <div class="mt-6">
                        <InputLabel :for="'product-sku-' + index" value="Product" />

                        <select
                            :id="'product-sku-' + index"
                            v-model="product.sku"
                            class="mt-1 block w-3/4"
                            :class="'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'"
                        >
                            <option value="" disabled>Please select one</option>
                            <option
                                v-for="option in $page.props.products"
                                :value="option.sku"
                                @click="changeProduct(index, option)"
                            >
                                {{ option.sku }} {{ option.name }}
                            </option>
                        </select>

                        <InputError
                            :message="form.errors[`products.${index}.sku`]"
                            :replace-from="`products.${index}.sku`"
                            :replace-to="'product'"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6">
                        <InputLabel :for="'quantity-' + index" value="Quantity" />

                        <TextInput
                            :id="'quantity-' + index"
                            v-model.number="product.quantity"
                            type="number"
                            min="0"
                            max="4294967295"
                            class="mt-1 block w-3/4"
                            placeholder=""
                            @keyup.enter="createSaleOrder"
                        />

                        <InputError
                            :message="form.errors[`products.${index}.quantity`]"
                            :replace-from="`products.${index}.quantity`"
                            :replace-to="'quantity'"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6">
                        <InputLabel :for="'price-' + index" value="Unit Price" />

                        <TextInput
                            :id="'price-' + index"
                            v-model.number="product.price"
                            type="text"
                            class="mt-1 block w-3/4"
                            placeholder=""
                            @keyup.enter="createSaleOrder"
                        />

                        <InputError
                            :message="form.errors[`products.${index}.price`]"
                            :replace-from="`products.${index}.price`"
                            :replace-to="'price'"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6 flex space-x-3">
                        <SecondaryButton
                            v-if="form.products.length > 1"
                            @click="removeProductForm(product)"
                        >
                            Remove
                        </SecondaryButton>
                        <SecondaryButton
                            v-if="index == form.products.length - 1"
                            @click="addNewProductForm"
                        >
                            Add New Product
                        </SecondaryButton>
                    </div>
                </template>

                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="createSaleOrder"
                    >
                        Add New Sale Order
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
