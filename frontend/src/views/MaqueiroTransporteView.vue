<template>
  <div class="transportesDisponiveis">
    <div class="titulo-pag">Transportes</div>
    <div class="transportes">
      <ul
        class="nav nav-tabs justify-content-center"
        id="transportesTab"
        role="tablist"
      >
        <li class="nav-item" role="presentation">
          <button
            class="nav-link active"
            id="disponiveis-tab"
            data-bs-toggle="tab"
            data-bs-target="#disponiveis"
            type="button"
            role="tab"
            aria-controls="disponiveis"
            aria-selected="true"
          >
            Transportes Disponíveis
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="aceitos-tab"
            data-bs-toggle="tab"
            data-bs-target="#aceitos"
            type="button"
            role="tab"
            aria-controls="aceitos"
            aria-selected="false"
          >
            Transportes Aceitos
          </button>
        </li>
      </ul>

      <div class="tab-content" id="transportesTabContent">
        <div
          class="tab-pane fade show active"
          id="disponiveis"
          role="tabpanel"
          aria-labelledby="disponiveis-tab"
        >
          <div v-if="loading" class="tableWrapper">
            <table class="table">
              <thead>
                <tr>
                  <th v-for="coluna in colunasDisponiveis" :key="coluna">
                    {{ coluna }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="skeleton-row" v-for="n in skeletonRows" :key="n">
                  <td
                    v-for="coluna in colunasDisponiveis"
                    :key="coluna"
                    class="loading"
                  >
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
              v-if="transportesDisponiveis.length === 0"
              class="no-data-message"
            >
              Não há transportes no momento
            </div>
            <Tabela
              v-else
              :colunas="colunasDisponiveis"
              :dados="transportesDisponiveis"
              :acoes="acoesDisponiveis"
              @executar-acao="executarAcao"
            />
          </div>
        </div>

        <div
          class="tab-pane fade"
          id="aceitos"
          role="tabpanel"
          aria-labelledby="aceitos-tab"
        >
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
                  <th v-for="coluna in colunasAceitos" :key="coluna">
                    {{ coluna }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr class="skeleton-row" v-for="n in skeletonRows" :key="n">
                  <td
                    v-for="coluna in colunasAceitos"
                    :key="coluna"
                    class="loading"
                  >
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
              v-if="filteredTransportesAceitos.length === 0"
              class="no-data-message"
            >
              Não há transportes no momento
            </div>
            <Tabela
              v-else
              :colunas="colunasAceitos"
              :dados="filteredTransportesAceitos"
              @linha-clicada="abrirModal"
            />
          </div>
        </div>
      </div>
    </div>

    <Modal v-if="modalOpen" @close="fecharModal">
      <template v-slot:header>
        <h3>Detalhes do Transporte</h3>
      </template>
      <template v-slot:body>
        <div class="modal-content">
          <ul class="modal-list">
            <li
              v-for="(value, key) in transporteSelecionado"
              :key="key"
              class="modal-list-item"
            >
              <strong>{{ formatKey(key) }}:</strong> <span>{{ value }}</span>
            </li>
          </ul>
          <div class="modal-buttons">
            <button
              v-if="transporteSelecionado.StatusTransporte === 'Pendente'"
              @click="iniciarTransporte"
              class="button green"
            >
              Iniciar Transporte
            </button>
            <div class="modal-buttons" v-else>
              <button @click="toggleIncidentForm" class="button red">
                Relatar Incidente
              </button>
              <div
                v-if="transporteSelecionado.StatusTransporte !== 'Concluido'"
              >
                <button @click="concluirTransporte" class="button green">
                  Concluir Transporte
                </button>
              </div>
            </div>
          </div>
          <div v-if="showIncidentForm" class="incident-form">
            <span>Descreva o incidente no campo abaixo: </span>
            <textarea
              v-model="incidentDescription"
              placeholder="Descreva o incidente"
            ></textarea>
            <button @click="enviarIncidente" class="button red">
              Enviar Incidente
            </button>
          </div>
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
import Tabela from "../components/TabelaComponent/TabelaComponent.vue";
import apiService from "@/services/apiService";
import Modal from "@/components/ModalComponent/ModalComponent.vue";
import Swal from "sweetalert2";

export default {
  name: "TransportesMaqueiro",
  data() {
    return {
      search: "",
      statusFilter: "",
      priorityFilter: "",
      colunasDisponiveis: [
        "N° do transporte",
        "Paciente",
        "Origem",
        "Destino",
        "Prioridade",
        "Ações",
      ],
      colunasAceitos: [
        "N° do transporte",
        "Paciente",
        "Origem",
        "Destino",
        "Prioridade ",
        "Status do Transporte",
      ],
      transportesDisponiveis: [],
      transportesAceitos: [],
      loading: true,
      acoesDisponiveis: [
        { label: "Aceitar", class: "acao-button", action: "aceitar" },
        { label: "Recusar", class: "btn-red", action: "recusar" },
      ],
      skeletonRows: 10,
      modalOpen: false,
      transporteSelecionado: null,
      historicoTransporte: [],
      showIncidentForm: false,
      incidentDescription: "",
      incidentes: [],
    };
  },
  components: {
    Tabela,
    Modal,
  },
  computed: {
    filteredTransportesAceitos() {
      return this.transportesAceitos.filter((transporte) => {
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
  methods: {
    async getIncidentes(transporteId) {
      try {
        const response = await apiService.getIncidentes(transporteId);
        this.incidentes = response.data;
      } catch (error) {
        console.error("Error fetching incidentes:", error);
      }
    },
    toggleIncidentForm() {
      this.showIncidentForm = !this.showIncidentForm;
    },
    async enviarIncidente() {
      if (!this.incidentDescription.trim()) {
        Swal.fire({
          icon: "warning",
          title: "Descrição vazia!",
          text: "Por favor, insira uma descrição para o incidente.",
        });
        return;
      }

      try {
        const maqueiroId = localStorage.getItem("userId");
        const transporteId = this.transporteSelecionado["N° do transporte"];
        await apiService.relatarIncidente(
          transporteId,
          this.incidentDescription,
          maqueiroId
        );
        await Swal.fire({
          icon: "success",
          title: "Incidente relatado!",
          text: "O incidente foi relatado com sucesso.",
        });
        this.getIncidentes(transporteId);
        this.showIncidentForm = false;
        this.incidentDescription = "";
      } catch (error) {
        console.error("Error sending incidente:", error);
        Swal.fire({
          icon: "error",
          title: "Erro!",
          text: "Ocorreu um erro ao enviar o incidente. Por favor, tente novamente.",
        });
      }
    },
    async getHistoricoTransporte(transporteId) {
      try {
        const response = await apiService.getHistoricoTransporte(transporteId);
        this.historicoTransporte = response.data;
      } catch (error) {
        console.error("Error fetching historico:", error);
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
      this.getIncidentes(transporte["N° do transporte"]);
      this.getHistoricoTransporte(transporte["N° do transporte"]);

      setTimeout(() => {
        this.modalOpen = true;
      }, 500);
    },
    fecharModal() {
      this.modalOpen = false;
      this.transporteSelecionado = null;
      this.showIncidentForm = false;
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
      this.loading = true;

      try {
        const maqueiroId = localStorage.getItem("userId");
        const response = await apiService.getTransportesDisponiveis(maqueiroId);
    
        const originalData = response.data;
      

        const transportesDisponiveis = originalData
          .filter((item) => item.maqueiro_id === null)
          .map((item) => ({
            "N° do transporte": item.id,
            Paciente: item.paciente.nome,
            Origem: item.origem.nomeLocal,
            Destino: item.destino.nomeLocal,
            Prioridade: this.formatPrioridade(item.prioridade),
          }));

        const transportesAceitos = originalData
          .filter(
            (item) =>
              item.maqueiro_id !== null && item.maqueiro_id == maqueiroId
          )
          .map((item) => ({
            "N° do transporte": item.id,
            Paciente: item.paciente.nome,
            Origem: item.origem.nomeLocal,
            Destino: item.destino.nomeLocal,
            Prioridade: this.formatPrioridade(item.prioridade),
            StatusTransporte: this.formatStatus(item.status),
          }));
          
        this.transportesDisponiveis = transportesDisponiveis;
        this.transportesAceitos = transportesAceitos;

        setTimeout(() => {
          this.loading = false;
        }, 1200);
      } catch (error) {
        console.error("Error fetching transportes:", error);
      }
    },
    async executarAcao(acao, transporte) {
      if (acao === "aceitar") {
        await this.aceitarTransporte(transporte["N° do transporte"]);
      } else if (acao === "recusar") {
        await this.recusarTransporte(transporte["N° do transporte"]);
      }
    },
    async aceitarTransporte(transporteId) {
      try {
        const maqueiroId = localStorage.getItem("userId");
        if (!maqueiroId) {
          console.error("userID não encontrado no localStorage");
          return;
        }

        await apiService.aceitarTransporte(transporteId, maqueiroId);
        this.getTransportes();

        await Swal.fire({
          icon: "success",
          title: "Transporte aceito!",
          text: "A solicitação de transporte foi aceita.",
        });
      } catch (error) {
        console.error("Error accepting transporte:", error);
      }
    },
    async recusarTransporte(transporteId) {
      try {
        const maqueiroId = localStorage.getItem("userId");
        if (!maqueiroId) {
          console.error("userID não encontrado no localStorage");
          return;
        }

        await apiService.recusarTransporte(transporteId, maqueiroId);
        this.getTransportes();

        await Swal.fire({
          icon: "success",
          title: "Transporte recusado!",
          text: "A solicitação de transporte foi recusada.",
        });
      } catch (error) {
        console.error("Error rejecting transporte:", error);
      }
    },
    async iniciarTransporte() {
      try {
        const transporteId = this.transporteSelecionado["N° do transporte"];
        await apiService.iniciarTransporte(transporteId);
        this.getTransportes();
        this.fecharModal();

        await Swal.fire({
          icon: "success",
          title: "Transporte iniciado!",
          text: "O transporte foi iniciado.",
        });

        this.transporteSelecionado.StatusTransporte = "Em transporte";
      } catch (error) {
        console.error("Error starting transporte:", error);
      }
    },
    async concluirTransporte() {
      try {
        const transporteId = this.transporteSelecionado["N° do transporte"];
        await apiService.finalizarTransporte(transporteId, "concluido");
        this.getTransportes();
        this.fecharModal();

        await Swal.fire({
          icon: "success",
          title: "Transporte concluído!",
          text: "O transporte foi concluído.",
        });

        this.transporteSelecionado.StatusTransporte = "Concluido";
      } catch (error) {
        console.error("Error concluding transporte:", error);
      }
    },
  },
  mounted() {
    this.getTransportes();
  },
};
</script>


<style scoped>
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

.modal-content {
  width: 100%;
}

.modal-buttons {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  flex-direction: row;
}

.modal-buttons button {
  margin: 5px 10px 0px 10px;
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

.transportes {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 2rem auto;
  padding: 1.5rem;
  width: 100%;
  height: 75vh;
  background-color: #ffffff;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.titulo-pag {
  font-size: 20px;
  font-weight: bold;
  align-self: flex-start;
  color: white;
}

.header {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.header h2 {
  font-size: 1.8rem;
  color: #343a40;
}

.nav-tabs {
  width: 100%;
  margin-bottom: 1rem;
}

.nav-item {
  flex: 1;
}

.nav-link {
  font-size: 1.1rem;
  color: #495057;
  width: 100%;
  text-align: center;
}

.nav-link.active {
  color: #007bff;
  background-color: #e9ecef;
  border-color: #dee2e6 #dee2e6 #fff;
}

.tab-content {
  width: 100%;
}

.tableSection {
  width: 100%;
  margin-top: 1rem;
  padding: 1rem;
}

.tableSection h3 {
  font-size: 1.4rem;
  color: #495057;
  margin-bottom: 1rem;
}

.tableWrapper {
  width: 100%;
  overflow-x: auto;
}

.table {
  width: 100%;
  border-collapse: collapse;
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

.filters {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-bottom: 20px;
  width: 100%;
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

.button {
  padding: 0.6rem 1.2rem;
  font-size: 0.9rem;
  color: #fff;
  background-color: #007bff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.button:hover {
  background-color: #0056b3;
}

.button.red {
  background-color: #dc3545;
}

.button.red:hover {
  background-color: #c82333;
}

.button.green {
  background-color: #28a745;
}

.button.green:hover {
  background-color: #218838;
}

.no-data-message {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px;
  color: #6c757d;
  font-size: 18px;
  font-weight: bold;
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



