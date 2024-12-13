<script setup>
import { ref, onMounted } from 'vue';
import { Cursos } from '../store/cursos';
import { sweetalert } from '../composables/sweetAlert';
import { useRouter } from 'vue-router';
import { Sesion } from '../store/sesion';


const sweetAlert = sweetalert(); 
const cursoStore = Cursos();
const sesionStore = Sesion();  
const cursos = ref([]);
const router = useRouter();  

const mostrarRequisitos = (cursoRequisito) => {
  sweetAlert.showAlert("Requisitos", cursoRequisito || "No hay requisitos especificados");
}

// coso para ir al coso con sesion
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

onMounted(async () => {
  const closeLoading = sweetAlert.ShowLoading();
  await cursoStore.getCursosHome().then(() => {
    cursos.value = cursoStore.cursos;
  });
  try {
  } catch (error) {
    sweetAlert.errorAlert("Error", "Error al obtener los cursos");
    console.error(error);
  } finally {
    closeLoading();
  }
});

const goToRegister = () => {
  router.push({ name: 'Register' });
};

</script>

<template>
  <!-- Gustavin -->
  <section v-if="!sesionStore.sesion" class="cta-section">
    <div class="container">
      <div class="row align-items-center">
        <!-- Texto principal -->
        <div class="col-md-6">
          <h1>Estudia gratis y certifícate con +6,000 cursos online</h1>
          <p>Acelera tu futuro con nuestros cursos certificados.</p>
          <button class="btn btn-custom" @click="goToRegister">Crear cuenta gratis</button>
          <p class="cta-disclaimer">*Sin datos de tarjetas y sin llamadas de ventas.</p>
        </div>
        <!-- Imagen -->
        <div class="col-md-6">
          <img src="/src/assets/img/gustavinHome.png" alt="Certificado" class="cta-image img-fluid">
        </div>
      </div>
    </div>
  </section>

  <!-- Carrusel -->
  <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
    <!-- Indicadores de carrusel -->
    <div class="carousel-indicators">
      <button
        type="button"
        data-bs-target="#carouselExample"
        v-for="(curso, index) in cursos"
        :key="curso.cursoId"
        :data-bs-slide-to="index"
        :class="{ active: index === 0 }"
        aria-current="index === 0 ? 'true' : 'false'"
        aria-label="Slide"
      ></button>
    </div>

    <!-- Diapositivas del carrusel -->
    <div class="carousel-inner">
      <div
        v-for="(curso, index) in cursos"
        :key="curso.cursoId"
        :class="['carousel-item', { active: index === 0 }]"
      >
        <div class="d-flex align-items-center" style="min-height: 400px;">
          <div class="container">
            <div class="row">
              <!-- Contenido izquierdo -->
              <div class="col-md-6 d-flex flex-column justify-content-center">
                <h2>{{ curso.cursoName }}</h2>
                <p>{{ curso.cursoDescripcion }}</p>
                <p>
                  <strong>Dificultad:</strong> {{ curso.nivel.nivelName }} 
                </p>
                <!-- Botón para mostrar requisitos -->
                <button class="btn btn-custom" @click="mostrarRequisitos(curso.cursoRequisito)">
                  Ver Requisitos
                </button>
                <br>
                <button class="btn btn-custom" @click="irAVistaCurso(curso.cursoId)">
                  Ver Curso
                </button>
                <br>
              </div>

              <!-- Imagen del curso -->
              <div class="col-md-6">
                <img
                  :src="curso.categoria.categoriaImagen || 'ruta/imagen/por/defecto.jpg'"
                  class="d-block w-100 img-fluid"
                  alt="Imagen del curso"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Controles de navegación -->
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

</template>

<style scoped>
/* Fondo para la sección superior */
.cta-section {
  background-color: #d1e7dd; /* Verde grisáceo claro */
  padding: 4rem 0;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
}

.cta-section h1 {
  font-size: 2.5rem;
  font-weight: 700;
  color: #0f3d28; /* Verde oscuro */
  margin-bottom: 1rem;
}

.cta-section p {
  color: #0f3d28;
  font-size: 1.2rem;
  margin-bottom: 1rem;
}

.cta-section img {
  max-width: 80%;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease-in-out;
}

.cta-section img:hover {
  transform: scale(1.05); /* Efecto de zoom al pasar el mouse */
}

/* Fondo para la sección inferior */
.carousel-inner {
  background-color: #f8f9fa; /* Color de fondo más claro para contraste */
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

/* Botones de navegación */
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: rgba(0, 0, 0, 0.6); /* Fondo oscuro con opacidad */
  border-radius: 50%;
  width: 30px;
  height: 30px;
  transition: background-color 0.3s ease;
}

.carousel-control-prev-icon:hover,
.carousel-control-next-icon:hover {
  background-color: rgba(0, 0, 0, 0.8); /* Color más oscuro al hacer hover */
}

.carousel-control-prev,
.carousel-control-next {
  top: 50%;
  transform: translateY(-50%);
  z-index: 2;
}

.carousel-control-prev {
  left: -10px;
}

.carousel-control-next {
  right: -10px;
}

/* Indicadores del carrusel */
.carousel-indicators [data-bs-target] {
  background-color: #333;
  border-radius: 50%;
  width: 10px;
  height: 10px;
  transition: background-color 0.3s ease;
}

.carousel-indicators .active {
  background-color: #3ecf8e; /* Indicador activo en verde claro */
}

/* Textos del carrusel */
h2 {
  color: #0f3d28;
  font-size: 1.8rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

p {
  color: #555;
  font-size: 1.1rem;
  margin-bottom: 1rem;
}

p strong {
  color: #0f3d28;
  font-weight: 600;
}

/* Botones */
.btn {
  background-color: #0f3d28;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 5px;
  font-size: 1.1rem;
  margin-top: 0.5rem;
  transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
}

.btn:hover {
  background-color: #3ecf8e; /* Fondo verde claro al hacer hover */
  color: #0f3d28; /* Texto en verde oscuro */
  box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
}

/* Ajuste de las imágenes del carrusel */
.carousel-item img {
  width: 100%;
  height: auto;
  object-fit: cover;
  border-radius: 10px;
  transition: transform 0.3s ease-in-out;
}

.carousel-item img:hover {
  transform: scale(1.03); /* Pequeño zoom al hacer hover */
}

/* Responsividad */
@media (max-width: 768px) {
  .cta-section {
    padding: 2rem 0;
  }

  .cta-section h1 {
    font-size: 2rem;
  }

  .cta-section p {
    font-size: 1rem;
  }

  .carousel-inner {
    min-height: 300px;
  }

  .carousel-item img {
    max-height: 250px;
  }

  .carousel .row {
    flex-direction: column;
    text-align: center;
  }

  .col-md-6 {
    width: 100%;
  }

  .carousel-control-prev,
  .carousel-control-next {
    top: auto;
    bottom: 10px;
  }
}
</style>






