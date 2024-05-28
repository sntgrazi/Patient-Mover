<template>
  <div class="home">
    <div class="titulo-pag">Home</div>
    <div class="cards">
      <Card
        title="Aguardando transporte"
        quantity="23"
        icon="fa-clock-rotate-left"
      />
      <Card title="Em transporte" quantity="10" icon="fa-truck-medical" />
      <Card title="Concluídos" quantity="58" icon="fa-check" />
    </div>

    <div class="informacoes">
      <div class="info">
        <div class="titulo-e-botao">
          <div class="titulo">Emergências de Transporte</div>
          <button class="ver-tudo" @click="handleVerTudo">Ver Tudo</button>
        </div>
        <Tabela :colunas="colunas" :dados="dados" />
      </div>

      <div class="teste">
        <div class="titulo-e-botao">
          <div class="titulo">Incidentes p/dia</div>
          <button class="ver-tudo" @click="handleVerTudo">Ver Tudo</button>
        </div>
        <Chart />
      </div>
    </div>
  </div>
</template>

<script>
import Chart from "@/components/ChartComponent/ChartComponent.vue";
import Card from "../components/CardComponent/Card.vue";
import Tabela from "../components/TabelaComponent/TabelaComponent.vue";

export default {
  name: "Home",
  data() {
    return {
      colunas: ["ID", "Paciente", "Prioridade"],
      dados: [
        { ID: 1, Paciente: "João Silva", Prioridade: "Baixa" },
        { ID: 2, Paciente: "Maria Souza", Prioridade: "Média" },
        { ID: 3, Paciente: "Carlos Lima", Prioridade: "Alta" },
        // Adicione mais dados conforme necessário
      ],
    };
  },
  components: {
    Card,
    Chart,
    Tabela,
  },
  methods: {
    handleVerTudo() {
      alert("Mais detalhes sobre transporte!");
    },
  },
};
</script>

<style scoped>
.home {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 20px;
}

.titulo-pag {
  font-size: 18px;
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

.titulo-e-botao {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  padding: 10px 20px;
  box-sizing: border-box;
}

.titulo {
  font-weight: bold;
  font-size: 20px;
}

.ver-tudo {
  background-color: #0670ac;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.ver-tudo:hover {
  background-color: #005999;
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