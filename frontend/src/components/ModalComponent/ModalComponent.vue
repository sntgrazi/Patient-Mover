<template>
  <div class="modal-backdrop">
    <div class="modal-content">
      <div class="modal-header">
        <slot name="header"></slot>
        <button @click="closeModal">&times;</button>
      </div>
      <div class="modal-body">
        <slot name="body"></slot>
      </div>
    </div>
  </div>
</template>
  
  <script>
export default {
  name: "Modal",
  methods: {
    closeModal() {
      this.$emit("close");
    },
  },
};
</script>
  
<style scoped>

  
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  width: 80%;
  max-width: 800px;
  max-height: 70vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

button {
  background: none;
  border: none;
  font-size: 1.8rem;
  cursor: pointer;
  transition: transform 0.3s ease;
}

button:hover {
  transform: scale(1.3);
}
</style>
  