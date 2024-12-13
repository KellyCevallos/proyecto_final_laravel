<script setup>
import { ref, onMounted } from 'vue';
import { Sesion } from '../store/sesion.js';
import { useRouter } from 'vue-router';
import { sweetalert } from '../composables/sweetAlert'; // Importa SweetAlert

const sesionStore = Sesion();
const router = useRouter();
const { ShowLoading, errorAlert, successAlert } = sweetalert(); // Usamos las funciones de SweetAlert


// Variables del formulario
const formData = ref({
  userName: '',
  password: '',
});

onMounted(async () => {
  await sesionStore.getSesion();
});

const isPasswordVisible = ref(false); // Estado para controlar la visibilidad de la contraseña

const login = async () => {
  const closeLoading = ShowLoading('Verificando', 'Espere un momento...');
  const response = await sesionStore.login(formData.value);
  closeLoading();

  if (response.success == true) {
    successAlert('Inicio de sesión exitoso', 'Bienvenido de nuevo!');
    router.push({name : "Home"});
  } else {
    errorAlert('Error al iniciar sesión', 'Usuario o contraseña incorrectos');
  }
};

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value; // Alternar visibilidad
};

const goToRegister = () => {
  router.push({ name: 'Register' }); // Redirigir a la página de registro
};
</script>

<template>
  <Header></Header>
  <div class="login-container">
    <div class="login-box animate-fade-in">
      <h2>Inicia sesión en tu cuenta</h2>
  
      <form @submit.prevent="login">
        <div class="form-group">
          <input type="text" id="userName" v-model="formData.userName" placeholder="Nombre de usuario" required />
        </div>
  
        <div class="form-group">
          <div class="password-wrapper">
            <input
              :type="isPasswordVisible ? 'text' : 'password'"
              id="password"
              v-model="formData.password"
              placeholder="Contraseña"
              required
            />
            <button type="button" @click="togglePasswordVisibility" aria-label="Toggle password visibility">
              <i :class="isPasswordVisible ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
            </button>
          </div>
        </div>
  
        <button type="submit" class="login-button">Iniciar sesión</button>
      </form>
  
      <!-- <a href="#" class="forgot-password">He olvidado la contraseña</a> -->
      <p class="has-no-account">
        ¿No tienes una cuenta? 
        <a @click="goToRegister">Regístrate</a>
      </p>
    </div>

    <!-- Sección inspiracional sobre la educación -->
    <div class="education-message animate-slide-up">
      <h3>La educación es la clave del éxito</h3>
      <p>
        La educación transforma vidas y abre las puertas hacia un futuro brillante. Al igual que nuestros estudiantes de élite, tú también puedes alcanzar la excelencia profesional con el conocimiento y las habilidades correctas. ¡El primer paso empieza aquí!
      </p>
      <p>
        <strong>Ejemplo:</strong> Mira cómo nuestros estudiantes, como el profesional que ves en la imagen, han alcanzado sus metas a través de nuestra plataforma educativa. Tú puedes ser el próximo.
      </p>
    </div>

    <div class="login-image animate-fade-in-right">
      <img src="/src/assets/img/gustavinlogin.png" class="cta-image img-fluid" />
    </div>
  </div>
</template>

<style scoped>
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

@keyframes fadeInRight {
  from {
    transform: translateX(50px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Aplicando las animaciones */
.animate-fade-in {
  animation: fadeIn 1s ease-in-out;
}

.animate-slide-up {
  animation: slideUp 1.2s ease-out;
}

.animate-fade-in-right {
  animation: fadeInRight 1.5s ease-in-out;
}

/* Contenedor principal del login */
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(90deg, #0f3d28, #0d0f0c); /* Fondo con degradado */
  padding: 2rem;
  gap: 4rem; /* Aumenta el espacio entre el formulario y la imagen */
}

.login-box {
  background-color: white;
  padding: 2rem 2.5rem;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  width: 400px;
  max-width: 100%;
}

/* Título del formulario */
.login-box h2 {
  text-align: center;
  margin-bottom: 2rem;
  font-size: 1.8rem;
  color: #0f3d28;
}

/* Agrupación de los campos */
.form-group {
  margin-bottom: 1.5rem;
}

.form-group input {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid #ccc;
  border-radius: 10px;
  font-size: 1rem;
  background-color: #f8f9fa;
}

.form-group input:focus {
  border-color: #3ecf8e; /* Verde claro al hacer focus */
  outline: none;
  box-shadow: 0 0 5px rgba(62, 207, 142, 0.4);
}

/* Ajuste de la caja de contraseña */
.password-wrapper {
  position: relative;
}

.password-wrapper button {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: #3ecf8e; /* Color del ícono */
}

.login-button {
  width: 100%;
  padding: 0.9rem;
  background-color: #3ecf8e;
  color: white;
  border: none;
  border-radius: 25px;
  font-size: 1.2rem;
  cursor: pointer;
  margin-top: 1rem;
}

.login-button:hover {
  background-color: #35b67b; /* Hover del botón */
}

/* Texto olvidó contraseña */
.forgot-password {
  display: block;
  margin-top: 1rem;
  color: #0f3d28;
  text-align: center;
}

/* Texto para quienes no tienen cuenta */
.has-no-account {
  text-align: center;
  margin-top: 1rem;
  color: #0f3d28;
}

.has-no-account a {
  color: #3ecf8e;
  cursor: pointer;
}

/* Sección con mensaje sobre la importancia de la educación */
.education-message {
  max-width: 350px;
  text-align: center;
  color: white;
}

.education-message h3 {
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
}

.education-message p {
  font-size: 1.1rem;
  margin-bottom: 0.8rem;
}

/* Imagen en la parte derecha */
.login-image {
  max-width: 300px; /* Ajustar el tamaño máximo de la imagen */
  display: flex;
  justify-content: center;
  align-items: center;
  flex-shrink: 0; /* Evitar que la imagen se reduzca demasiado */
}

.login-image img {
  width: 100%;
  max-width: 250px;
}

/* Responsividad */
@media (max-width: 768px) {
  .login-container {
    flex-direction: column;
    padding: 2rem;
    text-align: center;
  }

  .login-image {
    display: none; /* Ocultar imagen en pantallas pequeñas */
  }

  .login-box {
    width: 100%;
    max-width: 90%;
  }

  .education-message {
    display: none; /* Ocultar la sección inspiracional en pantallas pequeñas */
  }
}
</style>

