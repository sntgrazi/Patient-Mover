<template>
  <nav class="navbar">
    <div class="profile">
      <button class="menu-button" @click="$emit('toggleSidebar')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <span class="user-name">{{ user.name }}</span>
      <img :src="user.profilePicture" alt="Profile Picture" class="profile-picture" />
    </div>
  </nav>
</template>

<script>
export default {
  name: "NavBarComponent",
  data() {
    return {
      user: {
        name: "John Doe",
        profilePicture: "https://via.placeholder.com/50" // Substitua pela URL da foto do perfil do usuário
      }
    };
  }
};
</script>

<style scoped>
.navbar {
  display: flex;
  align-items: center;
  justify-content: end;
  padding: 20px;
}

.profile {
  display: flex;
  align-items: center;
  gap: 20px;
}

.profile-picture {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}

.user-name {
  font-size: 18px;
  color: #fff; /* Cor do texto */
}

.menu-button {
  display: none;
  background-color: transparent;
  color: #fff;
  border: none;
  padding: 10px;
  font-size: 24px;
  border-radius: 5px;
  cursor: pointer;
}

@media (max-width: 1200px) {
  .menu-button {
    display: block;
  }
}
</style>
