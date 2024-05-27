<template>
  <div class="todosTransportes">
    <div class="header">
      <h2>Todos os Transportes</h2>
      <input type="text" v-model="search" placeholder="Pesquisar..." />
    </div>
    <Tabela :colunas="colunas" :dados="filteredTransportes" />
  </div>
</template>

<script>
import Tabela from "@/components/TabelaComponent/TabelaComponent.vue";

export default {
  name: "TodosTransportes",
  data() {
    return {
      search: "",
      colunas: [
        "N° do Transporte",
        "Paciente",
        "Origem",
        "Destino",
        "Data",
        "Hora",
        "Status",
      ],
      dados: [
        {
          "N° do Transporte": 1,
          Paciente: "João Silva",
          Origem: "UTI",
          Destino: "Raio-X",
          Data: "2024-05-10",
          Hora: "14:30",
          Status: "Concluído",
        },
        {
          "N° do Transporte": 2,
          Paciente: "Maria Souza",
          Origem: "Enfermaria",
          Destino: "Fisioterapia",
          Data: "2024-05-11",
          Hora: "10:15",
          Status: "Concluído",
        },
        {
          "N° do Transporte": 3,
          Paciente: "Carlos Lima",
          Origem: "Centro Cirúrgico",
          Destino: "Recuperação",
          Data: "2024-05-12",
          Hora: "09:00",
          Status: "Concluído",
        },
      ],
    };
  },
  computed: {
    filteredTransportes() {
      return this.dados.filter((transporte) => {
        return Object.values(transporte).some((val) =>
          String(val).toLowerCase().includes(this.search.toLowerCase())
        );
      });
    },
  },
  components: {
    Tabela,
  },
};
</script>

<style scoped>
.todosTransportes {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  margin: auto;
  gap: 20px;
  padding: 20px;
  width: 100%;
  height: 80vh;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.header h2 {
  margin: 0;
  font-size: 24px;
  color: #000;
}

.header input {
  width: 300px;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s;
}

.header input:focus {
  border-color: #007bff;
  outline: none;
}
</style>
