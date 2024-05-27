<template>
  <div class="container">
    <div class="form-login">
      <div class="titulo-form">
        <span>Login</span>
      </div>
      <div class="form">
        <div class="inputForm">
          <label for="usuario">Usuário</label>
          <input type="text" id="usuario" v-model="usuario" />
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
export default {
  data() {
    return {
      usuario: "",
      senha: "",
    };
  },
  methods: {
    realizarLogin() {
      console.log("Login:", this.usuario, this.senha);
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