<template>
  <div class="transportesDisponiveis">
    <div class="titulo-pag">Transportes</div>
    <div class="transportes">
      <div class="header">
        <input type="text" v-model="search" placeholder="Pesquisar..." />
      </div>

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
            <Tabela
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
            <Tabela :colunas="colunasAceitos" :dados="transportesAceitos" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Tabela from "../components/TabelaComponent/TabelaComponent.vue";
import apiService from "@/services/apiService";
import Swal from "sweetalert2";

export default {
  name: "TransportesMaqueiro",
  data() {
    return {
      search: "",
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
    };
  },
  components: {
    Tabela,
  },
  methods: {
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
      const maqueiroId = localStorage.getItem("userId");
      try {
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
          .filter((item) => item.maqueiro_id !== null)
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
  },
  mounted() {
    this.getTransportes();
  },
};
</script>

<style scoped>
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

.header input {
  width: 25%;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s;
}

.header input:focus {
  border-color: #007bff;
  box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
  outline: none;
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
