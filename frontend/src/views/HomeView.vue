<template>
  <div class="home">
    <div class="titulo-pag">Home</div>
    <div class="cards">
      <Card
        title="Aguardando transporte"
        :quantity="pendentes"
        icon="fa-clock-rotate-left"
      />
      <Card
        title="Em transporte"
        :quantity="emTransporte"
        icon="fa-truck-medical"
      />
      <Card title="Concluídos" :quantity="concluidos" icon="fa-check" />
    </div>

    <div class="informacoes">
      <div class="info">
        <div class="titulo-e-botao">
          <div class="titulo">Transportes de Hoje</div>
        </div>
        <div v-if="transportesDoDia.length === 0" class="no-data-message">
          Não há transportes no momento
        </div>
        <Tabela v-else :colunas="colunas" :dados="transportesDoDia" />
      </div>

      <div class="teste">
        <div class="titulo-e-botao">
          <div class="titulo">Incidentes por dia</div>
        </div>
        <div class="canvas-wrapper">
          <canvas id="incidentes-chart"></canvas>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Chart from "chart.js/auto";
import Card from "../components/CardComponent/Card.vue";
import apiService from "@/services/apiService";
import Tabela from "@/components/TabelaComponent/TabelaComponent.vue";

export default {
  name: "Home",
  data() {
    return {
      colunas: [
        "N° do Transporte",
        "Paciente",
        "Origem",
        "Prioridade",
        "Status",
      ],
      dados: [],
      transportesDoDia: [],
      pendentes: 0,
      emTransporte: 0,
      concluidos: 0,
      userId: null,
      userRole: null,
      incidentesPorDia: {},
    };
  },
  components: {
    Card,
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
      this.userId = localStorage.getItem("userId");
      this.userRole = localStorage.getItem("userRole");

      if (!this.userId || !this.userRole) {
        console.error("userId ou userRole não encontrados no localStorage.");
        return;
      }

      await apiService
        .getTransportes()
        .then((response) => {
          this.dados = response.data;

          const today = new Date().toISOString().split("T")[0];

          if (this.userRole === "maqueiro") {
            this.transportesDoDia = this.dados
              .filter((transporte) => {
                const transporteDate = new Date(transporte.data)
                  .toISOString()
                  .split("T")[0];
                return (
                  transporteDate === today &&
                  transporte.maqueiro_id == this.userId
                );
              })
              .map((transporte) => ({
                NumeroTransporte: transporte.id,
                Paciente: transporte.paciente.nome,
                Origem: transporte.origem.nomeLocal,
                Prioridade: this.formatPrioridade(transporte.prioridade),
                StatusTransporte: this.formatStatus(transporte.status),
              }));

            this.pendentes = this.dados.filter(
              (transporte) =>
                transporte.maqueiro_id == this.userId &&
                transporte.status === "pendente"
            ).length;
            this.emTransporte = this.dados.filter(
              (transporte) =>
                transporte.maqueiro_id == this.userId &&
                transporte.status !== "concluido" &&
                transporte.status !== "pendente"
            ).length;
            this.concluidos = this.dados.filter(
              (transporte) =>
                transporte.maqueiro_id == this.userId &&
                transporte.status === "concluido"
            ).length;
          } else {
            this.transportesDoDia = this.dados
              .filter((transporte) => {
                const transporteDate = new Date(transporte.data)
                  .toISOString()
                  .split("T")[0];
                return transporteDate === today;
              })
              .map((transporte) => ({
                NumeroTransporte: transporte.id,
                Paciente: transporte.paciente.nome,
                Origem: transporte.origem.nomeLocal,
                Prioridade: this.formatPrioridade(transporte.prioridade),
                StatusTransporte: this.formatStatus(transporte.status),
              }));

            this.pendentes = this.dados.filter(
              (transporte) => transporte.status === "pendente"
            ).length;
            this.emTransporte = this.dados.filter(
              (transporte) =>
                transporte.status !== "concluido" &&
                transporte.status !== "pendente"
            ).length;
            this.concluidos = this.dados.filter(
              (transporte) => transporte.status === "concluido"
            ).length;
          }
        })
        .catch((error) => {
          console.error("Erro ao obter transportes:", error);
        });
    },

    async getIncidentes() {
      await apiService
        .getIncidentesTotal()
        .then((response) => {
          this.dados = response.data;
          this.incidentesPorDia = this.countIncidentesPorDia(this.dados);
          this.$nextTick(() => {
            this.renderIncidentesChart();
          });
        })
        .catch((error) => {
          console.error("Erro ao obter incidentes:", error);
        });
    },

    countIncidentesPorDia(incidentes) {
      const incidentesPorDia = {};
      this.userId = localStorage.getItem("userId");
      this.userRole = localStorage.getItem("userRole");

      incidentes.forEach((incidente) => {
        const data = new Date(incidente.created_at).toLocaleDateString();

        if (
          this.userRole === "maqueiro" &&
          incidente.registrado_por == this.userId
        ) {
          if (incidentesPorDia[data]) {
            incidentesPorDia[data]++;
          } else {
            incidentesPorDia[data] = 1;
          }
        } else if (this.userRole === "admin") {
          if (incidentesPorDia[data]) {
            incidentesPorDia[data]++;
          } else {
            incidentesPorDia[data] = 1;
          }
        }
      });

      return incidentesPorDia;
    },

    renderIncidentesChart() {
      const labels = Object.keys(this.incidentesPorDia);
      const data = Object.values(this.incidentesPorDia);

      const canvas = document.getElementById("incidentes-chart");
      const ctx = canvas.getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Incidentes por dia",
              data: data,
              backgroundColor: "#0670AC",
              borderColor: "#0670AC",
              borderWidth: 2,
              borderRadius: 5,
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
          plugins: {
            legend: {
              display: false, // Remover a legenda (opcional)
            },
          },
          layout: {
            padding: 10,
          },
          responsive: true,
          maintainAspectRatio: false,
          barThickness: 40,
        },
      });
    },
  },
  mounted() {
    this.getTransportes();
    this.getIncidentes();
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

.home {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.titulo-pag {
  font-size: 20px;
  font-weight: bold;
  align-self: flex-start;
  color: white;
}

.cards {
  margin-top: 10px;
  width: 100%;
  display: flex;
  flex-direction: row;
  gap: 65px;
  justify-content: center;
  margin-bottom: 10px;
}

.informacoes {
  margin-top: 20px;
  width: 100%;
  display: flex;
  flex-direction: row;
  gap: 30px;
  justify-content: center;
}

.info {
  border-radius: 10px;
  width: 60%;
  height: 60vh;
  background-color: white;
  box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-direction: column;
  gap: 30px;
  align-items: center;
}

.teste {
  display: flex;
  flex-direction: column;
  gap: 30px;
  align-items: center;

  border-radius: 10px;
  width: 40%;
  height: 60vh;
  background-color: white;
  box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.2);
}

.canvas-wrapper {
  width: 100%;
  height: 100%; /* or set a fixed height */
}

.titulo-e-botao {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  padding: 20px 20px;
  box-sizing: border-box;
}

.titulo {
  font-weight: bold;
  font-size: 20px;
}

@media (max-width: 1700px) {
  .info,
  .teste {
    height: 55vh;
  }
}

@media (max-width: 1024px) {
  .cards {
    flex-direction: column;
    align-items: center;
  }

  .info,
  .teste {
    width: 100%;
  }
}

@media (max-width: 768px) {
  .titulo-pag {
    font-size: 16px;
  }

  .cards {
    flex-direction: column;
    align-items: center;
    gap: 10px;
  }

  .info,
  .teste {
    width: 100%;
  }

  .informacoes {
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }

  .titulo {
    font-size: 18px;
  }
}

@media (max-width: 480px) {
  .home {
    padding: 10px;
  }

  .titulo-pag {
    font-size: 14px;
  }

  .ver-tudo {
    padding: 8px 12px;
    font-size: 14px;
  }
}
</style>
