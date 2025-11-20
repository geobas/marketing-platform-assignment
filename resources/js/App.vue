<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
        <div class="text-center space-x-4">

            <!-- Subscribe button -->
            <button @click="showModal = true"
                class="px-6 py-3 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-md transition">
                Subscribe
            </button>

            <!-- List Leads button -->
            <button @click="goToLeads"
                class="px-6 py-3 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium shadow-md transition">
                List Leads
            </button>

        </div>

        <!-- Lead capture modal -->
        <LeadCaptureModal :show="showModal" @close="showModal = false" @success="showSuccess($event)" />

        <!-- Success modal -->
        <MessageModal :show="showSuccessModal" title="Success" :message="successMessage"
            @close="showSuccessModal = false" />

        <!-- Leads list modal -->
        <MessageModal :show="showLeadsModal" title="Leads List" :message="leadsMessage"
            @close="showLeadsModal = false" />
    </div>
</template>

<script>
import LeadCaptureModal from "./LeadCaptureModal.vue";
import MessageModal from "./MessageModal.vue";

export default {
    components: { LeadCaptureModal, MessageModal },

    data() {
        return {
            showModal: false,
            showSuccessModal: false,
            successMessage: ""
        };
    },

    methods: {
        showSuccess(message) {
            this.successMessage = message;
            this.showSuccessModal = true;
        },

        goToLeads() {
            window.location.href = "/leads";
        }
    }
};
</script>
