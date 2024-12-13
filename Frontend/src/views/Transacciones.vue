<script setup>

import { useRouter } from 'vue-router';
import { Sesion } from '../store/sesion';
import { Transacciones } from '../store/transacciones';
import { Pago } from '../store/pago';
import { ref, onMounted, watch, reactive } from 'vue';
import { sweetalert } from '../composables/sweetAlert';
import Swal from 'sweetalert2';

const sesionStore = Sesion();
const router = useRouter();
const pagoStore = Pago();
const transaccionStore = Transacciones();
const sweetAlert = sweetalert();
const swal = Swal;
const url = 'http://127.0.0.1:8000';


const selectedView = ref('misCursos'); // Controla la vista seleccionada
const showModal = ref(false); // Controla la visibilidad del modal
const selectedCurso = reactive({}); // Almacena el curso seleccionado para editar
const tipoPago = ref(null); // Controla el tipo de pago seleccionado
const comprobante = ref(null); // Controla el comprobante

// Cargar datos al montar el componente
onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();
  await transaccionStore.getTransaccion(sesionStore.token);
  await pagoStore.getPagoType(sesionStore.token);
  closeLoading();
});

// Actualiza la vista dinámica al cambiar la opción
watch(selectedView, async (newView) => {
  const closeLoading = sweetAlert.ShowLoading();
  switch (newView) {
    case 'misCursos':
      await transaccionStore.getTransaccion(sesionStore.token);
      break;
    case 'cursosAprobados':
      await transaccionStore.getCursosPagados(sesionStore.token);
      break;
    case 'cursosInscribirse':
      await transaccionStore.getCursosPorInscribirse(sesionStore.token);
      break;
  }
  closeLoading();
});
// Cambiar entre las opciones de vista
const setView = (view) => {
  selectedView.value = view;
};


// Ver comprobante
const verComprobante = (comprobante) => {
  swal.fire({
    title: 'Comprobante de Pago',
    html: `<div style="display: flex; justify-content: center;">
             <img src="${url}${comprobante}" alt="Comprobante de Pago" style="max-width: 90%; max-height: 70vh; object-fit: contain;">
           </div>`,
    showCancelButton: true,
    confirmButtonText: 'Ver y Descargar',
    cancelButtonText: 'Cerrar',
  }).then((result) => {
    if (result.isConfirmed) {
      // Abrir la imagen en una nueva pestaña
      const newTab = window.open(`${url}${comprobante}`, '_blank');
      
      if (!newTab) {
        console.error('No se pudo abrir la nueva pestaña. Asegúrate de que no haya bloqueadores de ventanas emergentes.');
      }
    }
  });
};




// Abrir el modal de edición con los datos del curso seleccionado
const editarCurso = (curso) => {
  Object.assign(selectedCurso, curso);
  tipoPago.value = ''; // Restablecer tipo de pago
  comprobante.value = ''; // Restablecer comprobante
  showModal.value = true;
};

// Manejar la carga del archivo
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  const validImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

  if (file && !validImageTypes.includes(file.type)) {
    sweetAlert.errorAlert('Error', 'Solo se permiten archivos de imagen (JPEG, PNG, JPG, GIF).');
    comprobante.value = null; // Resetear comprobante si el archivo no es válido
    return;
  }

  comprobante.value = file; // Almacenar el archivo si es válido
  console.log('Comprobante cargado:', file);
};


// Finalizar pago y cerrar el modal
const finalizarPago = async () => {
  // Verificación del comprobante si el tipo de pago es diferente de efectivo (2)
  if (tipoPago.value != 2 && !comprobante.value) {
    sweetAlert.errorAlert('Error', 'Debe adjuntar un comprobante para este tipo de pago.');
    return;
  }

  // Construcción del objeto para enviar al store
  const dataPago = {
    cursoId: selectedCurso.claseId,   // Clase ID
    pagoMonto: selectedCurso.curso.cursoValor,  // Valor del curso
    pagoType_pagoTypeId: tipoPago.value,         // Tipo de pago seleccionado
    pagoComprobante: comprobante.value || null  // Comprobante opcional
  };

  console.log('Datos enviados para finalizar pago:', dataPago); // Verificar el objeto en consola

  try {
    const response = await transaccionStore.finalizarTransaccion(sesionStore.token, dataPago);
    if (response.success) {
      sweetAlert.successAlert('Éxito', response.message);
      showModal.value = false;  // Cerrar el modal
      await transaccionStore.getTransaccion(sesionStore.token);  // Recargar las transacciones
    } else {
      sweetAlert.errorAlert('Error', response.message);
    }
  } catch (error) {
    console.error('Error al finalizar el pago:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al finalizar el pago.');
  }
};


// Eliminar curso
const eliminarCurso = async (cursoId) => {
  const confirm = await sweetAlert.confirmAlert(
    "Atención",
    "¿Estás seguro de eliminar este proceso?"
  );
  if (confirm) {
    try {
      const response = await transaccionStore.deleteTransaccion(sesionStore.token, cursoId);
      if (response.success) {
        sweetAlert.successAlert("Curso eliminado", response.message);
        await transaccionStore.getTransaccion(sesionStore.token);
      } else {
        sweetAlert.errorAlert("Error", response.message);
      }
    } catch (error) {
      console.error("Error al eliminar el curso:", error);
      sweetAlert.errorAlert("Error", "Hubo un problema al eliminar el curso.");
    }
  }
};
</script>

<template>

  <div class="perfil-container">
    <h2 class="text-center">Transacciones</h2>

    <div class="view-options">
      <button :class="{ active: selectedView === 'misCursos' }" @click="setView('misCursos')">
        Cursos Pendientes
      </button>
      <button :class="{ active: selectedView === 'cursosAprobados' }" @click="setView('cursosAprobados')">
        Cursos Pagados
      </button>
    </div>

    <div class="crud-content">
      <div v-if="selectedView === 'misCursos'">
        <h3>Procesos Pendientes</h3>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre del Curso</th>
              <th>Estudiante</th>
              <th>Estado</th>
              <th>Valor</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="curso in transaccionStore.transaccion" :key="curso.claseId">
              <td>{{ curso.claseId }}</td>
              <td>{{ curso.curso.cursoName }}</td>
              <td>{{ curso.usuario.userName }}</td>
              <td>{{ curso.relVerificacion ? 'Pagado' : 'Pendiente' }}</td>
              <td>{{ curso.curso.cursoValor }}$</td>
              <td>
                <button class="btn btn-warning btn-sm mx-1" @click="editarCurso(curso)">Editar</button>
                <button class="btn btn-danger btn-sm mx-1" @click="eliminarCurso(curso.claseId)">Eliminar</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="selectedView === 'cursosAprobados'">
        <h3>Procesos Pagados</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Usuario</th>
                <th>Verificación</th>
                <th>Comprobante</th>
                <th>Valor</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="curso in transaccionStore.transaccion" :key="curso.cursoId">
                <td>{{ curso.claseId }}</td>
                <td>{{ curso.curso.cursoName }}</td>
                <td>{{ curso.usuario.userName }}</td>
                <td>{{ curso.relVerificacion ? 'Pagado' : 'No Verificado' }}</td>
                <td v-if="curso.pago.pagoComprobante">
                  <img 
                    :src="`${url}${curso.pago.pagoComprobante}`" 
                    alt="Comprobante" 
                    style="max-width: 100px; max-height: 100px; cursor: pointer;" 
                    @click="verComprobante(curso.pago.pagoComprobante)" 
                  />
                </td>
                <td v-else></td>
                <td>{{ curso.curso.cursoValor }}$</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
    </div>
    

    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Finalizar Inscripcion</h3>
        <div class="form-group">
          <label for="tipoPago">Tipo de Pago</label>
            <select v-model="tipoPago" id="tipoPago" class="form-control">
              <option value="" disabled>Seleccione un tipo de pago</option>
              <option v-for="pago in pagoStore.pagoType.data" :key="pago.pagoTypeId" :value="pago.pagoTypeId">
                {{ pago.pagoTypeName }}
              </option>
            </select>
        </div>

        <div v-if="tipoPago && tipoPago != 2" class="form-group">
          <label for="comprobante">Comprobante</label>
            <input type="file" id="comprobante" class="form-control" @change="handleFileUpload" />
        </div>

        <div class="form-group">
          <label for="valorCurso">Valor del Curso</label>
          <input :value="selectedCurso.curso.cursoValor" type="text" id="valorCurso" class="form-control" disabled />
        </div>

        <button class="btn btn-success mt-4" @click="finalizarPago">Finalizar Pago</button>
        <button class="btn btn-secondary mt-2" @click="showModal = false">Cancelar</button>
      </div>
    </div>
  </div>
</template>


<style scoped>

.text-center {
  text-align: center;
}

/* Estilos para las opciones de vista */
.perfil-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.view-options {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 2rem;
}

.view-options button {
  padding: 0.5rem 1.5rem;
  border: none;
  background-color: #f0f0f0;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s;
}

.view-options button.active {
  background-color: #3ecf8e;
  color: white;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  width: 400px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.view-options button:hover {
  background-color: #0f3d28;
  color: white;
}

.crud-content {
  margin-top: 2rem;
}

/* Estilos para la tabla */
.table-responsive {
  margin-top: 1rem;
}

.table th, .table td {
  text-align: center;
  vertical-align: middle;
  padding: 1rem;
}

.btn-sm {
  padding: 0.5rem;
  font-size: 0.85rem;
}
</style>