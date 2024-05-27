<template>
  <div>
    <div class="sidebar" :class="{ hidden: isSidebarHidden }">
      <button @click="toggleSidebar" class="fechar-button">
        <i class="fa-solid fa-xmark"></i>
      </button>
      <div class="logo">
        <h2>Patient Mover</h2>
        <img src="../../assets/img/cama-de-hospital.png" alt="icon" />
      </div>
      <ul>
        <li
          v-for="item in menuItems"
          :key="item.text"
          @click="setActive(item.text)"
        >
          <router-link
            :to="item.link"
            :class="{ active: activeItem === item.text }"
          >
            <i :class="`fa-solid ${item.icon}`"></i> {{ item.text }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  name: "Sidebar",
  props: {
    isSidebarHidden: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      activeItem: null,
      menuItems: [
        { text: "Home", icon: "fa-house", link: "/" },
        { text: "Solicitar Transporte", icon: "fa-circle-plus", link: "/solicitar-transporte" },
        { text: "Incidentes", icon: "fa-triangle-exclamation", link: "/incidentes" },
        { text: "Todos os transportes", icon: "fa-list", link: "/todas-solicitacoes" },
      ],
    };
  },
  methods: {
    setActive(item) {
      this.activeItem = item;
    },
    toggleSidebar() {
      this.$emit("update:isSidebarHidden", !this.isSidebarHidden);
    },
  },
  watch: {
    isSidebarHidden(newVal) {
      if (newVal) {
        document.body.classList.add("noscroll");
      } else {
        document.body.classList.remove("noscroll");
      }
    },
  },
};
</script>

<style scoped>
.sidebar {
  display: flex;
  flex-direction: column;
  position: relative;
  width: 280px;
  height: 95vh;
  background-color: #fff;
  color: #000000;
  padding: 20px;
  margin: 20px;
  box-sizing: border-box;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease, visibility 0.3s ease;
}

.sidebar.hidden {
  transform: translateX(-100%);
  visibility: hidden;
}

.sidebar > .logo {
  position: relative;
  margin-top: 40px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-bottom: 1px solid #34495e;
  padding-bottom: 20px;
}

.sidebar > .logo > h2 {
  margin-top: 0;
  font-size: 24px;
  font-weight: bold;
  text-align: center;
  padding-bottom: 10px;
}

.sidebar > .logo > img {
  width: 50px;
  height: 50px;
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.sidebar ul li {
  margin: 20px 0;
  display: block;
}

.sidebar ul li a {
  color: #2d3748;
  text-decoration: none;
  font-size: 17px;
  font-weight: 500;
  transition: color 0.3s ease, background-color 0.3s ease;
  display: inline-block;
  width: 100%;
  padding: 18px 10px;
  border-radius: 8px;
}

.sidebar ul li a i {
  color: #3182ce;
  margin-right: 8px;
  font-size: 20px;
}

.sidebar ul li a:hover {
  background-color: #ffffff;
  box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.2);
}

.sidebar ul li a.active {
  transition: box-shadow 0.3s ease;
  box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.2);
}

.fechar-button {
  position: absolute;
  top: 8px;
  display: none;
  right: 10px;
  background-color: transparent;
  color: #000000;
  border: none;
  padding: 10px;
  font-size: 24px;
  border-radius: 5px;
  cursor: pointer;
}

@media (max-width: 1200px) {
  .sidebar {
    width: 270px;
    position: fixed;
    top: 0;
    left: 0;
    transition: transform 0.3s ease, visibility 0.3s ease;
    z-index: 1000;
    height: 95vh;
  }

  .fechar-button {
    display: block;
  }
}
</style>
