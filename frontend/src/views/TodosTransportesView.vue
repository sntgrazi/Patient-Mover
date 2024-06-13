<template>
  <div class="todosTransportes">
    <div class="titulo-pag">Todos os transportes</div>
    <div class="tableInfo">
      <div class="header">
        <div class="filters">
          <input type="text" v-model="search" placeholder="Pesquisar..." class="search" />
          <select v-model="statusFilter">
            <option value="">Todos os status</option>
            <option value="pendente">Pendente</option>
            <option value="Em Transporte">Em Transporte</option>
            <option value="concluido">Concluído</option>
          </select>
          <select v-model="priorityFilter">
            <option value="">Todas as Prioridades</option>
            <option value="alta">Alta</option>
            <option value="media">Média</option>
            <option value="baixa">Baixa</option>
          </select>
        </div>
      </div>

      <div v-if="loading" class="tableWrapper">
        <table class="table">
          <thead>
            <tr>
              <th v-for="coluna in colunas" :key="coluna">{{ coluna }}</th>
            </tr>
          </thead>
          <tbody>
            <tr class="skeleton-row" v-for="n in skeletonRows" :key="n">
              <td v-for="coluna in colunas" :key="coluna" class="loading">
                <div class="loading-container">
                  <div class="bar"></div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="tableWrapper">
        <Tabela
          :colunas="colunas"
          :dados="filteredTransportes"
          @linha-clicada="abrirModal"
        />
      </div>
    </div>

    <Modal v-if="modalOpen" @close="fecharModal">
      <template v-slot:header>
        <h3>Detalhes do Transporte</h3>
      </template>
      <template v-slot:body>
        <div class="modal-content">
          <ul class="modal-list">
            <li v-for="(value, key) in transporteSelecionado" :key="key" class="modal-list-item">
              <strong>{{ formatKey(key) }}:</strong> {{ value }}
            </li>
          </ul>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script>
import Tabela from "@/components/TabelaComponent/TabelaComponent.vue";
import Modal from "@/components/ModalComponent/ModalComponent.vue";
import apiService from "@/services/apiService";

export default {
  name: "TodosTransportes",
  data() {
    return {
      search: "",
      statusFilter: "",
      priorityFilter: "",
      colunas: [
        "N° do Transporte",
        "Paciente",
        "Origem",
        "Destino",
        "Prioridade",
        "Data",
        "Hora",
        "Status",
      ],
      dados: [],
      loading: true,
      skeletonRows: 10,
      modalOpen: false,
      transporteSelecionado: null,
    };
  },
  computed: {
    filteredTransportes() {
      return this.dados.filter((transporte) => {
        const matchesSearch = Object.values(transporte).some((val) => 
          val && String(val).toLowerCase().includes(this.search.toLowerCase())
        );
        const matchesStatus = this.statusFilter
          ? transporte.StatusTransporte && transporte.StatusTransporte.toLowerCase() === this.statusFilter.toLowerCase()
          : true;
        const matchesPriority = this.priorityFilter
          ? transporte.Prioridade && transporte.Prioridade.toLowerCase() === this.priorityFilter.toLowerCase()
          : true;
        return matchesSearch && matchesStatus && matchesPriority;
      });
    },
  },
  components: {
    Tabela,
    Modal
  },
  mounted() {
    this.getTransportes();
  },
  methods: {
    formatKey(key) {
      const formattedKey = {
        "N° do Transporte": "N° do Transporte",
        "Paciente": "Paciente",
        "Origem": "Origem",
        "Destino": "Destino",
        "Prioridade": "Prioridade",
        "Data": "Data",
        "Hora": "Hora",
        "StatusTransporte": "Status do Transporte"
      };
      return formattedKey[key] || key;
    },
    abrirModal(transporte) {
      this.transporteSelecionado = transporte;
      this.modalOpen = true;
    },
    fecharModal() {
      this.modalOpen = false;
      this.transporteSelecionado = null;
    },
    formatDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      });
    },
    formatStatus(status) {
      if (!status) return "N/A";

      return status
        .split("_")
        .map((word) => {
          return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        })
        .join(" ");
    },
    formatPrioridade(prioridade){
      if (!prioridade) return "N/A";

      return prioridade
        .split("_")
        .map((word) => {
          return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        })
        .join(" ");
    },
    async getTransportes() {
      try {
        const response = await apiService.getTransportes();
        const originalData = response.data;

        const transformedData = originalData.map((item) => ({
          "N° do Transporte": item.id,
          Paciente: item.paciente.nome,
          Origem: item.origem.nomeLocal,
          Destino: item.destino.nomeLocal,
          Prioridade: this.formatPrioridade(item.prioridade) || "N/A",
          Data: this.formatDate(item.data) || "N/A",
          Hora: item.hora || "N/A",
          StatusTransporte: this.formatStatus(item.status),
        }));

        this.dados = transformedData;

        setTimeout(() => {
          this.loading = false;
        }, 1200);
      } catch (error) {
        console.error("Error fetching transportes:", error);
      }
    },
  },
};
</script>

<style scoped>
.todosTransportes {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 25px;
  width: 100%;
  height: 100%;
}

.tableInfo {
  padding: 20px;
  width: 100%;
  height: 75vh;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}

.header {
  width: 100%;
}

.titulo-pag {
  font-size: 20px;
  font-weight: bold;
  align-self: flex-start;
  color: white;
}

.filters {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 20px;
}

.filters input,
.filters select {
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s;
}

.filters input{
  width: 25%;
}

.filters select {
  width: 15%;
}

.filters input:focus,
.filters select:focus {
  border-color: #007bff;
  outline: none;
}

.loading {
  position: relative;
}

.loading-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

.tableWrapper {
  width: 100%;
}

.bar {
  background-color: #e7e7e7;
  height: 14px;
  border-radius: 5px;
  width: 80%;
}

.bar:after {
  position: absolute;
  content: "";
  display: block;
  width: 100%;
  height: 24px;
  background-image: linear-gradient(
    100deg,
    rgba(255, 255, 255, 0),
    rgba(255, 255, 255, 0.5) 60%,
    rgba(255, 255, 255, 0) 80%
  );
  background-size: 200px 24px;
  background-position: -100px 0;
  background-repeat: no-repeat;
  animation: loading 1s infinite;
}

@keyframes loading {
  40% {
    background-position: 100% 0;
  }
  100% {
    background-position: 100% 0;
  }
}

.modal-content {
  padding: 20px;
}

.modal-list {
  list-style: none;
  padding: 0;
}

.modal-list-item {
  margin-bottom: 10px;
  font-size: 16px;
}

.modal-list-item strong {
  font-weight: bold;
  color: #333;
}
</style>
