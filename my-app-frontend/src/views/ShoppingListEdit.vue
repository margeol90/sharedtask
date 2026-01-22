<template>
  <div>
    <h2>{{ shoppingListName }}</h2>

    <input v-model="newItemName" placeholder="Add item" @keyup.enter="addItem" />
    <button @click="addItem">Add</button>

    <ul>
      <li v-for="item in items" :key="item.id">
        <input type="checkbox" v-model="item.is_completed" @change="toggleItem(item)" />
        <span :class="{ completed: item.is_completed }">{{ item.name }}</span>
        <button @click="deleteItem(item)">Delete</button>
      </li>
    </ul>

    <button v-if="selectedIds.length" @click="bulkDelete">Delete Selected</button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      items: [],
      newItemName: '',
      selectedIds: [],
      shoppingListId: null,
      shoppingListName: '',
    };
  },

  computed: {
    completedItems() {
      return this.items.filter(i => i.is_completed);
    },
  },

  methods: {
    async fetchShoppingList() {
      if (!this.shoppingListId) return;

      try {
        const { data } = await axios.get(`/api/shopping-lists/${this.shoppingListId}`);
        this.shoppingListName = data.name;
        this.items = data.items; // assuming API returns items
      } catch (e) {
        console.error('Failed to fetch shopping list', e);
      }
    },

    async addItem() {
      if (!this.newItemName.trim()) return;

      const { data } = await axios.post(
        `/api/shopping-lists/${this.shoppingListId}/items`,
        { name: this.newItemName }
      );

      this.items.push(data.item);
      this.newItemName = '';
    },

    async toggleItem(item) {
      const { data } = await axios.patch(`/api/shopping-items/${item.id}/toggle`);
      Object.assign(item, data.item);
    },

    async deleteItem(item) {
      await axios.delete(`/api/shopping-items/${item.id}`);
      this.items = this.items.filter(i => i.id !== item.id);
    },

    async bulkDelete() {
      await axios.delete('/api/shopping-items/bulk', {
        data: { ids: this.selectedIds },
      });
      this.items = this.items.filter(i => !this.selectedIds.includes(i.id));
      this.selectedIds = [];
    },
  },

  mounted() {
    // Use Vue Router route param for list ID
    this.shoppingListId = this.$route.params.id;

    if (!this.shoppingListId) {
      console.warn('No shopping list ID found in route');
      return;
    }

    this.fetchShoppingList();
  },
};
</script>

<style scoped>
.completed {
  text-decoration: line-through;
}
</style>
