<script setup>
import Header from '../components/Public/header.vue';
import { ref, onMounted, watch } from 'vue';
import { Sesion } from '../store/sesion';
import { Transacciones} from '../store/transacciones';
import { sweetalert } from '../composables/sweetAlert';
import { useRouter } from 'vue-router';
import certificado from '../components/certificado.vue'

const sesionStore = Sesion();
const transaccionStore = Transacciones();
const sweetAlert = sweetalert();
const router = useRouter();

const mostrarCertificado = ref(false);

const selectedView = ref('misCursos'); // Controla qué vista se está mostrando ('misCursos', 'cursosAprobados', 'cursosInscribirse')

onMounted(async () => {
  await transaccionStore.getTransaccionUserEnCurso(sesionStore.token, sesionStore.user.userId);
  console.log(transaccionStore.transaccion);
});


watch(selectedView, async (newView) => {
  const closeLoading = sweetAlert.ShowLoading();
  switch (newView) {
    case 'misCursos':
      await transaccionStore.getTransaccionUserEnCurso(sesionStore.token, sesionStore.user.userId);
      break;
    case 'cursosAprobados':
      await transaccionStore.getTransaccionUserFinalizado(sesionStore.token, sesionStore.user.userId);
      console.log(transaccionStore.transaccion);
      break;
    case 'cursosInscribirse':
      await transaccionStore.getTransaccionUserNoPagado(sesionStore.token, sesionStore.user.userId);
      console.log(transaccionStore.transaccion);
      break;
  }
  closeLoading();
});
// Cambiar entre las opciones de vista
const setView = (view) => {
  selectedView.value = view;
};

const info = (valor ) => {
  sweetAlert.showAlert("Atencion", "Para poder finalizar la inscripcion de este curso, por favor realizar el pago correspondiente: " + valor);
}
const irCurso = (cursoId,pasado,claseId) => {
  console.log(cursoId);
  router.push(`/clases/${cursoId}/${pasado}/${claseId}`);
};
const eliminarCurso = async (cursoId) => {
   const response = await sweetAlert.confirmAlert("Retirarse", "Si te retiras no podras ingresar hasta pagar de nuevo, ¿Estas seguro de retirarte?");
   if(response){
      const response2 = await transaccionStore.deleteTransaccion(sesionStore.token, cursoId);
     if(response2.success == true){
       sweetAlert.successAlert("Curso eliminado", response.message);
        window.location.reload();
     }else{
        sweetAlert.errorAlert("Error", response.message);
      }
   }else{
     return;
   }
};

</script>

<template>
  <div class="perfil-container">
    <!-- Título -->
    <h2 class="text-center">Mis cursos</h2>

    <!-- Opciones de la vista -->
    <div class="view-options">
      <button :class="{ active: selectedView === 'misCursos' }" @click="setView('misCursos')">
        Actuales
      </button>
      <button :class="{ active: selectedView === 'cursosAprobados' }" @click="setView('cursosAprobados')">
        Aprobados
      </button>
      <button :class="{ active: selectedView === 'cursosInscribirse' }" @click="setView('cursosInscribirse')">
        Pendientes
      </button>
    </div>

    <!-- Contenido dinámico según la opción seleccionada -->
    <div class="crud-content">
      <!-- Vista de "Mis Cursos" -->
      <div v-if="selectedView === 'misCursos'">
        <h3>Mis Cursos Actuales</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Profesor</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="curso in transaccionStore.transaccion" :key="curso.cursoId">
                <td>{{ curso.claseId }}</td>
                <td>{{ curso.curso.cursoName }}</td>
                <td>{{ curso.curso.createdBy }}</td>
                <td>{{ curso.relVerificacion ? 'En curso' : 'Terminado' }}</td>
                <td>
                  <button class="btn btn-warning btn-sm mx-1" @click="irCurso(curso.curso.cursoId,false,curso.claseId)">Ir al Curso</button>
                  <button class="btn btn-danger btn-sm mx-1" @click="eliminarCurso(curso.claseId)">Retirarse</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vista de "Cursos Aprobados" -->
      <div v-if="selectedView === 'cursosAprobados'">
        <h3>Mis Cursos Aprobados</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Usuario</th>
                <th>Calificacion</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="curso in transaccionStore.transaccion" :key="curso.cursoId">
                    <td>{{ curso.claseId }}</td>
                    <td>{{ curso.curso.cursoName }}</td>
                    <td>{{ curso.usuario.userName }}</td>
                    <td>{{ curso.relNota }}</td>
                    <td>
                      <button class="btn btn-success btn-sm mx-1" @click="mostrarCertificado = true">Certificado</button>
                    </td>
                    <certificado  v-if="mostrarCertificado" :curso="curso" @cerrar="mostrarCertificado = false"/>
                  </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vista de "Cursos por Inscribirse" -->
      <div v-if="selectedView === 'cursosInscribirse'">
        <h3>Mis Cursos Por Pagar</h3>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Curso</th>
                <th>Usuario</th>
                <th>Verificación</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="curso in transaccionStore.transaccion" :key="curso.claseId">
                    <td>{{ curso.claseId }}</td>
                    <td>{{ curso.curso.cursoName }}</td>
                    <td>{{ curso.usuario.userName }}</td>
                    <td>{{ curso.relVerificacion ? 'Verificado' : 'No Verificado' }}</td>
                    <td>
                        <button class="btn btn-info btn-sm mx-1" @click="info(curso.curso.cursoValor)">
                            <i class="bi bi-info-circle"></i> <!-- Ícono de información de Bootstrap Icons -->
                          </button>     
                          <button class="btn btn-danger btn-sm mx-1" @click="eliminarCurso(curso.claseId)">Retirarse</button>                     
                    </td>
                  </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.perfil-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.text-center {
  text-align: center;
}

/* Estilos para las opciones de vista */
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
  color: #333;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease-in-out;
}

.view-options button.active {
  background-color: #3ecf8e;
  color: white;
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
