<script setup>

import Pagination from '../components/Pagination.vue'; 
import { Cursos } from '../store/cursos';
import { ref, onMounted } from 'vue';
import { Sesion } from '../store/sesion';
import { Categoria } from '../store/categoria';
import { sweetalert } from '../composables/sweetAlert';
import CrearCurso from '../components/Admin/crearCurso.vue';  // Importamos el componente

const sweetAlert = sweetalert();
const categoriaStore = Categoria();
const sesionStore = Sesion();
const cursoStore = Cursos();
const cursos = ref([]);

const showCrearCurso = ref(false);  // Controla la visualización del modal
const cursoSeleccionado = ref(null);  // Curso seleccionado para editar

const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
});

// Función para cargar cursos
const loadCursos = async (pageUrl = null) => {
  await cursoStore.getCursos(sesionStore.token, pageUrl);
  cursos.value = cursoStore.cursos.data;
  paginationData.value = {
    current_page: cursoStore.cursos.current_page,
    last_page: cursoStore.cursos.last_page,
    prev_page_url: cursoStore.cursos.prev_page_url,
    next_page_url: cursoStore.cursos.next_page_url
  };
};

// Función para eliminar un curso
const deleteCurso = async (cursoId) => {
  const confirm = await sweetAlert.confirmAlert('Eliminar curso', '¿Estás seguro de que deseas eliminar este curso?');
  if (confirm) {
    try {
      await cursoStore.deleteCurso(sesionStore.token, cursoId);
      sweetAlert.successAlert('Éxito', 'El curso ha sido eliminado.');
      loadCursos();
    } catch (error) {
      sweetAlert.errorAlert('Error', 'Hubo un problema al eliminar el curso.');
    }
  }
};

// Función para abrir el modal en modo de creación
const openCreateModal = () => {
  cursoSeleccionado.value = null;  // Limpiar el curso seleccionado
  showCrearCurso.value = true;
};

// Función para abrir el modal en modo de edición
const openEditModal = (curso) => {
  cursoSeleccionado.value = curso;  // Cargar los datos del curso seleccionado
  showCrearCurso.value = true;
};

// Función para cerrar el modal
const closeModal = () => {
  showCrearCurso.value = false;
};

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();
  await loadCursos();
  await categoriaStore.getCategoria();
  await categoriaStore.getNiveles();
  closeLoading();
});
</script>

<template>
  <div class="crud-container">
    <h2 class="crud-title">Gestión de Cursos</h2>

    <div class="action-bar">
      <button class="btn btn-primary" @click="openCreateModal">
        <i class="bi bi-plus-circle"></i> Crear Curso
      </button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Categoría</th>
            <th>Creador</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="curso in cursos" :key="curso.cursoId">
            <td>{{ curso.cursoId }}</td>
            <td>{{ curso.cursoName }}</td>
            <td class="descripcion">{{ curso.cursoDescripcion }}</td>
            <td>{{ curso.cursoValor }}</td>
            <td>{{ curso.cursoCategoriaId }}</td>
            <td>{{ curso.createdBy }}</td>
            <td>
              <button class="btn btn-warning btn-sm" @click="openEditModal(curso)">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-danger btn-sm" @click="deleteCurso(curso.cursoId)">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <Pagination
      :currentPage="paginationData.current_page"
      :lastPage="paginationData.last_page"
      :prevPageUrl="paginationData.prev_page_url"
      :nextPageUrl="paginationData.next_page_url"
      @pageChange="loadCursos"
    />
  </div>

  <!-- Mostrar el componente de crear/editar curso cuando showCrearCurso sea true -->
  <CrearCurso v-if="showCrearCurso" :cursoData="cursoSeleccionado" @close="closeModal" />
</template>


<style scoped>
.descripcion {
  max-width: 300px; /* Ajusta el ancho máximo que desees */
  white-space: nowrap; /* Evita que el texto se divida en varias líneas */
  overflow: hidden; /* Oculta el texto que se desborda */
  text-overflow: ellipsis; /* Añade "..." al final del texto truncado */
}
/* Contenedor principal */
.crud-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
  background-color: #f8f9fa;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Título del CRUD */
.crud-title {
  text-align: center;
  font-size: 2rem;
  color: #0f3d28;
  margin-bottom: 2rem;
}

/* Barra de acciones */
.action-bar {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1.5rem;
}

/* Tabla */
.table-responsive {
  margin-top: 1rem;
}

.table th, .table td {
  text-align: center;
  vertical-align: middle;
}

.table th {
  background-color: #0f3d28;
  color: white;
}

.table td {
  background-color: #f0f7f4;
}
</style>
