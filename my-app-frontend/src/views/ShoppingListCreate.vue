<template>
    <div class="flex flex-col items-center justify-center min-h-screen">
        <form @submit.prevent="addList" class="space-y-5 w-[50%] m-auto">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Name</label>
                <input
                    v-model="name"
                    type="text"
                    required
                    placeholder="Name List"
                    class="w-full border border-indigo-700 rounded-xl text-indigo-700 px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                />
            </div>
        </form>
    </div>
</template>

<script setup>
    import { ref } from 'vue'
    import { useAuthStore } from '../stores/auth'
    import { useRouter } from 'vue-router'
    import { useShoppingListStore } from '../stores/shoppingLists'
    
    const name = ref('')

    const auth = useAuthStore()
    const store = useShoppingListStore()
    const router = useRouter()
    
    async function addList() {
        const response = await store.createList(name.value)
        console.log(response);
        router.push(`/shopping-list/${response.id}`)
    }
</script>
