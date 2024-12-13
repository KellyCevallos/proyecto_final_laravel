<script setup>

import Pagination from '../components/Pagination.vue'; 
import { Cursos } from '../store/cursos';
import { ref, onMounted } from 'vue';
import { Sesion } from '../store/sesion';
import { Categoria } from '../store/categoria';
import { sweetalert } from '../composables/sweetAlert';
import CrearCurso from '../components/Admin/crearCurso.vue';  // Importamos el componente
import { User } from '../store/users';

const sweetAlert = sweetalert();
const userStore = User();
const categoriaStore = Categoria();
const sesionStore = Sesion();
const cursoStore = Cursos();
const cursos = ref([]);

// Controla la visualización de los modales
const showCrearCurso = ref(false);  // Controla el modal para crear/editar cursos
const showEstudiantesModal = ref(false);  // Controla el modal para ver estudiantes
const cursoSeleccionado = ref(null);  // Curso seleccionado para editar o ver estudiantes
const estudiantes = ref([]);  // Lista de estudiantes del curso seleccionado

const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null
});

// Función para cargar cursos
const loadCursos = async (pageUrl = null) => {
  await cursoStore.getCursosTeacher(sesionStore.token, sesionStore.user.userName, pageUrl);
  cursos.value = cursoStore.cursoTeacher.data;
  paginationData.value = {
    current_page: cursoStore.cursos.current_page,
    last_page: cursoStore.cursos.last_page,
    prev_page_url: cursoStore.cursos.prev_page_url,
    next_page_url: cursoStore.cursos.next_page_url
  };
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

// Función para abrir el modal de estudiantes
const openinfoModal = async (cursoId) => {
  showEstudiantesModal.value = true;  // Mostrar el modal
  await loadEstudiantes(cursoId);  // Cargar los estudiantes del curso
};

// Función para cargar estudiantes del curso
const loadEstudiantes = async (cursoId) => {
    const closeLoading = sweetAlert.ShowLoading();
    const response = await userStore.getUserCurso(sesionStore.token,cursoId);
    console.log(response.data);
    estudiantes.value = response.data;
    closeLoading();
};

// Función para cerrar los modales
const closeModal = () => {
  showCrearCurso.value = false;
  showEstudiantesModal.value = false;
  loadCursos();
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
  <Header />

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
            <td>{{ curso.cursoDescripcion }}</td>
            <td>{{ curso.cursoValor }}</td>
            <td>{{ curso.cursoCategoriaId }}</td>
            <td>{{ curso.createdBy }}</td>
            <td>
              <button class="btn btn-warning btn-sm" @click="openEditModal(curso)">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-info btn-sm" @click="openinfoModal(curso.cursoId)">
                <i class="bi bi-eye"></i> Ver Estudiantes
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


  <!-- Modal para crear/editar curso -->
  <CrearCurso v-if="showCrearCurso" :cursoData="cursoSeleccionado" @close="closeModal" />

  <!-- Modal para ver estudiantes -->
  <div v-if="showEstudiantesModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Estudiantes del Curso</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>
        <div class="modal-body">
          <div v-if="estudiantes.value != ''" class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="estudiante in estudiantes" :key="estudiante.usuario.userId">
                  <td>{{ estudiante.usuario.userId}}</td>
                  <td>{{ estudiante.usuario.userNombres }} {{ estudiante.usuario.userApellidos }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else>
            <p>No hay estudiantes inscritos en este curso.</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
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
  padding: 1rem;
}

.table th {
  background-color: #0f3d28;
  color: white;
}

.table td {
  background-color: #f0f7f4;
}

/* Estilos del modal */
.modal-content {
  padding: 1.5rem;
}

.modal-header {
  background-color: #0f3d28;
  color: white;
}

.table {
  background-color: white;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
}
</style>
