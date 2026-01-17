<template>
    <div>
        <div class="flex flex-col items-center mb-8">
        <img src="/logo.png" alt="Logo" class="w-20 h-20 mb-2 md:w-24 md:h-24"/>
        <h1 class="text-3xl md:text-4xl font-bold text-indigo-700">CoupleTasks</h1>
        </div>
        <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
            <label class="block mb-1 font-medium">Email</label>
            <input
            v-model="email"
            type="email"
            required
            placeholder="you@example.com"
            class="w-full text-indigo-700 border rounded-lg px-3 py-2 border-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
        </div>

        <div>
            <label class="block mb-1 font-medium">Password</label>
            <input
            v-model="password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full text-indigo-700 border border-indigo-700 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
        </div>

        <button
            type="submit"
            :disabled="loading"
            class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition disabled:opacity-50"
        >
            {{ loading ? 'Logging in...' : 'Login' }}
        </button>

        <p class="text-sm text-center mt-4">
            Don't have an account?
            <router-link to="/register" class="text-indigo-600 hover:underline">Register</router-link>
        </p>
        </form>

        <p v-if="error" class="text-red-500 mt-2 text-center">{{ error }}</p>
    </div>
</template>

<script>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const email = ref('')
    const password = ref('')
    const error = ref(null)
    const loading = ref(false)

    const auth = useAuthStore()
    const router = useRouter()

    const handleLogin = async () => {
      loading.value = true
      error.value = null

      try {
        await auth.login(email.value, password.value)
        router.push('/dashboard')
      } catch (e) {
        error.value = e.response?.data?.message || 'Login failed'
      } finally {
        loading.value = false
      }
    }

    return { email, password, handleLogin, error, loading }
  },
}
</script>

<style scoped>
/* optional: subtle animations for inputs */
input:focus {
  transform: scale(1.01);
  transition: transform 0.1s;
}
</style>
