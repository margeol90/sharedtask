<template>
    <div class="m-auto p-3">
      <!-- Logo / App Name -->
      <div class="flex flex-col items-center mb-8">
        <img src="/logo.png" alt="Logo" class="w-20 h-20 mb-2 md:w-24 md:h-24"/>
        <h1 class="text-3xl md:text-4xl font-bold text-indigo-700">CoupleTasks</h1>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-5 w-[50%] m-auto">
        <div>
          <label class="block mb-1 font-medium text-gray-700">Name</label>
          <input
            v-model="name"
            type="text"
            required
            placeholder="Your name"
            class="w-full border border-indigo-700 rounded-xl text-indigo-700 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>

        <div>
          <label class="block mb-1 font-medium text-gray-700">Email</label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="you@example.com"
            class="w-full border border-indigo-700 rounded-xl text-indigo-700 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>

        <div>
          <label class="block mb-1 font-medium text-gray-700">Password</label>
          <input
            v-model="password"
            type="password"
            required
            placeholder="••••••••"
            class="w-full border border-indigo-700 rounded-xl text-indigo-700 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>
        <div>
          <label class="block mb-1 font-medium text-gray-700">Confirm assword</label>
          <input
            v-model="password_confirmation"
            type="password"
            required
            placeholder="••••••••"
            class="w-full border border-indigo-700 rounded-xl text-indigo-700 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          @click="submit"
          class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition disabled:opacity-50"
        >
          {{ loading ? 'Signing up...' : 'Sign Up' }}
        </button>

        <p class="text-sm text-center text-gray-500 mt-2">
          Already have an account?
          <router-link to="/login" class="text-indigo-600 font-medium hover:underline">Login</router-link>
        </p>

        <p v-if="error" class="text-red-500 mt-2 text-center">{{ error }}</p>
      </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const name = ref('')
const email = ref('')
const password = ref('')
const password_confirmation = ref('')
const error = ref('')

const auth = useAuthStore()
const router = useRouter()

const submit = async () => {
  error.value = ''
  try {
    await auth.register(name.value, email.value, password.value, password_confirmation.value)
    router.push('/dashboard')
  } catch (e) {
    error.value = e.response?.data?.message || 'Registration failed'
  }
}
</script>
