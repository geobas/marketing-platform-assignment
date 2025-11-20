<template>
    <div v-if="show" class="modal-backdrop">
        <div class="modal">
            <h2>{{ title }}</h2>
            <p>{{ message }}</p>
            <br>
            <button @click="$emit('close')" type="button" class="close-btn">
                Close
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: { type: Boolean, default: false },
        title: { type: String, default: "Error" },
        message: { type: String, default: "" },
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
</style>
