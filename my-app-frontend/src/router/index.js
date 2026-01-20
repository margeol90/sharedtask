import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import LoginView from '../views/LoginView.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'
import ShoppingListCreate from '../views/ShoppingListCreate.vue'
import ShoppingListEdit from '../views/ShoppingListEdit.vue'

export const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      component: LoginView
    },
    {
      path: '/register',
      component: Register
    },
    {
      path: '/dashboard',
      component: Dashboard,
      meta: { requiresAuth: true },
    },
    {
      path: '/shopping-list',
      component: ShoppingListCreate,
      meta: { requiresAuth: true },
    },
    {
      path: '/shopping-list/:id',
      component: ShoppingListEdit,
      meta: { requiresAuth: true },
    },
  ]
})

router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  // Wait until auth state is initialized
  if (!auth.isReady) {
    await auth.init()
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login')
  }

  next()
})
