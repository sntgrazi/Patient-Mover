<template>
  <div class="custom-card">
    <div class="custom-card-conteudo">
      <div class="custom-card-content">
        <span>{{ quantity }}</span>
      </div>
      <div class="custom-card-title">
        <span>{{ title }}</span>
      </div>
    </div>
    <div class="custom-card-icon">
      <span><i :class="`fa-solid ${icon}`"></i></span>
    </div>
  </div>
</template>

<script>
export default {
  name: "Card",
  props: {
    title: {
      type: String,
      required: true,
    },
    quantity: {
      type: Number,
      required: true,
    },
    icon: {
      type: String,
      required: true,
    },
  },
};
</script>

<style>
.custom-card {
  margin-top: 20px;
  width: 100%;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 15px;
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.custom-card-conteudo {
  flex-grow: 1;
  color: #000;
  padding: 10px;
}

.custom-card-content {
  font-size: 26px;
  font-weight: bold;
  margin-bottom: 10px;
}

.custom-card-title {
  font-size: 16px;
}

.custom-card-icon {
  font-size: 50px;
  color: #040745;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px;
}
</style>
