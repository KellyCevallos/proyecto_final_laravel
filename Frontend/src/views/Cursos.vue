<script setup>
import { Cursos } from '../store/cursos';
import { ref, onMounted, watch } from 'vue';
import { Sesion } from '../store/sesion';
import { Categoria } from '../store/categoria';
import Pagination from '../components/Pagination.vue';
import { sweetalert } from '../composables/sweetAlert';
import { useRouter, useRoute } from 'vue-router';


const router = useRouter();
const route = useRoute();  
const sweetAlert = sweetalert();
const categoriaStore = Categoria();
const sesionStore = Sesion();
const cursoStore = Cursos();
const cursos = ref([]);
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
});
const categoriaId = ref(route.params.categoriaId);

watch(() => route.params.categoriaId, async (newCategoriaId) => {
  const closeLoading = sweetAlert.ShowLoading();
  categoriaId.value = newCategoriaId;
  await loadCursos(null, categoriaId.value);
  closeLoading();
});

// Función para cargar cursos
const loadCursos = async (pageUrl = null, id) => {
  console.log('loadCursos', pageUrl, id);
  await cursoStore.getCursosCategorizados(pageUrl, id);
  cursos.value = cursoStore.cursos;
  paginationData.value = {
    current_page: cursoStore.cursos.current_page,
    last_page: cursoStore.cursos.last_page,
    prev_page_url: cursoStore.cursos.prev_page_url,
    next_page_url: cursoStore.cursos.next_page_url
  };
};

// Método para obtener la imagen de la categoría asociada al curso
const getCategoriaImagen = (categoriaId) => {
  const categoria = categoriaStore.categorianormal.find(cat => cat.categoriaId === categoriaId);
  return categoria ? categoria.categoriaImagen : 'default-image.jpg';
};

const irAVistaCurso = (cursoId) => {
  if (!sesionStore.sesion) {
    sweetAlert.showAlert(
      "Debes iniciar sesión",
      "Por favor, inicia sesión para acceder a los cursos."
    );
  } else {
    router.push(`/clases/${cursoId}`);
  }
}

const inscripcion = async (cursoId, cursoValor) => {
  cursoValor = parseFloat(cursoValor);
  const caso = cursoValor !== 0 ? 2 : 1;

  if (!sesionStore.sesion) {
    sweetAlert.showAlert(
      "Debes iniciar sesión",
      "Por favor, inicia sesión para inscribirte en los cursos."
      
    );
    return;
  } else {
    const formData = new FormData();
    formData.append("cursoId", cursoId);
    formData.append("userId", sesionStore.user.userId);
    formData.append("caso", caso);
    if (cursoValor !== 0.00) {
      const response = await sweetAlert.confirmAlert(
        "Atención",
        "Este curso tiene un valor de " + cursoValor + " US$, ¿Desea continuar con la inscripción?"
      );
      if (response) {
        const response2 = await cursoStore.inscripcion(sesionStore.token, formData);
        if (response2.success) {
          sweetAlert.showAlert("Inscripción exitosa", response2.message);
        } else {
          sweetAlert.showAlert("Error", response2.message);
        }
      }
    } else {
      const response2 = await cursoStore.inscripcion(sesionStore.token, formData);
      if (response2.success) {
        sweetAlert.showAlert("Inscripción exitosa", response2.message);
      } else {
        sweetAlert.showAlert("Error", response2.message);
      }
    }
  }
};

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();
  const categoriaId = route.params.categoriaId;
  await loadCursos(null,categoriaId);
  closeLoading();
});
</script>
<template>
<div>


  <div class="container my-5">
    <h2>Lista de Cursos: </h2>

    <!-- Recorre los cursos y muestra su información -->
    <div v-if="cursos.length">
      <div class="curso-card row align-items-center mb-4" v-for="curso in cursos" :key="curso.cursoId">
        <!-- Imagen del curso obtenida desde la categoría -->
        <div class="col-md-4">
          <img :src="getCategoriaImagen(curso.cursoCategoriaId)" class="curso-imagen img-fluid rounded" alt="Imagen del curso" />
        </div>

        <!-- Información del curso -->
        <div class="col-md-8">
          <h3 class="curso-titulo">{{ curso.cursoName }}</h3>
          <p class="curso-descripcion">{{ curso.cursoDescripcion }}</p>
          <p class="curso-instructor">{{ curso.createdBy }}</p>
          <p class="curso-precio">{{ curso.cursoValor }} US$</p> 
          <button class="btn btn-custom" @click="inscripcion(curso.cursoId, curso.cursoValor)">
            Incribirse
          </button>
        </div>
      </div>

      <!-- Paginación -->
      <Pagination
        :currentPage="paginationData.current_page"
        :lastPage="paginationData.last_page"
        :prevPageUrl="paginationData.prev_page_url"
        :nextPageUrl="paginationData.next_page_url"
        @pageChange="loadCursos"
      />
    </div>
  </div>

</div>
</template>




<style scoped>
.curso-card {
  border-bottom: 1px solid #ddd;
  padding-bottom: 20px;
}

.curso-imagen {
  max-width: 100%;
  height: auto;
}

.curso-titulo {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.curso-descripcion {
  font-size: 1rem;
  color: #555;
}

.curso-instructor {
  font-size: 0.875rem;
  color: #888;
  margin-bottom: 1rem;
}

.curso-precio {
  font-size: 1.25rem;
  font-weight: bold;
}

.pagination {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

button {
  padding: 10px 20px;
  background-color: #007bff;
  color: #fff;
  border: none;
  border-radius: 5px;
}

button:disabled {
  background-color: #ccc;
}
</style>
