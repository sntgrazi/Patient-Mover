<template>
  <div class="histIncidentes">
    <div class="header">
      <h2>Histórico de Incidentes</h2>
      <input type="text" v-model="search" placeholder="Pesquisar..." />
    </div>
    <Tabela :colunas="colunas" :dados="filteredIncidentes" />
  </div>
</template>

<script>
import Tabela from "@/components/TabelaComponent/TabelaComponent.vue";

export default {
  name: "HistIncidentes",
  data() {
    return {
      search: "",
      colunas: [
        "Id",
        "Paciente",
        "N° do Transporte",
        "Tipo",
        "Descrição",
        "Data/Hora",
        "Registrado por",
      ],
      dados: [
        {
          Id: 1,
          Paciente: "João Silva",
          "N° do Transporte": 101,
          Tipo: "Queda",
          Descrição: "Paciente escorregou ao sair da maca",
          "Data/Hora": "2024-05-10 14:30:00",
          "Registrado por": "Dr. Eduardo",
        },
        {
          Id: 2,
          Paciente: "Maria Souza",
          "N° do Transporte": 102,
          Tipo: "Atraso",
          Descrição: "Transporte atrasado devido à manutenção do elevador",
          "Data/Hora": "2024-05-11 10:15:00",
          "Registrado por": "Enfermeira Ana",
        },
        {
          Id: 3,
          Paciente: "Carlos Lima",
          "N° do Transporte": 103,
          Tipo: "Queda",
          Descrição: "Paciente caiu ao ser transferido para a cadeira de rodas",
          "Data/Hora": "2024-05-12 09:00:00",
          "Registrado por": "Técnico José",
        },
      ],
    };
  },
  computed: {
    filteredIncidentes() {
      return this.dados.filter((incidente) => {
        return Object.values(incidente).some((val) =>
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
.histIncidentes {
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

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

table th,
table td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: left;
}

table th {
  background-color: #f4f4f4;
  font-weight: bold;
}

table tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}
</style>
