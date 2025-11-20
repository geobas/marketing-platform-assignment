<template>
    <transition name="fade">
        <div v-if="show" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50">
            <div
                class="bg-white w-full max-w-md rounded-xl shadow-2xl p-6 relative transform transition-transform duration-300 ease-out hover:scale-105">

                <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">{{ title }}</h2>
                <p class="text-gray-700 text-center">{{ message }}</p>

                <div class="flex justify-center mt-6">
                    <button @click="closeModal" type="button"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 shadow transition-colors duration-300 ease-out transform hover:scale-105 cursor-pointer">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        show: { type: Boolean, default: false },
        title: { type: String, default: "Error" },
        message: { type: String, default: "" },
    },
    methods: {
        closeModal() {
            this.$emit('close');
        }
    }
};
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    min-width: 300px;
}

.close-btn {
    background: #e5e7eb;
    /* light gray */
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s ease;
}

.close-btn:hover {
    background: #d1d5db;
    /* darker gray */
}

.close-btn:active {
    background: #9ca3af;
    /* pressed */
    color: white;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease-out;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
    opacity: 1;
}
</style>
