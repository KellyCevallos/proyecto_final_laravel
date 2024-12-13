<script setup>
import { ref } from 'vue';
import { Sesion } from '../store/sesion.js';
import { useRouter } from 'vue-router';
import { User } from '../store/users.js';
import { sweetalert } from '../composables/sweetAlert';

const sesionStore = Sesion();
const router = useRouter();
const userStore = User();
const { successAlert, errorAlert, ShowLoading } = sweetalert(); 

const formData = ref({
  userNombres: '',
  userApellidos: '',
  userName: '',
  userCorreo: '',
  password: '',
  confirmPassword: '',
  userWordKey: ''
});

const isPasswordVisible = ref(false); // Estado para la visibilidad de la contraseña
const isConfirmPasswordVisible = ref(false); // Estado para la visibilidad de la confirmación de contraseña

const register = async () => {
  if (formData.value.password !== formData.value.confirmPassword) {
    errorAlert('Error', 'Las contraseñas no coinciden');
    return;
  }

  const closeLoading = ShowLoading();

  try {
    const response = await userStore.registrar(formData.value);
    closeLoading(); 
    if (response.success) {
      successAlert('Registro exitoso', response.message);
      router.push({ name: 'Login' });
    } else {
      const errorMessages = Object.values(response.errors)
        .flat()
        .map(error => `<p>${error}</p>`) 
        .join(''); 
      errorAlert('Error en el registro', `Por favor revise los campos: ${errorMessages}`, true); 
    }
  } catch (error) {
    closeLoading(); 
    console.error('Error en el registro:', error);
    errorAlert('Error', 'Ocurrió un error al crear la cuenta');
  }
};

const togglePasswordVisibility = () => {
  isPasswordVisible.value = !isPasswordVisible.value;
};

const toggleConfirmPasswordVisibility = () => {
  isConfirmPasswordVisible.value = !isConfirmPasswordVisible.value;
};

const goToLogin = () => {
  router.push({ name: 'Login' });
};
</script>

<template>
  <Header></Header>
  <div class="register-container">
    <div class="register-left animate-slide-in-left">
      <h1>¡Únete a nuestra comunidad!</h1>
      <p>Accede a más de 6,000 cursos en línea. Es gratis y fácil de empezar.</p>
    </div>
    <div class="register-box animate-slide-in-right">
      <h2>Crea tu cuenta</h2>
      <form @submit.prevent="register">
        <div class="form-group-inline">
          <div class="form-group">
            <input type="text" id="userNombres" v-model="formData.userNombres" placeholder="Nombres" required />
          </div>
          <div class="form-group">
            <input type="text" id="userApellidos" v-model="formData.userApellidos" placeholder="Apellidos" required />
          </div>
        </div>

        <div class="form-group">
          <input type="text" id="userName" v-model="formData.userName" placeholder="Nombre de usuario" required />
        </div>

        <div class="form-group">
          <input type="email" id="userCorreo" v-model="formData.userCorreo" placeholder="Correo electrónico" required />
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

        <div class="form-group">
          <div class="password-wrapper">
            <input
              :type="isConfirmPasswordVisible ? 'text' : 'password'"
              id="confirmPassword"
              v-model="formData.confirmPassword"
              placeholder="Confirmar contraseña"
              required
            />
            <button type="button" @click="toggleConfirmPasswordVisibility" aria-label="Toggle confirm password visibility">
              <i :class="isConfirmPasswordVisible ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="register-button">Crear cuenta</button>
      </form>

      <p class="has-account">
        ¿Ya tienes una cuenta?
        <a @click="goToLogin">Inicia sesión</a>
      </p>
    </div>
  </div>
</template>

<style scoped>
/* Animaciones */
@keyframes slideInLeft {
  from {
    transform: translateX(-50px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideInRight {
  from {
    transform: translateX(50px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.animate-slide-in-left {
  animation: slideInLeft 1s ease-out;
}

.animate-slide-in-right {
  animation: slideInRight 1s ease-out;
}

/* Contenedor principal del registro */
.register-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: linear-gradient(90deg, #0f3d28, #0d0f0c); /* Degradado */
  padding: 2rem;
  gap: 3.5rem; /* Aumentar el espacio entre los elementos */
}

/* Panel izquierdo con el mensaje */
.register-left {
  flex: 1;
  color: white;
  padding: 2rem;
  text-align: center;
  max-width: 450px; /* Ancho máximo del texto */
}

.register-left h1 {
  font-size: 2.8rem;
  margin-bottom: 1rem;
  color: #3ecf8e; /* Verde claro */
}

.register-left p {
  font-size: 1.2rem;
  color: #f0f0f0;
}

/* Caja de registro */
.register-box {
  background-color: white;
  padding: 2rem 2.5rem;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  width: 400px;
  max-width: 100%;
}

/* Título del formulario */
.register-box h2 {
  text-align: center;
  margin-bottom: 2rem;
  font-size: 1.8rem;
  color: #0f3d28;
}

/* Agrupación de los campos */
.form-group {
  margin-bottom: 1.5rem;
}

.form-group-inline {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
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

.register-button {
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

.register-button:hover {
  background-color: #35b67b;
}

.has-account {
  text-align: center;
  margin-top: 1.5rem;
  color: #0f3d28;
}

.has-account a {
  color: #3ecf8e;
  cursor: pointer;
}

/* Responsividad */
@media (max-width: 768px) {
  .register-container {
    flex-direction: column;
  }

  .register-left {
    display: none; /* Ocultar panel izquierdo en pantallas pequeñas */
  }

  .register-box {
    width: 100%;
    max-width: 90%;
  }
}
</style>



