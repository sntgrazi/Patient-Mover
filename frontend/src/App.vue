<template>
  <div id="app" class="container">
    <Sidebar
      v-if="!isLoginRoute"
      :isSidebarHidden="isSidebarHidden"
      @update:isSidebarHidden="handleSidebarVisibility"
    />
    <div v-if="!isLoginRoute" class="overlay" v-show="!isSidebarHidden" @click="toggleSidebar"></div>
    <div class="main-content">
      <NavBarComponent v-if="!isLoginRoute" @toggleSidebar="toggleSidebar" />
      <div class="content">
        <RouterView />
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue';
import { useRoute } from 'vue-router';
import Sidebar from "./components/SideNavComponent/SideBarComponent.vue";
import NavBarComponent from "./components/SideNavComponent/NavBarComponent.vue";

export default {
  name: "App",
  components: {
    Sidebar,
    NavBarComponent,
  },
  setup() {
    const route = useRoute();
    const isLoginRoute = computed(() => route.path === '/');
    
    return {
      isLoginRoute,
    };
  },
  data() {
    return {
      isSidebarHidden: window.innerWidth <= 1200,
    };
  },
  mounted() {
    window.addEventListener("resize", this.handleResize);
  },
  beforeUnmount() { // Atualizado para Vue 3
    window.removeEventListener("resize", this.handleResize);
  },
  methods: {
    toggleSidebar() {
      this.isSidebarHidden = !this.isSidebarHidden;
    },
    handleResize() {
      this.isSidebarHidden = window.innerWidth <= 1200;
    },
    handleSidebarVisibility(newState) {
      this.isSidebarHidden = newState;
    },
  },
  watch: {
    isSidebarHidden(newVal) {
      if (!newVal) {
        document.body.style.overflow = "hidden";
      } else {
        document.body.style.overflow = "";
      }
    },
  },
};
</script>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
}

.container {
  display: flex;
  width: 100%;
}

.main-content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  transition: margin-left 0.3s ease;
}

.content {
  padding: 20px;
  flex-grow: 1;
}

@media (max-width: 1200px) {
  .main-content {
    margin-left: 0;
    width: 100%;
  }

  .overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
  }
}

@media (max-width: 768px) {
  .content {
    padding: 10px;
  }
}

@media (max-width: 480px) {
  .content {
    padding: 5px;
  }
}
</style>
