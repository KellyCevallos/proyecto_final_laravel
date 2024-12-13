<script setup>
import { RouterView, useRouter } from 'vue-router';
import { ref, onMounted } from 'vue';
import { Sesion } from './store/sesion';
import { sweetalert } from './composables/sweetAlert'; 
import { Categoria } from './store/categoria';
import { Cursos } from './store/cursos';
import FooterComponent from './components/Public/footer.vue';
import Header from './components/Public/header.vue';

const sesionStore = Sesion();
const categoriaStore = Categoria();
const cursoStore = Cursos();
const sweetAlert = sweetalert(); // Llama a la función sweetalert
const router = useRouter();

// Bandera para controlar el renderizado del componente hijo
const isChildMounted = ref(false);

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();

  try {
    // Ejecuta las llamadas en paralelo
    await Promise.all([sesionStore.getSesion(), categoriaStore.getCategorias(), cursoStore.getCursosHome()]);
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Error al obtener la sesión');
    console.error(error);
  } finally {
    closeLoading();
    isChildMounted.value = true;
  }
});
</script>

<template>
  <div class="app">
    <!-- Header añadido aquí -->
    <Header />
    <div class="content">
      <RouterView v-if="isChildMounted" />
    </div>
    <!-- Footer añadido aquí -->
    <FooterComponent />
  </div>
</template>

<style scoped>
.app {
  display: flex;
  flex-direction: column;
  min-height: 100vh; /* Altura total de la pantalla */
  margin: 0; /* Asegúrate de que no haya margen */
  padding: 0; /* Elimina cualquier padding no deseado */
}

.content {
  flex: 1; /* Se expande para llenar el espacio */
  padding: 0; /* Elimina el padding */
  margin: 0; /* Asegúrate de que no haya márgenes */
}


footer {
  background-color: #0f3d28;
  color: #f0f0f0;
  padding: 20px;
  text-align: center;
}
</style>

