<template>
  <div class="tabela">
    <table>
      <thead>
        <tr>
          <th v-for="coluna in colunas" :key="coluna">{{ coluna }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="linha in dados" :key="linha.id">
          <td v-for="coluna in colunas" :key="coluna">
            <span :class="getPrioridadeClasse(linha[coluna])">
              {{ linha[coluna] }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
  
  <script>
export default {
  name: "Tabela",
  props: {
    colunas: {
      type: Array,
      required: true,
    },
    dados: {
      type: Array,
      required: true,
    },
  },
  methods: {
    getPrioridadeClasse(prioridade) {
      switch (prioridade) {
        case "Baixa":
          return "prioridade-baixa";
        case "Média":
          return "prioridade-media";
        case "Alta":
          return "prioridade-alta";
        default:
          return "";
      }
    },
  },
};
</script>
  
  <style>
.tabela {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  border: none;
  color: #a0aec0;
}

td {
  color: #2d3748;
}

th,
td {
  border-bottom: 1px solid #ddd;
  padding: 10px;
  text-align: left;
  font-size: 18px;
  font-weight: 500;
}

.prioridade-baixa {
  background-color: #14860a;
  color: white;
  padding: 5px 15px;
  border-radius: 5px;
  display: inline-block; 
}

.prioridade-media {
  background-color: #f87211; 
  color: white;
  padding: 5px 15px;
  border-radius: 5px;
  display: inline-block; 
}

.prioridade-alta {
  background-color: #cf0000; 
  color: white;
  padding: 5px 15px;
  border-radius: 5px;
  display: inline-block;
}
</style>  