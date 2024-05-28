import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import SolicitarTransporte from '../views/SolicitarTransporteView.vue';
import Incidentes from '../views/HistIncidentesView.vue';
import TodasSolicitacoes from '../views/TodosTransportesView.vue';
import LoginView from '../views/LoginView.vue';
import authService from '@/services/authService';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,

    },
    {
      path: '/home',
      name: 'home',
      component: HomeView,
      meta: { requiresAuth: true }
    },
    {
      path: '/solicitar-transporte',
      name: 'solicitarTransporte',
      component: SolicitarTransporte,
      meta: { requiresAuth: true }
    },
    {
      path: '/incidentes',
      name: 'incidentes',
      component: Incidentes,
      meta: { requiresAuth: true }
    },
    {
      path: '/todas-solicitacoes',
      name: 'todasSolicitacoes',
      component: TodasSolicitacoes,
      meta: { requiresAuth: true }
    },
  ]
})


router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // Verifica se o usuário está autenticado
    if (!authService.isAuthenticated()) {
      // Se não estiver autenticado, redireciona para o login
      next({ name: 'login' });
    } else {
      // Se estiver autenticado, continua para a rota solicitada
      next();
    }
  } else {
    // Para rotas que não requerem autenticação
    next();
  }
});

export default router
