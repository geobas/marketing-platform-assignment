<template>
    <transition name="fade">
        <div v-if="localShow" class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50">
            <div
                class="bg-white w-full max-w-md rounded-xl shadow-2xl p-6 relative transform transition-transform duration-300 ease-out">

                <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">
                    Join Us
                </h2>

                <form @submit.prevent="submitForm" class="space-y-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input v-model="form.full_name" type="text" required
                            class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input v-model="form.email" type="email" required
                            class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>

                    <div class="flex items-start space-x-2">
                        <input id="consent" type="checkbox" v-model="form.consent"
                            class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="consent" class="text-sm text-gray-700">
                            I agree to receive marketing emails.
                        </label>
                    </div>

                    <div class="flex justify-between pt-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow transition-colors duration-300 ease-out transform hover:scale-105 cursor-pointer">
                            Submit
                        </button>

                        <button type="button" @click="fadeOutModal"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors duration-300 ease-out transform hover:scale-105 cursor-pointer">
                            Cancel
                        </button>
                    </div>

                </form>

            </div>

            <!-- Error modal -->
            <MessageModal :show="showError" :title="errorTitle" :message="errorMessage" @close="showError = false" />
        </div>
    </transition>
</template>

<script>
import MessageModal from "./MessageModal.vue";

export default {
    components: { MessageModal },

    props: {
        show: { type: Boolean, default: false }
    },

    data() {
        return {
            localShow: this.show,
            form: {
                full_name: "",
                email: "",
                consent: false
            },
            showError: false,
            errorTitle: "Error",
            errorMessage: ""
        };
    },

    watch: {
        show(newVal) {
            this.localShow = newVal;
        }
    },

    methods: {
        fadeOutModal() {
            this.localShow = false;
            setTimeout(() => this.$emit("close"), 300); // wait for fade animation
        },

        resetForm() {
            this.form = {
                full_name: "",
                email: "",
                consent: false
            };
        },

        showErrorModal(title, message) {
            this.errorTitle = title;
            this.errorMessage = message;
            this.showError = true;
        },

        async submitForm() {
            try {
                await axios.post("/api/leads", this.form);

                // Emit success to parent
                this.$emit("Success", "Thank you! Your information has been submitted.");

                // Clear the form
                this.resetForm();

                // Fade out modal
                this.fadeOutModal();
            } catch (error) {
                if (error.response) {
                    if (error.response.status === 422) {
                        const errors = Object.values(error.response.data.errors).flat();
                        this.showErrorModal("Validation Error", errors.join("\n"));
                    } else {
                        this.showErrorModal(
                            "Server Error",
                            error.response.data.message || "An unexpected error occurred."
                        );
                    }
                } else {
                    this.showErrorModal("Network Error", "Please check your internet connection.");
                }
            }
        }
    }
};
</script>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.25s ease-out;
}

/* Fade transition for the modal wrapper */
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
