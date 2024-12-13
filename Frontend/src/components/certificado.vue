<template>
  <div v-if="showModal" class="modal-overlay" @click="$emit('cerrar')">
    <div class="certificado-container">
      <div class="certificado-content">
        <h1 class="certificado-title">Certificado</h1>
        <h2 class="certificado-subtitle">DE RECONOCIMIENTO A:</h2>

        <!-- Información del usuario -->
        <div class="certificado-usuario">{{ curso.usuario.userNombres }} {{ curso.usuario.userApellidos }}</div>

        <p class="certificado-text">
          Por haber completado satisfactoriamente el curso:
        </p>
        <div class="certificado-curso">{{ curso.curso.cursoName }}</div>

        <p class="certificado-text">
          Con una calificación final de:
        </p>
        <div class="certificado-calificacion">{{ curso.relNota }} / 10</div>

        <!-- Firma y pie de página -->
        <div class="certificado-footer">
          <div class="firma-container">
            <p class="certificado-firma">{{ curso.curso.createdBy }}</p>
            <hr class="firma-linea-ajustada" />
            <p class="firma-docente">Firma Docente</p>
          </div>
          <p class="certificado-firma">Fecha: {{ curso.updated_at.split('T')[0] }}</p>
        </div>
        <button class="print-button no-print" @click="printCertificado">Imprimir Certificado</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref } from 'vue';

const props = defineProps({
  curso: {
    type: Object,
    required: true,
  },
});

const showModal = ref(true);

const closeModal = () => {
  showModal.value = false;
};

const printCertificado = () => {
  window.print();
};
</script>

<style scoped>
@page {
  size: landscape;
  margin: 0;
}

@media print {
  * {
    visibility: hidden;
  }
  .certificado-container, .certificado-container * {
    visibility: visible;
  }
  .certificado-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    padding: 1rem;
    background-image: url('https://raw.githubusercontent.com/alexmnt710/Aplicaciones-Web-LRPQTP/main/Frontend/src/assets/img/certificado.png');
    background-size: 100% 100%;
    background-position: center;
    box-shadow: none;
  }
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.certificado-container {
  width: 95%;
  max-width: 1200px;
  padding: 2rem;
  border-radius: 10px;
  background-image: url('https://raw.githubusercontent.com/alexmnt710/Aplicaciones-Web-LRPQTP/main/Frontend/src/assets/img/certificado.png');
  background-size: cover;
  background-position: center;
  box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 1.5rem;
  position: relative;
}

.certificado-content {
  padding: 2rem;
  border-radius: 10px;
  width: 100%;
}

.close-button {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: transparent;
  border: none;
  font-size: 2rem;
  cursor: pointer;
  color: #333;
}

.certificado-title,
.certificado-subtitle,
.certificado-usuario,
.certificado-text,
.certificado-curso,
.certificado-calificacion {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.certificado-title {
  font-size: 2.5rem;
  color: #2c3e50;
  margin-bottom: 0.5rem;
  text-transform: uppercase;
}

.certificado-subtitle {
  font-size: 1.5rem;
  color: #34495e;
  margin-bottom: 1.5rem;
  font-weight: 400;
}

.certificado-usuario {
  font-size: 2rem;
  font-weight: bold;
  margin: 1rem 0;
}

.certificado-curso {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2980b9;
  margin-bottom: 1rem;
}

.certificado-calificacion {
  font-size: 1.8rem;
  font-weight: bold;
  color: #27ae60;
  margin-bottom: 1rem;
}

.certificado-text {
  font-size: 1.2rem;
  color: #7f8c8d;
}

.print-button {
  margin-top: 1.5rem;
  padding: 0.75rem 1.5rem;
  background-color: #27ae60;
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1.2rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.print-button:hover {
  background-color: #2ecc71;
}

.certificado-footer {
  margin-top: 2rem;
  text-align: center;
}

.firma-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.certificado-firma {
  font-size: 1.2rem;
  font-family: 'Dancing Script', cursive; /* Fuente estilo manuscrita */
  color: #333;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.firma-linea-ajustada {
  width: 50%; /* Ajuste de la longitud de la línea */
  border: none;
  border-top: 2px solid #333;
  margin: 0 auto;
}

.firma-docente {
  font-size: 1rem;
  color: #333;
}
</style>