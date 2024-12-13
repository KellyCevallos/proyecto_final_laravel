<script setup>
import { ref, reactive, watch, onMounted } from 'vue';
import { Cursos } from '../../store/cursos';
import { Categoria } from '../../store/categoria';
import { Sesion } from '../../store/sesion';
import { sweetalert } from '../../composables/sweetAlert';

// Recibir el curso a editar como prop
const props = defineProps({
  cursoData: Object
});

const emit = defineEmits(['close']);

const sweetAlert = sweetalert();
const categoriaStore = Categoria();
const sesionStore = Sesion();
const cursoStore = Cursos();


// Inicializar el curso con un bloque de contenido y una pregunta de examen por defecto
const newCurso = reactive({
  cursoId: null,
  cursoName: '',
  cursoDescripcion: '',
  cursoNivelId: null,
  cursoCategoriaId: null,
  cursoValor: null,
  cursoRequisito: '',
  cursoContenido: [
    { titulo: '', media: '', tipoMedia: 'imagen', concepto: '' }
  ],
  cursoExamen: [
    { pregunta: '', opciones: ['', '', '', ''], respuestaCorrecta: '' }
  ],
  createdBy: `${sesionStore.user.userNombres} ${sesionStore.user.userApellidos}`
});

// Cargar los datos del curso seleccionado al editar
watch(() => props.cursoData, (newData) => {
  if (newData) {
    Object.assign(newCurso, {
      cursoId: newData.cursoId,
      cursoName: newData.cursoName,
      cursoDescripcion: newData.cursoDescripcion,
      cursoNivelId: newData.cursoNivelId,
      cursoCategoriaId: newData.cursoCategoriaId,
      cursoValor: newData.cursoValor,
      cursoRequisito: newData.cursoRequisito,
      cursoContenido: JSON.parse(newData.cursoContenido),
      cursoExamen: JSON.parse(newData.cursoExamen)
    });
  }
}, { immediate: true });

// Función para agregar bloques y preguntas
const addContentBlock = () => {
  newCurso.cursoContenido.push({ titulo: '', media: '', concepto: '' });
};

const removeContentBlock = (index) => {
  newCurso.cursoContenido.splice(index, 1);
};

const addExamenQuestion = () => {
  newCurso.cursoExamen.push({ pregunta: '', opciones: ['', '', '', ''], respuestaCorrecta: '' });
};

const removeExamenQuestion = (index) => {
  newCurso.cursoExamen.splice(index, 1);
};

// Guardar o actualizar el curso
const saveCurso = async () => {
    const closeLoading = sweetAlert.ShowLoading();
  try {
    
    const cursoParaGuardar = {
      ...newCurso,
      cursoContenido: JSON.stringify(newCurso.cursoContenido),
      cursoExamen: JSON.stringify(newCurso.cursoExamen)
    };

    if (!newCurso.cursoId) {
      const response = await cursoStore.crearCurso(sesionStore.token, cursoParaGuardar);
      if (response.success == true) {
        sweetAlert.successAlert('Curso creado', response.message);
        
      }else{
        sweetAlert.errorAlert('Error', response.message);
      }

    } else {
      const response = await cursoStore.updateCurso(sesionStore.token,newCurso.cursoId ,cursoParaGuardar);
        if (response.success == true) {
            sweetAlert.successAlert('Curso actualizado', response.message);
            await cursoStore.getCursos(sesionStore.token);
        }else{
            sweetAlert.errorAlert('Error', response.message);
        }
    }

    emit('close').then(() => {
      closeLoading();
    location.reload();
    });
  } catch (error) {
    sweetAlert.errorAlert('Error', 'Hubo un problema al guardar el curso.');
  }
    closeLoading();
};

// Cargar categorías y niveles
const loadInitialData = async () => {
  await categoriaStore.getCategoria();
  await categoriaStore.getNiveles();
};

// Inicializamos al montar el componente
onMounted(() => {
  loadInitialData();
});
</script>


<template>
    <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ newCurso.cursoId ? 'Editar Curso' : 'Crear Curso' }}</h5>
              <button type="button" class="btn-close" @click="$emit('close')"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="saveCurso">
                  <!-- Datos del Curso -->
                  <div class="mb-3">
                    <label for="cursoName" class="form-label">Nombre del Curso</label>
                    <input v-model="newCurso.cursoName" type="text" id="cursoName" required class="form-control" />
                  </div>
                  <div class="mb-3">
                    <label for="cursoDescripcion" class="form-label">Descripción</label>
                    <textarea v-model="newCurso.cursoDescripcion" id="cursoDescripcion" required class="form-control"></textarea>
                  </div>
                  <div class="mb-3">
                    <label for="cursoNivelId" class="form-label">Nivel del Curso</label>
                    <select v-model="newCurso.cursoNivelId" id="cursoNivelId" required class="form-control">
                      <option value="" disabled>Seleccionar nivel</option>
                      <option v-for="nivel in categoriaStore.nivel" :key="nivel.nivelId" :value="nivel.nivelId">
                        {{ nivel.nivelName }}
                      </option>
                    </select>
                  </div>
                  <div class="mb-3">
                      <label for="cursoCategoriaId" class="form-label">Categoria del Curso</label>
                      <select v-model="newCurso.cursoCategoriaId" id="cursoCategoriaId" required class="form-control">
                        <option value="" disabled>Seleccionar categoria</option>
                        <option v-for="categoria in categoriaStore.categorianormal" :key="categoria.categoriaId" :value="categoria.categoriaId">
                          {{ categoria.categoriaName }}
                        </option>
                      </select>
                    </div>
                  <div class="mb-3">
                    <label for="cursoValor" class="form-label">Precio</label>
                    <input v-model="newCurso.cursoValor" type="number" step="0.01" id="cursoValor" required class="form-control" />
                  </div>
                  <div class="mb-3">
                    <label for="cursoRequisito" class="form-label">Requisitos</label>
                    <input v-model="newCurso.cursoRequisito" type="text" id="cursoRequisito" required class="form-control" />
                  </div>
      
                  <!-- Contenido del Curso -->
                  <h3 class="section-title">Contenido del Curso</h3>
                  <div v-for="(block, index) in newCurso.cursoContenido" :key="index" class="content-block mb-3 p-3 rounded shadow-sm">
                    <div class="form-group mb-2">
                      <label for="'titulo-' + index" class="form-label">Título</label>
                      <input v-model="block.titulo" type="text" :id="'titulo-' + index" class="form-control" required />
                    </div>
                    <div class="form-group mb-2">
                      <label for="'media-' + index" class="form-label">Media (URL)</label>
                      <input v-model="block.media" type="text" :id="'media-' + index" class="form-control" />
                      <div class="form-group mb-2">
                        <label for="'tipoMedia-' + index" class="form-label">Tipo de Media</label>
                          <input v-model="block.tipoMedia" type="radio" :id="'tipoMedia-imagen-' + index" value="imagen" checked />
                          <label :for="'tipoMedia-imagen-' + index">Imagen</label>
                          <input v-model="block.tipoMedia" type="radio" :id="'tipoMedia-video-' + index" value="video" />
                          <label :for="'tipoMedia-video-' + index">Video</label>
                      </div>
                    </div>
                    <div class="form-group mb-2">
                      <label for="'concepto-' + index" class="form-label">Concepto</label>
                      <textarea v-model="block.concepto" :id="'concepto-' + index" class="form-control" required></textarea>
                    </div>
                    <button v-if="newCurso.cursoContenido.length > 1" type="button" @click="removeContentBlock(index)" class="btn btn-danger btn-sm">Eliminar Bloque</button>
                    <hr />
                  </div>
                  <button type="button" @click="addContentBlock" class="btn btn-primary btn-sm">Añadir Bloque de Contenido</button>
      
                  <!-- Examen del Curso -->
                  <h3 class="section-title">Examen del Curso</h3>
                  <div v-for="(question, index) in newCurso.cursoExamen" :key="index" class="examen-question mb-3 p-3 rounded shadow-sm">
                    <div class="form-group mb-2">
                      <label for="'pregunta-' + index" class="form-label">Pregunta</label>
                      <input v-model="question.pregunta" type="text" :id="'pregunta-' + index" class="form-control" required />
                    </div>
                    <div class="form-group mb-2">
                      <label>Opciones</label>
                      <div v-for="(opcion, optIndex) in question.opciones" :key="optIndex" class="form-group-inline mb-1">
                        <input v-model="question.opciones[optIndex]" type="text" class="form-control" required />
                      </div>
                    </div>
                    <div class="form-group mb-2">
                      <label for="'respuestaCorrecta-' + index" class="form-label">Respuesta Correcta</label>
                      <input v-model="question.respuestaCorrecta" type="text" :id="'respuestaCorrecta-' + index" class="form-control" required />
                    </div>
                    <button v-if="newCurso.cursoExamen.length > 1" type="button" @click="removeExamenQuestion(index)" class="btn btn-danger btn-sm">Eliminar Pregunta</button>
                    <hr />
                  </div>
                  <button type="button" @click="addExamenQuestion" class="btn btn-primary btn-sm">Añadir Pregunta</button>
      
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-success">{{ newCurso.cursoId ? 'Actualizar Curso' : 'Crear Curso' }}</button>
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Cerrar</button>
                  </div>
                </form>
              </div>
          </div>
        </div>
      </div>
  <div class="modal fade show d-block" tabindex="-1" role="dialog" style="background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ newCurso.cursoId ? 'Editar Curso' : 'Crear Curso' }}</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveCurso">
            <!-- Datos del Curso -->
            <div class="mb-3">
              <label for="cursoName" class="form-label">Nombre del Curso</label>
              <input v-model="newCurso.cursoName" type="text" id="cursoName" required class="form-control" />
            </div>
            <div class="mb-3">
              <label for="cursoDescripcion" class="form-label">Descripción</label>
              <textarea v-model="newCurso.cursoDescripcion" id="cursoDescripcion" required class="form-control"></textarea>
            </div>
            <div class="mb-3">
              <label for="cursoNivelId" class="form-label">Nivel del Curso</label>
              <select v-model="newCurso.cursoNivelId" id="cursoNivelId" required class="form-control">
                <option value="" disabled>Seleccionar nivel</option>
                <option v-for="nivel in categoriaStore.nivel" :key="nivel.nivelId" :value="nivel.nivelId">
                  {{ nivel.nivelName }}
                </option>
              </select>
            </div>
            <div class="mb-3">
                <label for="cursoCategoriaId" class="form-label">Categoria del Curso</label>
                <select v-model="newCurso.cursoCategoriaId" id="cursoCategoriaId" required class="form-control">
                  <option value="" disabled>Seleccionar categoria</option>
                  <option v-for="categoria in categoriaStore.categorianormal" :key="categoria.categoriaId" :value="categoria.categoriaId">
                    {{ categoria.categoriaName }}
                  </option>
                </select>
              </div>
            <div class="mb-3">
              <label for="cursoValor" class="form-label">Precio</label>
              <input v-model="newCurso.cursoValor" type="number" step="0.01" id="cursoValor" required class="form-control" />
            </div>
            <div class="mb-3">
              <label for="cursoRequisito" class="form-label">Requisitos</label>
              <input v-model="newCurso.cursoRequisito" type="text" id="cursoRequisito" required class="form-control" />
            </div>

            <!-- Contenido del Curso -->
            <h3 class="section-title">Contenido del Curso</h3>
            <div v-for="(block, index) in newCurso.cursoContenido" :key="index" class="content-block mb-3 p-3 rounded shadow-sm">
              <div class="form-group mb-2">
                <label for="'titulo-' + index" class="form-label">Título</label>
                <input v-model="block.titulo" type="text" :id="'titulo-' + index" class="form-control" required />
              </div>
              <div class="form-group mb-2">
                <label for="'media-' + index" class="form-label">Media (URL)</label>
                <input v-model="block.media" type="text" :id="'media-' + index" class="form-control" />
              </div>
              <div class="form-group mb-2">
                <label for="'concepto-' + index" class="form-label">Concepto</label>
                <textarea v-model="block.concepto" :id="'concepto-' + index" class="form-control" required></textarea>
              </div>
              <button v-if="newCurso.cursoContenido.length > 1" type="button" @click="removeContentBlock(index)" class="btn btn-danger btn-sm">Eliminar Bloque</button>
              <hr />
            </div>
            <button type="button" @click="addContentBlock" class="btn btn-primary btn-sm">Añadir Bloque de Contenido</button>

            <!-- Examen del Curso -->
            <h3 class="section-title">Examen del Curso</h3>
            <div v-for="(question, index) in newCurso.cursoExamen" :key="index" class="examen-question mb-3 p-3 rounded shadow-sm">
              <div class="form-group mb-2">
                <label for="'pregunta-' + index" class="form-label">Pregunta</label>
                <input v-model="question.pregunta" type="text" :id="'pregunta-' + index" class="form-control" required />
              </div>
              <div class="form-group mb-2">
                <label>Opciones</label>
                <div v-for="(opcion, optIndex) in question.opciones" :key="optIndex" class="form-group-inline mb-1">
                  <input v-model="question.opciones[optIndex]" type="text" class="form-control" required />
                </div>
              </div>
              <div class="form-group mb-2">
                <label for="'respuestaCorrecta-' + index" class="form-label">Respuesta Correcta</label>
                <input v-model="question.respuestaCorrecta" type="text" :id="'respuestaCorrecta-' + index" class="form-control" required />
              </div>
              <button v-if="newCurso.cursoExamen.length > 1" type="button" @click="removeExamenQuestion(index)" class="btn btn-danger btn-sm">Eliminar Pregunta</button>
              <hr />
            </div>
            <button type="button" @click="addExamenQuestion" class="btn btn-primary btn-sm">Añadir Pregunta</button>

            <div class="modal-footer">
              <button type="submit" class="btn btn-success">{{ newCurso.cursoId ? 'Actualizar Curso' : 'Crear Curso' }}</button>
              <button type="button" class="btn btn-secondary" @click="$emit('close')">Cerrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Secciones de contenido y examen */
.section-title {
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-size: 1.5rem;
  color: #0f3d28;
}

.content-block, .examen-question {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
}

/* Ajustes al modal */
.modal-content {
  padding: 1.5rem;
}

.modal-header {
  background-color: #0f3d28;
  color: white;
}

.modal-footer {
  display: flex;
  justify-content: space-between;
}
</style>
