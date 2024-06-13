<template>
  <div class="tabela">
    <table>
      <thead>
        <tr>
          <th v-for="coluna in colunas" :key="coluna">{{ coluna }}</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="linha in dados"
          :key="linha['N° do transporte'] || linha.Id"
          @click="linhaClicada(linha)"
          class="tabela-linha"
        >
          <td v-for="(value, coluna) in linha" :key="coluna">
            <span
              v-if="coluna === 'Prioridade' || coluna === 'Status'"
              :class="getClass(coluna, value)"
            ></span>
            <span v-else :class="getClass(coluna, value)">
              {{ value }}
            </span>
          </td>
          <td v-if="acoes">
            <template v-if="isMaqueiroRoute">
              <button
                v-for="botao in getAcoes(linha)"
                :key="botao.label || botao.icone"
                @click.stop="executarAcao(botao.action, linha)"
                :class="botao.class"
              >
                <i :class="botao.icone"></i>
              </button>
            </template>
            <template v-else>
              <button
                v-for="botao in acoes"
                :key="botao.label || botao.icone"
                @click.stop="executarAcao(botao.action, linha)"
                :class="botao.class"
              >
                <span >{{ botao.label }}</span>
              </button>
            </template>
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
    acoes: {
      type: Array,
      required: false,
    },
  },
  computed: {
    isMaqueiroRoute() {
      return this.$route.path.includes("maqueiros");
    },
  },
  methods: {
    getAcoes(linha) {
      if (linha.Status === "Ativo") {
        return this.acoes.filter(
          (acao) => acao.contexto === "ativo" || acao.contexto === "todos"
        );
      } else if (linha.Status === "Inativo") {
        return this.acoes.filter(
          (acao) => acao.contexto === "inativo" || acao.contexto === "todos"
        );
      }
      return [];
    },
    getClass(coluna, value) {
      if (coluna === "StatusTransporte") {
        switch (value) {
          case "Pendente":
            return "status-pendente";
          case "Em Transporte":
            return "status-em-transporte";
          case "Concluido":
            return "status-concluido";
          case "Cancelado":
            return "status-cancelado";
          default:
            return "";
        }
      } else if (coluna === "Prioridade") {
        switch (value) {
          case "Baixa":
            return "prioridade-baixa";
          case "Media":
            return "prioridade-media";
          case "Alta":
            return "prioridade-alta";
          default:
            return "";
        }
      } else if (coluna === "Status") {
        switch (value) {
          case "Ativo":
            return "status-ativo";
          case "Inativo":
            return "status-inativo";
          default:
            return "";
        }
      }
      return "";
    },
    executarAcao(acao, linha) {
      this.$emit("executar-acao", acao, linha);
    },
    linhaClicada(linha) {
      this.$emit("linha-clicada", linha);
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
  text-align: center;
  font-size: 17px;
  font-weight: 500;
}

.tabela-linha:hover {
  cursor: pointer;
  background-color: #f5f5f5;
  transition: background-color 0.3s ease;
}

.prioridade-baixa,
.prioridade-media,
.prioridade-alta,
.status-ativo,
.status-inativo {
  position: relative;
  width: 15px;
  height: 15px;
  border-radius: 50%;
  display: inline-block;
  z-index: 1;
}

.prioridade-baixa::before,
.prioridade-media::before,
.prioridade-alta::before,
.status-ativo::before,
.status-inativo::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 50%;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  z-index: -1;
}

.prioridade-baixa,
.status-ativo {
  background-color: #14860a;
}

.prioridade-baixa::before {
  background-color: rgba(20, 134, 10, 0.486);
  animation: glow-baixa 1.6s infinite;
}

.prioridade-media {
  background-color: #f87211;
}

.prioridade-media::before {
  background-color: rgba(248, 114, 17, 0.5);
  animation: glow-media 1.5s infinite;
}

.prioridade-alta,
.status-inativo {
  background-color: #cf0000;
}

.prioridade-alta::before {
  background-color: rgba(207, 0, 0, 0.466);
  animation: glow-alta 1.3s infinite;
}

@keyframes glow-baixa {
  100% {
    opacity: 0.2;
    transform: translate(-50%, -50%) scale(1);
  }
  50% {
    opacity: 0.4;
    transform: translate(-50%, -50%) scale(1.2);
  }
}

@keyframes glow-media {
  100% {
    opacity: 0.2;
    transform: translate(-50%, -50%) scale(1);
  }
  50% {
    opacity: 0.6;
    transform: translate(-50%, -50%) scale(1.3);
  }
}

@keyframes glow-alta {
  100% {
    opacity: 0.2;
    transform: translate(-50%, -50%) scale(1);
  }
  50% {
    opacity: 0.8;
    transform: translate(-50%, -50%) scale(1.3);
  }
}

.status-pendente,
.status-em-transporte,
.status-concluido,
.status-cancelado {
  color: white;
  padding: 5px 15px;
  border-radius: 5px;
  width: 100%;
  display: inline-block;
}

.status-pendente {
  background-color: rgb(255, 116, 3);
}

.status-em-transporte {
  background-color: blue;
}

.status-concluido {
  background-color: green;
}

.status-cancelado {
  background-color: red;
}

.acao-button {
  background-color: #3182ce;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  padding: 8px 12px;
  margin-right: 5px;
  transition: background-color 0.3s ease;
}

.acao-button:hover {
  background-color: rgb(37, 94, 192);
}

.btn-red {
  background-color: #ee412a;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  padding: 8px 12px;
  margin-right: 5px;
  transition: background-color 0.3s ease;
}

.btn-red:hover {
  background-color: #cc3723c9;
}
</style>
