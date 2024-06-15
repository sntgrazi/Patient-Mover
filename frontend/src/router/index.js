import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import SolicitarTransporte from '../views/SolicitarTransporteView.vue';
import Incidentes from '../views/MaqueiroTransporteView.vue';
import TodasSolicitacoes from '../views/TodosTransportesView.vue';
import LoginView from '../views/LoginView.vue';
import Maqueiros from '../views/MaqueirosView.vue';
import authService from '@/services/authService';
import MaqueiroTransporteView from '../views/MaqueiroTransporteView.vue';
import MeuperfilView from '../views/MeuPerfilView.vue';

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
      meta: { requiresAuth: true, roles: ['admin', 'maqueiro']},
    },
    {
      path: '/solicitar-transporte',
      name: 'solicitarTransporte',
      component: SolicitarTransporte,
      meta: { requiresAuth: true, roles: ['admin']},
    },
    // {
    //   path: '/incidentes',
    //   name: 'incidentes',
    //   component: Incidentes,
    //   meta: { requiresAuth: true, roles: ['admin'] },
    // },
    {
      path: '/todas-solicitacoes',
      name: 'todasSolicitacoes',
      component: TodasSolicitacoes,
      meta: { requiresAuth: true, roles: ['admin'] },
    },
    {
      path: '/maqueiros',
      name: 'maqueiros',
      component: Maqueiros,
      meta: { requiresAuth: true, roles: ['admin'] },
    },
    {
      path: '/maqueiro-transporte',
      name: 'maqueiroTransporte',
      component: MaqueiroTransporteView,
      meta: { requiresAuth: true, roles: ['maqueiro'] },
    },
    {
      path: '/meu-perfil',
      name: 'meuperfil',
      component: MeuperfilView,
      meta: { requiresAuth: true, roles:  ['admin', 'maqueiro'] },
    },
  ],
})


router.beforeEach((to, from, next) => {
  const isAuthenticated = authService.isAuthenticated();
  const userRole = localStorage.getItem('userRole');

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next({ name: 'login' });
    } else if (to.matched.some(record => record.meta.roles && !record.meta.roles.includes(userRole))) {
      next({ name: 'home' }); 
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router
