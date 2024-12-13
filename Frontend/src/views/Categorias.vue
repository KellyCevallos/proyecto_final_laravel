<script setup>
import Pagination from '../components/Pagination.vue'; // Importamos el componente de paginación
import { Categoria } from '../store/categoria';
import { ref, onMounted } from 'vue';
import { sweetalert } from '../composables/sweetAlert';
import { Sesion } from '../store/sesion';

const categoriaStore = Categoria();
const sesionStore = Sesion();
const sweetAlert = sweetalert();

const categorias = ref([]); // Lista de categorías
const paginationData = ref({
  current_page: 1,
  last_page: 1,
  prev_page_url: null,
  next_page_url: null,
});

const showModal = ref(false);
const isEditing = ref(false);
const selectedCategoria = ref(null);

// Variables para crear/editar una categoría
const newCategoria = ref({
  categoriaName: '',
  categoriaDescripcion: '',
  categoriaImagen: '' // URL para la imagen
});

// --- Funciones CRUD ---

// Función para cargar todas las categorías
const loadCategorias = async (page = 1) => {
  try {
    await categoriaStore.getCategorias(page);
    categorias.value = categoriaStore.categoria.data;

    // Configurar los datos de la paginación
    paginationData.value.current_page = categoriaStore.categoria.current_page;
    paginationData.value.last_page = categoriaStore.categoria.last_page;
    paginationData.value.prev_page_url = categoriaStore.categoria.prev_page_url;
    paginationData.value.next_page_url = categoriaStore.categoria.next_page_url;
  } catch (error) {
    console.error('Error loading categories:', error);
  }
};

// Función para abrir el modal para crear una categoría
const openCreateModal = () => {
  isEditing.value = false;
  resetForm();
  showModal.value = true;
};

// Función para abrir el modal para editar una categoría
const openEditModal = (categoria) => {
  isEditing.value = true;
  selectedCategoria.value = categoria;
  newCategoria.value = {
    categoriaName: categoria.categoriaName,
    categoriaDescripcion: categoria.categoriaDescripcion,
    categoriaImagen: categoria.categoriaImagen // Prellenar con la URL existente
  };
  showModal.value = true;
};

// Función para resetear el formulario
const resetForm = () => {
  newCategoria.value = {
    categoriaName: '',
    categoriaDescripcion: '',
    categoriaImagen: ''
  };
};

// Función para crear una nueva categoría
const createCategoria = async () => {
  const formData = new FormData();
  formData.append('categoriaName', newCategoria.value.categoriaName);
  formData.append('categoriaDescripcion', newCategoria.value.categoriaDescripcion);
  formData.append('categoriaImagen', newCategoria.value.categoriaImagen);

  try {
    const response = await categoriaStore.crearCategoria(sesionStore.token, formData);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'La categoría ha sido creada correctamente.');
      showModal.value = false;
      loadCategorias();
    } else {
      sweetAlert.errorAlert('Error', response.message);
    }
  } catch (error) {
    console.error('Error creating category:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al crear la categoría.');
  }
};

// Función para actualizar una categoría
const updateCategoria = async () => {
  const formData = new FormData();
  formData.append('categoriaName', newCategoria.value.categoriaName);
  formData.append('categoriaDescripcion', newCategoria.value.categoriaDescripcion);
  formData.append('categoriaImagen', newCategoria.value.categoriaImagen);

  try {
    const response = await categoriaStore.updateCategoria(sesionStore.token, formData, selectedCategoria.value.categoriaId);
    if (response.success) {
      sweetAlert.successAlert('Éxito', 'La categoría ha sido actualizada correctamente.');
      showModal.value = false;
      loadCategorias();
    } else {
      sweetAlert.errorAlert('Error', response.message);
    }
  } catch (error) {
    console.error('Error updating category:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al actualizar la categoría.');
  }
};

// Función para guardar la categoría (creación o actualización)
const saveCategoria = () => {
  if (isEditing.value) {
    updateCategoria();
  } else {
    createCategoria();
  }
};

// Función para eliminar una categoría
const deleteCategoria = async (categoriaId) => {
  const confirm = await sweetAlert.confirmAlert('Confirmar', '¿Estás seguro de que deseas eliminar esta categoría?');
  if (confirm) {
    try {
      await categoriaStore.deleteCategoria(sesionStore.token, categoriaId);
      sweetAlert.successAlert('Éxito', 'La categoría ha sido eliminada correctamente.');
      loadCategorias();
    } catch (error) {
      console.error('Error deleting category:', error);
      sweetAlert.errorAlert('Error', 'Hubo un problema al eliminar la categoría.');
    }
  }
};

// Función para manejar el cambio de página
const handlePageChange = (page) => {
  loadCategorias(page);
};

// Cargar las categorías cuando el componente se monte
onMounted(() => {
  const closeLoading = sweetAlert.ShowLoading();
  loadCategorias();
  closeLoading();
});
</script>

<template>

  <div class="crud-container mt-5">
    <h2 class="crud-title">Gestión de Categorías</h2>

    <!-- Botón para abrir el modal de crear categoría -->
    <div class="action-bar">
      <button class="btn btn-primary" @click="openCreateModal">
        <i class="bi bi-plus-circle"></i> Crear Categoría
      </button>
    </div>

    <!-- Tabla de categorías -->
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Imagen</th>
            <th>Creada en</th>
            <th>Actualizada en</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="categoria in categorias" :key="categoria.categoriaId">
            <td>{{ categoria.categoriaId }}</td>
            <td>{{ categoria.categoriaName }}</td>
            <td>{{ categoria.categoriaDescripcion }}</td>
            <td><img :src="categoria.categoriaImagen" alt="Imagen" class="table-image"></td>
            <td>{{ categoria.created_at }}</td>
            <td>{{ categoria.updated_at }}</td>
            <td>
              <button class="btn btn-warning btn-sm mx-1" @click="openEditModal(categoria)">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-danger btn-sm mx-1" @click="deleteCategoria(categoria.categoriaId)">
                <i class="bi bi-trash"></i> Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Componente de Paginación -->
    <Pagination
      :currentPage="paginationData.current_page"
      :lastPage="paginationData.last_page"
      :prevPageUrl="paginationData.prev_page_url"
      :nextPageUrl="paginationData.next_page_url"
      @pageChange="handlePageChange"
    />

    <!-- Modal para crear/editar categoría -->
    <div v-if="showModal" class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ isEditing ? 'Editar Categoría' : 'Crear Categoría' }}</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="saveCategoria">
              <div class="mb-3">
                <label for="categoriaName" class="form-label">Nombre de la Categoría</label>
                <input v-model="newCategoria.categoriaName" type="text" id="categoriaName" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="categoriaDescripcion" class="form-label">Descripción</label>
                <textarea v-model="newCategoria.categoriaDescripcion" id="categoriaDescripcion" class="form-control" required></textarea>
              </div>
              <div class="mb-3">
                <label for="categoriaImagenUrl" class="form-label">URL de la Imagen</label>
                <input v-model="newCategoria.categoriaImagen" type="text" id="categoriaImagenUrl" class="form-control" required>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="showModal = false">Cerrar</button>
                <button type="submit" class="btn btn-primary">{{ isEditing ? 'Actualizar' : 'Crear' }}</button>
              </div>
            </form>
          </div>
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
  background-color: #f8f9fa; /* Fondo claro */
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

.table {
  background-color: white;
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

/* Imágenes de la tabla */
.table-image {
  width: 50px;
  height: auto;
  border-radius: 5px;
}

/* Botones */
.btn-sm {
  padding: 0.4rem 0.8rem;
  font-size: 0.9rem;
}

/* Modal */
.modal-dialog-scrollable {
  max-height: 90vh;
}

.modal-body {
  overflow-y: auto;
  max-height: 65vh;
  padding: 1.5rem;
}

.modal.show {
  display: block;
}

.modal-dialog {
  max-width: 600px;
}

@media (min-width: 768px) {
  .modal-dialog {
    max-width: 600px;
  }
}
</style>
