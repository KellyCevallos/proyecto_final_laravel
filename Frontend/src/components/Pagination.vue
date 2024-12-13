<!-- src/components/Pagination.vue -->
<script setup>

const props = defineProps({
  currentPage: Number,
  lastPage: Number,
  prevPageUrl: String,
  nextPageUrl: String
});

const emit = defineEmits(['pageChange']);

const goToPage = (page) => {
  if (page > 0 && page <= props.lastPage) {
    emit('pageChange', page);
  }
};

const getPageNumber = (url) => {
  if (!url) return null;
  const params = new URLSearchParams(url.split('?')[1]);
  return params.get('page') ? parseInt(params.get('page')) : null;
};
</script>


<template>
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
      <!-- Botón de página anterior -->
      <li class="page-item" :class="{ disabled: !prevPageUrl }">
        <button class="page-link" @click="goToPage(getPageNumber(prevPageUrl))" :disabled="!prevPageUrl">
          Anterior
        </button>
      </li>

      <!-- Página actual / última página -->
      <li class="page-item disabled">
        <span class="page-link">Página {{ currentPage }} de {{ lastPage }}</span>
      </li>

      <!-- Botón de página siguiente -->
      <li class="page-item" :class="{ disabled: !nextPageUrl }">
        <button class="page-link" @click="goToPage(getPageNumber(nextPageUrl))" :disabled="!nextPageUrl">
          Siguiente
        </button>
      </li>
    </ul>
  </nav>
</template>



<style scoped>
.page-link {
  cursor: pointer;
}
.page-item.disabled .page-link {
  cursor: not-allowed;
}
</style>
