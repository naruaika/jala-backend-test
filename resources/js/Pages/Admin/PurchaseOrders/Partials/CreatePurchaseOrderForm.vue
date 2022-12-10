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
const invoiceNumberInput = ref(null);

var form = useForm({
    invoice_number: '',
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

    nextTick(() => invoiceNumberInput.value.focus());
};

const createPurchaseOrder = () => {
    form.post(route('admin.purchase-orders.store'), {
        preserveScroll: true,
        onBefore: () => form.clearErrors(),
        onSuccess: () => closeModal(),
        onError: () => {},
        onFinish: () => {},
    });
};

const closeModal = () => {
    showingModal.value = false;

    form.reset();
    form.clearErrors();
};
</script>

<template>
    <section class="mb-6">
        <PrimaryButton @click="showModal"> + Add New Purchase Order </PrimaryButton>

        <Modal :show="showingModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Add New Purchase Order
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    To avoid data duplication, please ensure that the purchase order you want to add doesn't already exist.
                </p>

                <div class="mt-6">
                    <InputLabel for="invoice-number" value="Invoice Number" />

                    <TextInput
                        id="invoice-number"
                        ref="invoiceNumberInput"
                        v-model="form.invoice_number"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder=""
                        @keyup.enter="createPurchaseOrder"
                    />

                    <InputError :message="form.errors.invoice_number" class="mt-2" />
                </div>

                <template v-for="(product, index) in form.products">
                    <h3 class="mt-6">Product #{{ index + 1 }}</h3>

                    <div class="mt-6">
                        <InputLabel for="product-sku" value="Product" />

                        <select
                            id="product-sku"
                            v-model="product.sku"
                            class="mt-1 block w-3/4"
                            :class="'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'"
                        >
                            <option value="" disabled>Please select one</option>
                            <option v-for="option in $page.props.products" :value="option.sku">
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
                        <InputLabel for="quantity" value="Quantity" />

                        <TextInput
                            id="quantity"
                            v-model="product.quantity"
                            type="number"
                            min="0"
                            max="4294967295"
                            class="mt-1 block w-3/4"
                            placeholder=""
                            @keyup.enter="createPurchaseOrder"
                        />

                        <InputError
                            :message="form.errors[`products.${index}.quantity`]"
                            :replace-from="`products.${index}.quantity`"
                            :replace-to="'quantity'"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-6">
                        <InputLabel for="price" value="Unit Price" />

                        <TextInput
                            id="price"
                            v-model="product.price"
                            type="text"
                            class="mt-1 block w-3/4"
                            placeholder=""
                            @keyup.enter="createPurchaseOrder"
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
                        @click="createPurchaseOrder"
                    >
                        Add New Purchase Order
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
