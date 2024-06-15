<template>
  <div class="maqueiros">
    <div class="titulo">
      <h2>Maqueiros Cadastrados</h2>
      <button class="btn-adicionar" @click="abrirFormulario">
        Adicionar Maqueiro
      </button>
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
      <div v-if="maqueiros.length === 0" class="no-data-message">
        Não há registros no momento
      </div>
      <Tabela
        v-else
        :colunas="colunas"
        :dados="maqueiros"
        :acoes="acoes"
        @executar-acao="executarAcao"
      />
    </div>

    <modal v-if="mostrarFormulario" @close="fecharFormulario">
      <template v-slot:header>
        <h3>{{ isEditando ? "Editar Maqueiro" : "Adicionar Maqueiro" }}</h3>
      </template>
      <template v-slot:body>
        <form
          @submit.prevent="
            isEditando ? atualizarMaqueiro() : adicionarMaqueiro()
          "
        >
          <div class="form-group">
            <label for="nome">Nome</label>
            <input
              type="text"
              id="nome"
              v-model="maqueiroAtual.nome"
              required
            />
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              v-model="maqueiroAtual.email"
              required
            />
          </div>

          <div class="form-actions">
            <button type="submit">
              {{ isEditando ? "Salvar" : "Adicionar" }}
            </button>
            <button type="button" @click="fecharFormulario">Cancelar</button>
          </div>
        </form>
      </template>
    </modal>
  </div>
</template>

<script>
import Modal from "../components/ModalComponent/ModalComponent.vue";
import Tabela from "@/components/TabelaComponent/TabelaComponent.vue";
import apiService from "@/services/apiService";
import Swal from "sweetalert2";

export default {
  name: "Maqueiros",
  components: {
    Modal,
    Tabela,
  },
  data() {
    return {
      maqueiros: [],
      mostrarFormulario: false,
      isEditando: false,
      loading: true,
      skeletonRows: 10,
      maqueiroAtual: {
        nome: "",
        email: "",
      },
      colunas: ["ID", "Nome", "Email", "Status", "Ações"],
      acoes: [
        {
          tela: "Inativar",
          action: "inativar",
          contexto: "ativo",
          icone: "fa-solid fa-user-minus",
          class: "acao-button",
        },
        {
          texto: "Ativar",
          action: "ativar",
          contexto: "inativo",
          icone: "fa-solid fa-user-check",
          class: "acao-button",
        },
        {
          texto: "Editar",
          action: "editar",
          contexto: "todos",
          icone: "fa-solid fa-pen-to-square",
          class: "acao-button",
        },
      ],
    };
  },
  mounted() {
    this.buscarMaqueiros();
  },
  methods: {
    abrirFormulario() {
      this.mostrarFormulario = true;
      this.isEditando = false;
    },
    fecharFormulario() {
      this.mostrarFormulario = false;
      this.isEditando = false;
      this.maqueiroAtual = {
        nome: "",
        email: "",
      };
    },
    async adicionarMaqueiro() {
      try {
        await apiService.cadastrarMaqueiros(this.maqueiroAtual);
        this.fecharFormulario();
        await this.buscarMaqueiros();
        Swal.fire({
          title: "Sucesso!",
          text: "O maqueiro foi adicionado com sucesso!",
          icon: "success",
          confirmButtonText: "OK",
        });
      } catch (error) {
        console.error(error);
        Swal.fire({
          title: "Erro!",
          text: "Não foi possível adicionar o maqueiro.",
          icon: "error",
          confirmButtonText: "Fechar",
        });
      }
    },
    async buscarMaqueiros() {
      try {
        const response = await apiService.getMaqueiros();
        const originalData = response.data.filter(
          (item) => item.role === "maqueiro"
        );

        const transformedData = originalData.map((item) => ({
          ID: item.id,
          Nome: item.nome,
          Email: item.email,
          Status: item.status,
        }));

        this.maqueiros = transformedData;

        setTimeout(() => {
          this.loading = false;
        }, 1200);
      } catch (error) {
        console.error(error);
      }
    },
    async executarAcao(acao, maqueiro) {
      switch (acao) {
        case "inativar":
          await this.confirmarInativacao(maqueiro);
          break;
        case "ativar":
          await this.confirmarAtivacao(maqueiro);
          break;
        case "editar":
          this.editarMaqueiro(maqueiro);
          break;
      }
    },
    async confirmarInativacao(maqueiro) {
      try {
        const result = await Swal.fire({
          title: "Tem certeza?",
          text: `Você realmente deseja inativar o maqueiro ${maqueiro.Nome}?`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, inativar!",
          cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
          await this.inativarMaqueiro(maqueiro);
        }
      } catch (error) {
        console.error(error);
      }
    },
    async confirmarAtivacao(maqueiro) {
      try {
        const result = await Swal.fire({
          title: "Tem certeza?",
          text: `Você realmente deseja ativar o maqueiro ${maqueiro.Nome}?`,
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Sim, ativar!",
          cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
          await this.ativarMaqueiro(maqueiro);
        }
      } catch (error) {
        console.error(error);
      }
    },
    async inativarMaqueiro(maqueiro) {
      try {
        await apiService.desativarUser(maqueiro.ID);
        await this.buscarMaqueiros();
        Swal.fire("Inativado!", "O maqueiro foi inativado.", "success");
      } catch (error) {
        console.error(error);
        Swal.fire("Erro!", "Houve um erro ao inativar o maqueiro.", "error");
      }
    },
    async ativarMaqueiro(maqueiro) {
      try {
        await apiService.ativarUser(maqueiro.ID);
        await this.buscarMaqueiros();
        Swal.fire("Ativado!", "O maqueiro foi ativado.", "success");
      } catch (error) {
        console.error(error);
        Swal.fire("Erro!", "Houve um erro ao ativar o maqueiro.", "error");
      }
    },
    editarMaqueiro(maqueiro) {
      this.maqueiroAtual = {
        ID: maqueiro.ID,
        nome: maqueiro.Nome,
        email: maqueiro.Email,
      };
      this.isEditando = true;
      this.mostrarFormulario = true;
    },
    async atualizarMaqueiro() {
      try {
        await apiService.atualizarMaqueiro(
          this.maqueiroAtual.ID,
          this.maqueiroAtual
        );
        this.fecharFormulario();
        await this.buscarMaqueiros();
        Swal.fire({
          title: "Sucesso!",
          text: "O maqueiro foi atualizado com sucesso!",
          icon: "success",
          confirmButtonText: "OK",
        });
      } catch (error) {
        console.error(error);
        Swal.fire({
          title: "Erro!",
          text: "Não foi possível atualizar o maqueiro.",
          icon: "error",
          confirmButtonText: "Fechar",
        });
      }
    },
    async atualizarMaqueiro() {
      try {
        await apiService.updateUser(this.maqueiroAtual.ID, this.maqueiroAtual);
        this.fecharFormulario();
        await this.buscarMaqueiros();
        Swal.fire({
          title: "Sucesso!",
          text: "O maqueiro foi atualizado com sucesso!",
          icon: "success",
          confirmButtonText: "OK",
        });
      } catch (error) {
        console.error(error);
        Swal.fire({
          title: "Erro!",
          text: "Não foi possível atualizar o maqueiro.",
          icon: "error",
          confirmButtonText: "Fechar",
        });
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


.maqueiros {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: auto;
  gap: 20px;
  padding: 20px;
  width: 90%;
  height: 80vh;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
}

.titulo {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  color: #333;
  margin-bottom: 20px;
}

.btn-adicionar {
  padding: 10px 20px;
  font-size: 16px;
  color: #fff;
  background-color: #3182ce;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-adicionar:hover {
  background-color: #0056b3;
}

.form-group {
  display: flex;
  flex-direction: column;
  width: 100%;
  margin-bottom: 15px;
}

label {
  font-size: 16px;
  margin-bottom: 5px;
  color: #555;
}

input[type="text"],
input[type="email"],
input[type="password"] {
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  transition: border-color 0.3s;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {
  border-color: #007bff;
  outline: none;
}

.form-actions {
  display: flex;
  justify-content: space-between;
  margin-top: 15px;
}

.form-actions button {
  padding: 10px 25px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.form-actions button[type="submit"] {
  background-color: #3182ce;
  color: #fff;
}

.form-actions button[type="submit"]:hover {
  background-color: #2b72b4;
}

.form-actions button[type="button"] {
  background-color: #dc3545;
  color: #fff;
}

.form-actions button[type="button"]:hover {
  background-color: #c82333;
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

@media (max-width: 768px) {
  .maqueiros {
    width: 100%;
    padding: 10px;
  }

  .btn-adicionar {
    align-self: center;
  }
}

@media (max-width: 480px) {
  .maqueiros {
    width: 100%;
    padding: 15px;
  }

  input[type="text"],
  input[type="email"],
  input[type="password"],
  button {
    width: 100%;
    padding: 10px;
  }
}
</style>