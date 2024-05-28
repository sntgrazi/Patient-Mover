<template>
  <div class="container">
    <div class="form-login">
      <div class="titulo-form">
        <span>Login</span>
      </div>
      <div class="form">
        <div class="inputForm">
          <label for="email">Email</label>
          <input type="text" id="email" v-model="email" />
        </div>
        <div class="inputForm">
          <label for="senha">Senha</label>
          <input type="password" id="senha" v-model="senha" />
          <a href="#">Esqueceu sua senha?</a>
        </div>
        <div class="inputForm">
        <button @click="realizarLogin">Acessar</button>
      </div>
      </div>
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
  </div>
</template>
  
<script>

import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
import apiService from '../services/apiService.js';
import authService from '@/services/authService.js';

export default {
  data() {
    return {
      email: "",
      senha: "",
    };
  },
  setup(){
    const router = useRouter();
    return { router }; 
  },  
  methods: {
    realizarLogin() {
      apiService.login({ email: this.email, password: this.senha})
      .then(response => {

        authService.setToken(response.data.access_token);
        console.log('Login successful:', response.data.access_token);

        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true,
         
        });
        Toast.fire({
          icon: "success",
          title: "Login realizado com sucesso!"
        });

        setTimeout(() => {
            this.router.push({ name: 'home' });
          }, 2000);
      }).catch(error => {
        Swal.fire({
          icon: 'error',
          title: 'Erro ao realizar login!',
          text: error.response.data.message,
        });
      });
    },
  },
};
</script>

<style scoped>
.container {
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
  gap: 10px ;
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

.inputForm > button:hover {
  background-color: #004080; 
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
  .container {
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