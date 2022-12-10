<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import { nextTick, ref } from 'vue';

const showingModal = ref(false);
const skuInput = ref(null);

const form = useForm({
    sku: '',
    name: '',
    price: '',
});

const showModal = () => {
    showingModal.value = true;

    nextTick(() => skuInput.value.focus());
};

const createProduct = () => {
    form.post(route('admin.products.store'), {
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
        <PrimaryButton @click="showModal"> + Add New Product </PrimaryButton>

        <Modal :show="showingModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Add New Product
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    To avoid data duplication, please ensure that the product you want to add doesn't already exist.
                </p>

                <div class="mt-6">
                    <InputLabel for="sku" value="Stock Keeping Unit (SKU)" />

                    <TextInput
                        id="sku"
                        ref="skuInput"
                        v-model="form.sku"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder=""
                        @keyup.enter="createProduct"
                    />

                    <InputError :message="form.errors.sku" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel for="name" value="Name" />

                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder=""
                        @keyup.enter="createProduct"
                    />

                    <InputError :message="form.errors.name" class="mt-2" />
                </div>

                <div class="mt-6">
                    <InputLabel for="price" value="Unit Price" />

                    <TextInput
                        id="price"
                        v-model="form.price"
                        type="text"
                        class="mt-1 block w-3/4"
                        placeholder=""
                        @keyup.enter="createProduct"
                    />

                    <InputError :message="form.errors.price" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <PrimaryButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="createProduct"
                    >
                        Add New Product
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
