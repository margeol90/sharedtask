<template>
  <div class="flex flex-col items-center justify-center min-h-screen">
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome {{ userName }}</h1>
    <p class="text-gray-600 mb-8">Active account: {{ accountName }}</p>
    <div>
      <div v-if="lists.length" class="p-5 border border-gray-500 rounded-xl">
        <h2 class="text-xl bold text-green-800">My Shopping Lists</h2>
        <div v-for="list in lists">
          <p 
          @click="router.push(`shopping-list/${list.id}`)"
          class="text-green-500 hover:text-green-700 hover:text-xl">
            {{ list.name }}
          </p>
        </div>
      </div>
      <div v-else>
        <button  class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700" @click="router.push('/shopping-list')">Add List +</button>
      </div>
    </div>
    <button @click="handleLogout" >Logout</button>
  </div>
</template>

<script setup>
    import { computed, onMounted } from 'vue'
    import { useAuthStore } from '../stores/auth'
    import { useRouter } from 'vue-router'
    import { useShoppingListStore } from '../stores/shoppingLists'

    const store = useShoppingListStore()
    const lists = computed(() => store.lists)
    
    onMounted(() => {
      store.fetchLists()
    })
    const auth = useAuthStore()
    const router = useRouter()
    
    const userName = computed(() => auth.user?.name)
    const accountName = computed(() => auth.activeAccount?.name)
    
    async function handleLogout() {
        await auth.logout()
        router.push('/login')
    }
</script>
