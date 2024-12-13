<script setup>

import { sweetalert } from '../composables/sweetAlert';
import { ref, reactive, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { Cursos } from '../store/cursos';
import Examen from '../components/Examen.vue';
import Swal from 'sweetalert2';

const cursoStore = Cursos();
const route = useRoute();
const sweetAlert = sweetalert();

const curso = reactive({
  cursoName: '',
  cursoDescripcion: '',
  cursoRequisito: '',
  cursoValor: '',
  cursoContenido: [],
  cursoExamen: []
});
const mostrarExamen = ref(false);

// Función para cargar los datos del curso
const loadCurso = async (id) => {
  const response = await cursoStore.getCurso(id);
  console.log(response);
  if (response.success) {
    Object.assign(curso, {
      ...response.data,
      cursoContenido: JSON.parse(response.data.cursoContenido),
      cursoExamen: JSON.parse(response.data.cursoExamen),
    });
  } else {
    sweetAlert.errorAlert('Error', response.message);
  }
};

// Traemos la prop claseId
const props = defineProps({
  claseId: {
    type: String,
    required: true,
  },
  pasado: {
    type: String,
    required: true,
  },
  cursoId: {
    type: String,
    required: true,
  },
});

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading(); // Muestra loading.

  try {
    await loadCurso(props.claseId); // Espera a que se cargue el curso.
  } catch (error) {
    console.error('Error al cargar el curso:', error);
    sweetAlert.showError('Error al cargar el curso.');
  } finally {
    closeLoading(); // Cierra el loading después de que se complete la carga.
  }
});

// Función para detectar si el medio es una imagen
const esImagen = (media) => {
  return media && /\.(jpg|jpeg|png|gif)$/i.test(media);
};

// Función para detectar si el medio es un video
const esVideo = (media) => {
  const plataformasVideo = [
    'youtube.com', 'youtu.be',     // YouTube
    'vimeo.com',                   // Vimeo
    'dailymotion.com', 'dai.ly',   // Dailymotion
  ];

  // Comprobar si la URL pertenece a una plataforma soportada o es un archivo de video local
  const esVideoPlataforma = plataformasVideo.some((plataforma) =>
    media.includes(plataforma)
  );

  const esArchivoVideo = /\.(mp4|webm|ogg|mov)$/i.test(media); // Archivos de video

  return esVideoPlataforma || esArchivoVideo;
};
// Función para abrir el video en una nueva pestaña
const abrirEnNuevaPestana = (url) => {
  window.open(url, '_blank');
};


const mostrarMedia = (media, esImagen) => {
  if (esImagen) {
    Swal.fire({
      imageUrl: media,
      imageAlt: 'Imagen',
      showCloseButton: true,
      background: '#f0f4f8',
      confirmButtonColor: '#38a169',
    });
  } else {
    Swal.fire({
      html: `<iframe src="${media}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="iframe-modal"></iframe>`,
      showCloseButton: true,
      background: '#f0f4f8',
      confirmButtonColor: '#38a169',
      width: '80%',
    });
  }
};
</script>

<template>
  <Header />
  <div class="curso-container mx-auto max-w-4xl p-6 md:p-12 bg-white shadow-lg rounded-xl animate-fadeIn">
    <h1 class="text-3xl md:text-4xl font-bold text-center text-green-700 mb-8">{{ curso.cursoName }}</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
      <div class="info-card">
        <h2 class="font-semibold text-xl mb-2">Requisitos</h2>
        <p class="text-gray-600">{{ curso.cursoRequisito }}</p>
      </div>
      <div class="info-card">
        <h2 class="font-semibold text-xl mb-2">Descripción del Curso</h2>
        <p class="text-green-600 text-2xl font-bold">{{ curso.cursoDescripcion }}</p>
      </div>
    </div>

    <div
      v-for="(block, index) in curso.cursoContenido"
      :key="index"
      class="content-block mb-12 p-6 bg-white shadow-lg rounded-xl animate-slideUp"
    >
      <h2 class="text-2xl font-semibold mb-4 text-green-700">{{ block.titulo }}</h2>

      <!-- Verificar y mostrar diferentes tipos de medios -->
      <template v-if="esImagen(block.media)">
        <img
          :src="block.media"
          alt="Imagen"
          class="media-image"
          @click="mostrarMedia(block.media, true)"
        />
      </template>

      <template v-else-if="esVideo(block.media)">
        <div class="video-placeholder">
          <p class="text-red-500 mb-2">El video está bloqueado en esta red.</p>
          <button
            class="btn-open-video"
            @click="abrirEnNuevaPestana(block.media)"
          >
            Abrir en YouTube
          </button>
        </div>
      </template>

      <template v-else>
        <p class="text-gray-600">Este medio no se puede mostrar.</p>
      </template>

      <p class="text-gray-600 mt-4">{{ block.concepto }}</p>
    </div>

    <button
      v-if="pasado === 'false'"
      @click="mostrarExamen = true"
      class="btn-examen mt-8"
    >
      Resolver Examen
    </button>

    <Examen v-if="mostrarExamen" :examen="curso.cursoExamen" :claseId="props.cursoId" :cursoId="curso.cursoId" @cerrar="mostrarExamen = false" />
  </div>
</template>

<style scoped>
/* Estilos para video bloqueado */
.video-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: #fafafa;
  padding: 1.5rem;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-open-video {
  background-color: #ff5252;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn-open-video:hover {
  background-color: #ff1744;
}
/* Animaciones */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.animate-fadeIn {
  animation: fadeIn 0.6s ease-in-out;
}

.animate-slideUp {
  animation: slideUp 0.6s ease-in-out;
}

/* Contenedor principal */
.curso-container {
  background-image: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);
  max-width: 800px;
  margin: 0 auto;
  padding: 3rem 2rem;
  border-radius: 16px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

/* Ajuste de imagen */
.media-image {
  width: 100%;
  max-height: 400px;
  object-fit: cover;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  margin-bottom: 1rem;
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.media-image:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

/* Contenedor responsivo para videos */
.media-video-container {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* Relación de aspecto 16:9 */
  height: 0;
  margin-bottom: 1rem;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  cursor: pointer;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.media-video-container:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

.media-video {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

/* Botón de examen */
.btn-examen {
  display: block;
  width: 100%;
  background-color: #4caf50;
  color: white;
  font-weight: bold;
  padding: 1rem;
  border-radius: 12px;
  transition: background-color 0.3s, transform 0.2s;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.btn-examen:hover {
  background-color: #388e3c;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.btn-examen:active {
  transform: translateY(1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Tarjetas de información */
.info-card {
  background-color: #fafafa;
  padding: 1.5rem;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s, box-shadow 0.3s;
  margin-bottom: 1rem;
}

.info-card:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

/* Bloques de contenido */
.content-block {
  margin-bottom: 2rem;
  padding: 2rem;
  background-color: #ffffff;
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  animation: slideUp 0.6s ease-in-out;
  transition: transform 0.3s, box-shadow 0.3s;
}

.content-block:hover {
  transform: scale(1.02);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}
</style>


