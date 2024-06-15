<template>
  <div class="custom-container">
    <div class="form-login">
      <div class="titulo-form">
        <span>Login</span>
      </div>
      <form @submit.prevent="realizarLogin" class="form">
        <div class="inputForm">
          <label for="email">Email</label>
          <input type="email" id="email" v-model="email" required />
        </div>
        <div class="inputForm">
          <label for="senha">Senha</label>
          <input
            :type="showPassword ? 'text' : 'password'"
            id="senha"
            v-model="senha"
            required
          />
          <span
            class="toggle-password"
            :class="{
              'fa-solid fa-eye': !showPassword,
              'fa-solid fa-eye-slash': showPassword,
            }"
            @click="togglePasswordVisibility"
          ></span>
          <a @click="mostrarModal">Esqueceu sua senha?</a>
        </div>
        <div class="inputForm">
          <button>Acessar</button>
        </div>
      </form>
    </div>

    <div class="conteudo">
      <img
        src="../assets/img/5.jpg"
        alt="Paciente e transportador"
        class="image"
      />
      <div class="titulo">
        <h1>Patient Mover</h1>
        <span>Coordenação eficiente de transporte de pacientes</span>
      </div>
    </div>

    <Modal v-if="exibirModal" @close="fecharModal">
      <template #header>
        <h2>Recuperar Senha</h2>
      </template>
      <template #body>
        <form @submit.prevent="enviarSenhaTemporaria">
          <div class="inputForm">
            <label for="emailRecuperacao">Email</label>
            <input
              type="email"
              id="emailRecuperacao"
              v-model="emailRecuperacao"
              required
            />
          </div>
          <div class="inputFormModal">
            <button class="buttonModal" type="submit">Enviar</button>
            <button class="buttonModal" type="button" @click="fecharModal">
              Cancelar
            </button>
          </div>
        </form>
      </template>
    </Modal>

    <Modal v-if="exibirModalTrocaSenha" @close="fecharModalTrocaSenha">
      <template #header>
        <h2>Alterar Senha</h2>
      </template>
      <template #body>
        <form @submit.prevent="trocarSenha">
          <div class="inputForm">
            <label for="novaSenha">Nova Senha</label>
            <input
              :type="showPasswordModal ? 'text' : 'password'"
              id="novaSenha"
              v-model="novaSenha"
              required
            />
            <span
              class="toggle-password-modal"
              :class="{
                'fa-solid fa-eye': !showPasswordModal,
                'fa-solid fa-eye-slash': showPasswordModal,
              }"
              @click="togglePasswordVisibilityModal"
            ></span>
          </div>
          <div class="inputForm">
            <label for="confirmarNovaSenha">Confirmar Nova Senha</label>

            <input
              type="password"
              id="confirmarNovaSenha"
              v-model="confirmarNovaSenha"
              required
            />
          </div>
          <div class="inputFormModal">
            <button class="buttonModal" type="submit">Alterar</button>
          </div>
        </form>
      </template>
    </Modal>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import { useRouter } from "vue-router";
import apiService from "@/services/apiService.js";
import authService from "@/services/authService.js";
import Modal from "@/components/ModalComponent/ModalComponent.vue";

export default {
  components: {
    Modal,
  },
  data() {
    return {
      email: "",
      senha: "",
      exibirModal: false,
      exibirModalTrocaSenha: false,
      emailRecuperacao: "",
      novaSenha: "",
      confirmarNovaSenha: "",
      token: "",
      showPassword: false,
      showPasswordModal: false,
    };
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  methods: {
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword;
    },
    togglePasswordVisibilityModal() {
      this.showPasswordModal = !this.showPasswordModal;
    },
    mostrarModal() {
      this.exibirModal = true;
    },
    fecharModal() {
      this.exibirModal = false;
      this.emailRecuperacao = "";
    },
    fecharModalTrocaSenha() {
      this.exibirModalTrocaSenha = false;
      this.novaSenha = "";
      this.confirmarNovaSenha = "";
    },
    async realizarLogin() {
      await apiService
        .login({ email: this.email, password: this.senha })
        .then((response) => {
          this.token = response.data.access_token;
          localStorage.setItem("userRole", response.data.user_role);
          localStorage.setItem("userNome", response.data.user_nome);
          localStorage.setItem("userId", response.data.user_id);
          if (response.data.password_reset_required) {
            this.exibirModalTrocaSenha = true;
          } else {
            authService.setToken(this.token);
            const Toast = Swal.mixin({
              toast: true,
              position: "top-end",
              showConfirmButton: false,
              timer: 2000,
              timerProgressBar: true,
            });
            Toast.fire({
              icon: "success",
              title: "Login realizado com sucesso!",
            });

            setTimeout(() => {
              
                this.router.push({ name: "home" });
             
             
            }, 2000);
          }
        })
        .catch((error) => {
          console.log(error.response.data);
          Swal.fire({
            icon: "error",
            title: "Erro ao realizar login!",
            text: error.response.data.error,
          });
        });
    },
    async enviarSenhaTemporaria() {
      Swal.fire({
        title: "Enviando email...",
        text: "Por favor, aguarde.",
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      await apiService
        .resetSenha({ email: this.emailRecuperacao })
        .then(() => {
          Swal.fire({
            icon: "success",
            title: "Email enviado!",
            text: "Uma senha temporária foi enviada para o seu email.",
          });
          this.fecharModal();
        })
        .catch((error) => {
          console.log(error.response.data);
          Swal.fire({
            icon: "error",
            title: "Erro ao enviar email!",
            text: error.response.data.error,
          });
        });
    },
    async trocarSenha() {
      if (this.novaSenha !== this.confirmarNovaSenha) {
        Swal.fire({
          icon: "error",
          title: "Erro ao alterar senha!",
          text: "As senhas não coincidem.",
        });
        return;
      }

      Swal.fire({
        title: "Alterando senha...",
        text: "Por favor, aguarde.",
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        },
      });

      await apiService
        .trocarSenha({ password: this.novaSenha }, this.token)
        .then(() => {
          Swal.fire({
            icon: "success",
            title: "Senha alterada com sucesso!",
            text: "Sua senha foi alterada com sucesso. Você será redirecionado para a tela inicial.",
          });
          this.fecharModalTrocaSenha();
          authService.setToken(this.token);
          setTimeout(() => {
            this.router.push({ name: "home" });
          }, 2000);
        })
        .catch((error) => {
          console.log(error.response.data);
          Swal.fire({
            icon: "error",
            title: "Erro ao alterar senha!",
            text: error.response.data.error,
          });
        });
    },
  },
};
</script>

<style scoped>
.custom-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  align-items: stretch;
  font-family: "Righteous", sans-serif;
  background-color: #ffffff;
  padding: 20px;
}

.form-login {
  width: 50%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background-color: #ffffff;
  margin: auto;
  gap: 20px;
}

.titulo-form {
  font-weight: bold;
  font-size: 2em;
  color: #0056b3;
}

.form {
  width: 100%;
  max-width: 360px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.inputForm {
  display: flex;
  flex-direction: column;
  margin: 10px 0;
  width: 100%;
}

.inputForm > label {
  margin-bottom: 5px;
}

.inputForm > input {
  font-size: 18px;
  padding: 15px;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border-radius: 10px;
  outline: none;
}

.inputForm > input:focus {
  border-color: #0056b3;
}

.inputForm > a {
  margin-top: 5px;
  color: #0056b3;
  cursor: pointer;
}

.inputForm > button {
  font-size: 20px;
  padding: 15px;
  background-color: #0056b3;
  color: #fff;
  border-radius: 10px;
  cursor: pointer;
  border: none;
}

.inputForm > button:hover, .buttonModal:hover {
  background-color: #004080;
}

.inputForm {
  position: relative;
}

.inputForm input[type="password"] {
  padding-right: 30px;
}

.inputForm .toggle-password {
  position: absolute;
  top: 50%;
  right: 10px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #a5a5a5;
}

.inputForm .toggle-password-modal {
  position: absolute;
  top: 65%;
  right: 15px;
  transform: translateY(-50%);
  cursor: pointer;
  color: #a5a5a5;
}

.inputFormModal {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.buttonModal {
  margin-top: 20px;
  font-size: 20px;
  padding: 10px;
  background-color: #0056b3;
  color: #fff;
  border-radius: 10px;
  cursor: pointer;
  border: none;
  width: 100%;
}

.conteudo {
  width: 50%;
  padding: 40px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #0e1147;
  color: white;
  position: relative;
  border-radius: 20px;
  overflow: hidden;
}

.image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  opacity: 0.3;
  z-index: 0;
}

.titulo {
  z-index: 1;
  position: relative;
  text-align: center;
}

.titulo > h1 {
  font-size: 2.5em;
}

.titulo > span {
  font-size: 1.2em;
}

@media (max-width: 1200px) {
  .custom-container {
    flex-direction: column;
  }

  .form-login,
  .conteudo {
    width: 100%;
    padding: 20px;
  }

  .conteudo {
    display: none;
  }

  .titulo > h1 {
    font-size: 1.8em;
  }

  .image {
    position: static;
    width: 100%;
    height: auto;
    opacity: 1;
  }
}
</style>