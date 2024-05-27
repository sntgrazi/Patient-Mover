import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import SolicitarTransporte from '../views/SolicitarTransporteView.vue'
import Incidentes from '../views/HistIncidentesView.vue'
import TodasSolicitacoes from '../views/TodosTransportesView.vue'
import LoginView from '../views/LoginView.vue'  

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/home',
      name: 'home',
      component: HomeView
    },
    {
      path: '/solicitar-transporte',
      name: 'solicitarTransporte',
      component: SolicitarTransporte
    },
    {
      path: '/incidentes',
      name: 'incidentes',
      component: Incidentes
    },
    {
      path: '/todas-solicitacoes',
      name: 'todasSolicitacoes',
      component: TodasSolicitacoes
    },
  ]
})

export default router
