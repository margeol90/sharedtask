<template>
  <div class="flex flex-col items-center justify-center min-h-screen">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome {{ userName }}</h1>
    <p class="text-gray-600 mb-8">Active account: {{ accountName }}</p>
    <button @click="handleLogout" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Logout</button>
  </div>
</template>

<script setup>
    import { computed } from 'vue'
    import { useAuthStore } from '../stores/auth'
    import { useRouter } from 'vue-router'
    
    const auth = useAuthStore()
    const router = useRouter()
    
    const userName = computed(() => auth.user?.name)
    const accountName = computed(() => auth.activeAccount?.name)
    
    async function handleLogout() {
        await auth.logout()
        router.push('/login')
    }
</script>
