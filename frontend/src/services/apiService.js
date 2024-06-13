// Objetivo: centralizar as chamadas de API em um único arquivo
import axios from 'axios';

const baseURL = "http://localhost:8000/api";

const apiService = axios.create({
    baseURL,
    headers: {
        'Content-Type': 'application/json'
    }
});

export default {
    async login(credentials) {
        return await apiService.post('/login', credentials);
    },

    async getLocais() {
        return await apiService.get('/locais');
    },

    async getPacientes() {
        return await apiService.get('/pacientes');
    },

    async getTransportes() {
        return await apiService.get('/transportes');
    },

    async getTransportesDisponiveis(id) {
        return await apiService.get(`/transportes/${id}/disponiveis`);
    },
    async getIncidentes() {
        return await apiService.get('/incidentes');
    },

    async criarSolicitacao(solicitacao) {
        return await apiService.post('/solicitacoes', solicitacao);
    },

    async cadastrarMaqueiros(maqueiros) {
        return await apiService.post('/users/maqueiro', maqueiros);
    },

    async resetSenha(email) {
        return await apiService.post('/password/temporary', email);
    },

    async trocarSenha(data, token) {
        return await apiService.post('/reset-password', data, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });
    },

    async getMaqueiros() {
        return await apiService.get('/users');
    },

    async desativarUser(id) {
        return await apiService.post(`/users/${id}/deactivate`);
    },

    async ativarUser(id) {
        return await apiService.post(`/users/${id}/activate`);
    },

    async updateUser(id, user) {
        return await apiService.put(`/users/${id}`, user);
    },

    async aceitarTransporte(transporteId, maqueiroId) {
        const response = await apiService.put(`/solicitacoes/${transporteId}/aceitar`, {
            maqueiro_id: maqueiroId
        });
        return response.data;
    },
    async recusarTransporte(transporteId, maqueiroiId) {
        const response = await apiService.post(`/solicitacoes/${transporteId}/recusar`, {
            maqueiroId: maqueiroiId
        });
        return response.data;
    }

};
