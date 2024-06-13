<template>
  <div class="solicitar-transporte">
    <div class="titulo">
      <h2>Solicitar Transporte</h2>
      <span>Criar nova solicitação</span>
    </div>
    <form class="formulario" @submit.prevent="criarSolicitacao">
      <div class="form-group-inline">
        <div class="form-group" style="flex: 6">
          <label for="nome-paciente">Nome do Paciente</label>
          <v-select
            :options="pacientes"
            label="label"
            class="custom-select"
            v-model="paciente"
          ></v-select>
        </div>
        <div class="form-group" style="flex: 4">
          <label for="prioridade">Prioridade</label>
          <v-select
            :options="opcoesPrioridade"
            label="label"
            class="custom-select"
            v-model="prioridade"
          ></v-select>
        </div>
      </div>
      <div class="form-group-inline">
        <div class="form-group">
          <label for="localizacao-atual">Localização Atual</label>
          <v-select
            :options="locais"
            label="label"
            class="custom-select"
            v-model="localizacaoAtual"
          ></v-select>
        </div>
        <div class="form-group">
          <label for="destino">Destino</label>
          <v-select
            :options="filtrarLocaisDestino"
            label="label"
            class="custom-select"
            v-model="destino"
          ></v-select>
        </div>
      </div>
      <div class="form-group-inline">
        <div class="form-group">
          <label for="data">Data</label>
          <input type="date" id="data" name="data" v-model="data" />
        </div>
        <div class="form-group">
          <label for="hora">Hora</label>
          <input type="time" id="hora" name="hora" v-model="hora" />
        </div>
      </div>
      <div class="form-group">
        <label for="informacoes">Informações</label>
        <textarea
          id="informacoes"
          name="informacoes"
          v-model="informacoes"
        ></textarea>
      </div>
      <button type="submit" class="btn-criar-solicitacao">Criar Solicitação</button>
    </form>
  </div>
</template>
  
<script>
import Swal from "sweetalert2";
import apiService from "@/services/apiService";

export default {
  name: "SolicitarTransporte",
  data() {
    return {
      locais: [],
      pacientes: [],
      localizacaoAtual: null,
      destino: null,
      paciente: null,
      prioridade: null,
      data: "",
      hora: "",
      informacoes: "",
      opcoesPrioridade: [
        { value: "alta", label: "Alta" },
        { value: "media", label: "Média" },
        { value: "baixa", label: "Baixa" },
      ],
    };
  },
  mounted() {
    this.getLocais();
    this.getPacientes();
  },
  computed: {
    filtrarLocaisDestino() {
      return this.locais.filter(
        (local) => local.value !== this.localizacaoAtual?.value
      );
    },
  },
  methods: {
    async getLocais() {
      try {
        const response = await apiService.getLocais();
        this.locais = response.data.map((local) => ({
          label: local.nomeLocal,
          value: local.id,
        }));
      } catch (error) {
        console.error("Error fetching locais:", error);
      }
    },
    async getPacientes() {
      try {
        const response = await apiService.getPacientes();
        this.pacientes = response.data.map((paciente) => ({
          label: paciente.nome,
          value: paciente.id,
        }));
      } catch (error) {
        console.error("Error fetching pacientes:", error);
      }
    },
    async criarSolicitacao() {
      if (
        !this.paciente ||
        !this.localizacaoAtual ||
        !this.destino ||
        !this.prioridade ||
        !this.data ||
        !this.hora
      ) {
        Swal.fire({
          title: "Campos incompletos",
          text: "Por favor, preencha todos os campos obrigatórios.",
          icon: "error",
          confirmButtonText: "OK",
        });
        return;
      }

      const solicitacao = {
        paciente_id: this.paciente.value,
        origem: this.localizacaoAtual.value,
        destino: this.destino.value,
        prioridade: this.prioridade.value,
        maqueiro_id: null,
        data: this.data,
        hora: this.hora,
        descricao: this.informacoes,
      };

      try {
        await apiService.criarSolicitacao(solicitacao);

        Swal.fire({
          title: "Sucesso!",
          text: "Solicitação de transporte criada com sucesso.",
          icon: "success",
          confirmButtonText: "OK",
        });

        this.paciente = null;
        this.localizacaoAtual = null;
        this.destino = null;
        this.prioridade = null;
        this.data = "";
        this.hora = "";
        this.informacoes = "";
      } catch (error) {
        console.error("Error creating solicitacao:", error);

        Swal.fire({
          title: "Erro!",
          text: "Ocorreu um erro ao criar a solicitação. Por favor, tente novamente mais tarde.",
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    },
  },
};
</script>
  
<style scoped>
.solicitar-transporte {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  margin: auto;
  gap: 20px;
  padding: 20px;
  width: 70%;
  height: auto;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}

.titulo {
  color: #000000;
  margin: 20px 0;
}

.titulo span {
  color: #545454;
}

.formulario {
  margin: 10px 0;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 20px;
  width: 100%;
}

.form-group,
.form-group-inline {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.form-group-inline {
  flex-direction: row;
  justify-content: space-between;
  gap: 20px;
}

label {
  font-size: 16px;
  margin-bottom: 5px;
  color: #555;
}

input,
textarea {
  padding: 15px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  margin-bottom: 10px;
  transition: border-color 0.3s;
}

input:focus,
textarea:focus,
.custom-select:focus {
  border-color: #007bff;
  outline: none;
}

textarea {
  resize: vertical;
  min-height: 100px;
}

.btn-criar-solicitacao {
  padding: 10px 20px;
  font-size: 16px;
  color: #fff;
  background-color: #3182ce;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-criar-solicitacao:hover {
  background-color: #0056b3;
}

@media (max-width: 768px) {
  .solicitar-transporte {
    width: 90%;
    padding: 10px;
  }

  .form-group-inline {
    flex-direction: column;
  }

  .formulario {
    align-items: stretch;
  }

  .btn-criar-solicitacao {
    align-self: center;
  }
}

@media (max-width: 480px) {
  .solicitar-transporte {
    width: 90%;
    padding: 15px;
  }

  input,
  textarea,
  select,
  .btn-criar-solicitacao {
    width: 100%;
    padding: 10px;
  }
}
</style>
