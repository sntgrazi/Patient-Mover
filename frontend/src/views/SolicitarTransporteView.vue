<template>
    <div class="solicitarTransporte">
      <div class="titulo">
        <h2>Solicitar Transporte</h2>
        <span>Criar nova solicitação</span>
      </div>
      <div class="formulario">
        <div class="form-group-inline">
          <div class="form-group" style="flex: 6;">
            <label for="nome">Nome do Paciente</label>
            <input type="text" id="nome" name="nome" />
          </div>
          <div class="form-group" style="flex: 4;">
            <label for="status">Prioridade</label>
            <select id="status" name="status">
              <option value="estavel">Alta</option>
              <option value="critico">Média</option>
              <option value="emergencia">Baixa</option>
            </select>
          </div>
        </div>
        <div class="form-group-inline">
          <div class="form-group">
            <label for="origem">Localização Atual</label>
            <input type="text" id="origem" name="origem" />
          </div>
          <div class="form-group">
            <label for="destino">Destino</label>
            <input type="text" id="destino" name="destino" />
          </div>
        </div>
        <div class="form-group-inline">
          <div class="form-group">
            <label for="data">Data</label>
            <input type="date" id="data" name="data" />
          </div>
          <div class="form-group">
            <label for="hora">Hora</label>
            <input type="time" id="hora" name="hora" />
          </div>
        </div>
        <div class="form-group">
          <label for="informacoes">Informações</label>
          <textarea id="informacoes" name="informacoes"></textarea>
        </div>
        <button>Criar Solicitação</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "SolicitarTransporte",
  };
  </script>
  
  <style scoped>
  .solicitarTransporte {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin: auto;
    gap: 20px;
    padding: 20px;
    width: 70%;
    height: auto;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
  }
  
  .titulo {
    color: #000000;
    margin: 20px 0;
  }
  
  .titulo span {
    color: #545454;
  }
  
  .formulario {
    margin: 10px 0;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 20px;
    width: 100%;
  }
  
  .form-group,
  .form-group-inline {
    display: flex;
    flex-direction: column;
    width: 100%;
  }
  
  .form-group-inline {
    flex-direction: row;
    justify-content: space-between;
    gap: 20px;
  }
  
  label {
    font-size: 16px;
    margin-bottom: 5px;
    color: #555;
  }
  
  input,
  textarea,
  select {
    padding: 15px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
    transition: border-color 0.3s;
  }
  
  input:focus,
  textarea:focus,
  select:focus {
    border-color: #007bff;
    outline: none;
  }
  
  textarea {
    resize: vertical;
    min-height: 100px;
  }
  
  button {
    padding: 10px 20px;
    font-size: 16px;
    color: #fff;
    background-color: #3182CE;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  
  @media (max-width: 768px) {
    .solicitarTransporte {
      width: 90%;
      padding: 10px;
    }
  
    .form-group-inline {
      flex-direction: column;
    }
  
    .formulario {
      align-items: stretch;
    }
  
    button {
      align-self: center;
    }
  }
  
  @media (max-width: 480px) {
    .solicitarTransporte {
      width: 90%;
      padding: 15px;
    }
  
    input, textarea, select, button {
      width: 100%;
      padding: 10px;
    }
  }
  </style>
  