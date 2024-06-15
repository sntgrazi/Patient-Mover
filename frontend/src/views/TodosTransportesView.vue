<template>
  <div class="todosTransportes">
    <div class="titulo-pag">Todos os transportes</div>
    <div class="tableInfo">
      <div class="header">
        <div class="filters">
          <input
            type="text"
            v-model="search"
            placeholder="Pesquisar..."
            class="search"
          />
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
        <div
              v-if="filteredTransportes.length === 0"
              class="no-data-message"
            >
              Não há transportes no momento
            </div>
        <Tabela
        v-else
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
          <!-- Transporte Details List -->
          <ul class="modal-list">
            <li
              v-for="(value, key) in transporteSelecionado"
              :key="key"
              class="modal-list-item"
            >
              <strong>{{ formatKey(key) }}:</strong> <span>{{ value }}</span>
            </li>
          </ul>

          <!-- Incident Reports -->
          <div class="incident-reports">
            <div
              v-for="incidente in incidentes"
              :key="incidente.id"
              class="incident-report"
            >
              <strong>Incidente:</strong> {{ incidente.descricao }}
              <span class="incident-date">{{
                new Date(incidente.created_at).toLocaleString()
              }}</span>
            </div>
          </div>

          <!-- Timeline -->
          <div class="timeline-container">
            <div class="timeline">
              <div class="timeline-line"></div>
              <div
                v-for="evento in historicoTransporte"
                :key="evento.id"
                class="timeline-event"
              >
                <div class="timeline-content">
                  <div class="timeline-icon">
                    <i class="fa-regular fa-clock"></i>
                  </div>
                  <div class="timeline-text">
                    <span>{{ evento.momento }}</span>
                    <span class="timeline-date">{{
                      new Date(evento.created_at).toLocaleString()
                    }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
      historicoTransporte: [],
      incidentes: [],
    };
  },
  computed: {
    filteredTransportes() {
      return this.dados.filter((transporte) => {
        const matchesSearch = Object.values(transporte).some(
          (val) =>
            val && String(val).toLowerCase().includes(this.search.toLowerCase())
        );
        const matchesStatus = this.statusFilter
          ? transporte.StatusTransporte &&
            transporte.StatusTransporte.toLowerCase() ===
              this.statusFilter.toLowerCase()
          : true;
        const matchesPriority = this.priorityFilter
          ? transporte.Prioridade &&
            transporte.Prioridade.toLowerCase() ===
              this.priorityFilter.toLowerCase()
          : true;
        return matchesSearch && matchesStatus && matchesPriority;
      });
    },
  },
  components: {
    Tabela,
    Modal,
  },
  mounted() {
    this.getTransportes();
  },
  methods: {
    async getHistoricoTransporte(transporteId) {
      
      try {
        const response = await apiService.getHistoricoTransporte(transporteId);
        this.historicoTransporte = response.data;
      } catch (error) {
        console.error("Error fetching historico:", error);
      }
    },
    async getIncidentes(transporteId) {
      try {
        const response = await apiService.getIncidentes(transporteId);
        this.incidentes = response.data;
      } catch (error) {
        console.error("Error fetching incidentes:", error);
      }
    },
    formatKey(key) {
      const formattedKey = {
        "N° do Transporte": "N° do Transporte",
        Paciente: "Paciente",
        Origem: "Origem",
        Destino: "Destino",
        Prioridade: "Prioridade",
        Data: "Data",
        Hora: "Hora",
        StatusTransporte: "Status do Transporte",
      };
      return formattedKey[key] || key;
    },
    abrirModal(transporte) {
      this.transporteSelecionado = transporte;
      console.log(transporte);
      this.getIncidentes(transporte["N° do Transporte"]);
      this.getHistoricoTransporte(transporte["N° do Transporte"]);

      setTimeout(() => {
        this.modalOpen = true;
      }, 500);
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
    formatPrioridade(prioridade) {
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
.no-data-message {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px;
  color: #6c757d;
  font-size: 18px;
  font-weight: bold;
}

.timeline-container {
  position: relative;
  margin-top: 20px;
  padding: 10px;
  overflow-x: auto;
}

.timeline {
  display: flex;
  align-items: center;
  position: relative;
  padding: 0 20px;
}

.timeline-event {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 10px;
  position: relative;
  min-width: 150px;
  text-align: center;
  background: #fff;
  border-radius: 4px;
  z-index: 1;
  margin: 10px;
}

.timeline-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.timeline-icon {
  font-size: 24px;
  color: #28a745;
  margin-bottom: 5px;
}

.timeline-text span {
  display: block;
  margin: 5px 0;
}

.timeline-date {
  font-size: 12px;
  color: #999;
}

.modal-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.modal-list-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.modal-list-item strong {
  color: #333;
  font-weight: bold;
}

.modal-list-item span {
  color: #555;
}

.modal-list-item:nth-child(even) {
  background-color: #f9f9f9;
}

.modal-list-item:nth-child(odd) {
  background-color: #fff;
}

.modal-list-item:last-child {
  border-bottom: none;
}

.incident-form {
  display: flex;
  flex-direction: column;
  margin-top: 20px;
}

.incident-form span {
  margin-bottom: 10px;
  font-weight: bold;
}

.incident-form textarea {
  width: 100%;
  height: 100px;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.incident-reports {
  margin-top: 20px;
}

.incident-report {
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 10px;
  margin-bottom: 10px;
  background: #f9f9f9;
}

.incident-date {
  display: block;
  font-size: 12px;
  color: #999;
  margin-top: 5px;
}

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

.filters input {
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

.table th,
.table td {
  color: #8a8b8b;
  text-align: center;
  padding: 0.8rem;
  border-bottom: 1px solid #e9ecef;
}

.skeleton-row .loading-container {
  height: 20px;
  background-color: #f1f3f5;
  animation: pulse 1.5s infinite ease-in-out;
  border-radius: 4px;
}

@keyframes pulse {
  0% {
    background-color: #f1f3f5;
  }
  50% {
    background-color: #e9ecef;
  }
  100% {
    background-color: #f1f3f5;
  }
}



</style>
