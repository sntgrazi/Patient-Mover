<template>
  <nav class="navbar">
    <div class="profile">
      <button class="menu-button" @click="$emit('toggleSidebar')">
        <i class="fa-solid fa-bars"></i>
      </button>
      <span class="user-name">{{ user.nome }}</span>
      <img
        :src="user.profilePicture"
        alt="Profile Picture"
        class="profile-picture"
        @click="toggleDropdown"
      />
      <div v-if="showDropdown" class="custom-dropdown-menu">
        <ul>
          <li><a href="#" @click="goToProfile">Meu perfil</a></li>
          <li><a href="#" @click="logout">Sair</a></li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import Swal from "sweetalert2";
import authService from "@/services/authService";

export default {
  name: "NavBarComponent",
  data() {
    return {
      user: {
        nome: localStorage.getItem("userNome") || "Usuário",
        profilePicture: "https://via.placeholder.com/50",
      },
      showDropdown: false,
    };
  },
  methods: {
    toggleDropdown() {
      this.showDropdown = !this.showDropdown;
    },
    goToProfile() {
      this.$router.push("/meu-perfil");
    },
    logout() {
      Swal.fire({
        title: "Tem certeza?",
        text: "Você deseja sair da sua conta?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, sair!",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          authService.logout();
          localStorage.removeItem("userNome");
          localStorage.removeItem("userRole");
          localStorage.removeItem("userId");

          this.$router.push("/");

          Swal.fire("Deslogado!", "Você foi deslogado com sucesso.", "success");
        }
      });
    },
  },
};
</script>

<style>
.navbar {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  padding: 20px;
}

.profile {
  display: flex;
  align-items: center;
  gap: 20px;
  position: relative;
}

.profile-picture {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
  cursor: pointer;
}

.custom-dropdown-menu {
  position: absolute;
  right: 0;
  top: 60px;
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 5px 0;
  width: 150px;
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
  z-index: 1000;
}

.custom-dropdown-menu ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.custom-dropdown-menu li a {
  display: block;
  color: #333;
  padding: 10px 20px;
  text-decoration: none;
}

.custom-dropdown-menu li a:hover {
  background-color: #f1f1f1;
}

.user-name {
  font-size: 18px;
  color: #ffffff; /* Cor do texto */
}

.menu-button {
  display: none;
  background-color: transparent;
  color: #fdfdfd;
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
