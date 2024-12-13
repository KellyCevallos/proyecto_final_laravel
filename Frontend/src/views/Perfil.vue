<script setup>
import { Sesion } from '../store/sesion';
import { useRouter } from 'vue-router';
import { onMounted, ref, watch, reactive, computed } from 'vue';
import { sweetalert } from '../composables/sweetAlert';
import { User } from '../store/users';

const sesionStore = Sesion();
const router = useRouter();
const sweetAlert = sweetalert();
const userStore = User();

const userData = reactive({
  userName: '',
  userNombres: '',
  userApellidos: '',
  userCorreo: '',
  password: '',
  verificarPassword: '',
  roles: []
});

const cambiosRealizados = ref(false); // Detecta cambios
const camposCompletos = ref(false); // Detecta si todos los campos están llenos

// Verificar si todos los campos obligatorios están llenos
const verificarCamposCompletos = computed(() => {
  return (
    userData.userName &&
    userData.userNombres &&
    userData.userApellidos &&
    userData.userCorreo &&
    (!userData.password || userData.verificarPassword) // Solo si password tiene valor, verificar también verificarPassword
  );
});

// Cargar los datos del usuario al montar el componente
onMounted(async () => {
  try {
    const user = sesionStore.user;
    console.log('Datos del usuario obtenidos:', user);

    if (user) {
      Object.assign(userData, {
        userName: user.userName,
        userNombres: user.userNombres,
        userApellidos: user.userApellidos,
        userCorreo: user.userCorreo,
        roles: user.roles
      });
      console.log('Datos asignados a userData:', userData);
    } else {
      console.error('No se encontraron datos de usuario');
      sweetAlert.errorAlert('Error', 'No se encontraron datos del usuario.');
    }
  } catch (error) {
    console.error('Error al cargar el perfil:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al cargar el perfil.');
  }
});

// Detectar cambios en los datos para habilitar el botón de guardar
watch(
  () => ({
    userName: userData.userName,
    userNombres: userData.userNombres,
    userApellidos: userData.userApellidos,
    userCorreo: userData.userCorreo,
    password: userData.password,
    verificarPassword: userData.verificarPassword
  }),
  (newVal, oldVal) => {
    cambiosRealizados.value = JSON.stringify(newVal) !== JSON.stringify(oldVal);
    camposCompletos.value = verificarCamposCompletos.value;
  },
  { deep: true }
);

// Función para guardar los cambios del perfil
const guardarPerfil = async () => {
  if (userData.password !== userData.verificarPassword) {
    sweetAlert.errorAlert('Error', 'Las contraseñas no coinciden.');
    return;
  }
  
  
  const perfilActualizado = {
    userName: userData.userName,
    userNombres: userData.userNombres,
    userApellidos: userData.userApellidos,
    userCorreo: userData.userCorreo,
    ...(userData.password && { password: userData.password }) // Solo enviar password si se ingresó
  };

  try {
    //verificar que todos los campos de password esten llenos
  if (userData.password && !userData.verificarPassword) {
    sweetAlert.errorAlert('Error', 'Debe verificar la contraseña.');
    return;
  }
    console.log('Guardando perfil con los datos:', perfilActualizado);

    const response = await userStore.updateUserProfile(sesionStore.token,perfilActualizado, sesionStore.user.userId);

    if (response.success) {
      sweetAlert.successAlert('Éxito', 'Perfil actualizado correctamente.');
      cambiosRealizados.value = false; // Deshabilitar botón de guardar
    } else {
      sweetAlert.errorAlert('Error', response.message);
    }
  } catch (error) {
    console.error('Error al guardar el perfil:', error);
    sweetAlert.errorAlert('Error', 'Hubo un problema al actualizar el perfil.');
  }
};
</script>

<template>

  <div class="perfil-container mx-auto p-8 shadow-lg rounded-xl">
    <div class="icono-usuario">
      <i class="bi bi-person-circle text-green-600"></i>
    </div>

    <h1 class="text-3xl font-bold text-center text-green-700 mb-8">Perfil del Usuario</h1>

    <form @submit.prevent="guardarPerfil">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="userName" class="form-label">Nombre de Usuario</label>
          <input v-model="userData.userName" type="text" id="userName" class="form-control" />
        </div>

        <div>
          <label for="userNombres" class="form-label">Nombres</label>
          <input v-model="userData.userNombres" type="text" id="userNombres" class="form-control" />
        </div>

        <div>
          <label for="userApellidos" class="form-label">Apellidos</label>
          <input v-model="userData.userApellidos" type="text" id="userApellidos" class="form-control" />
        </div>

        <div>
          <label for="userCorreo" class="form-label">Correo Electrónico</label>
          <input v-model="userData.userCorreo" type="email" id="userCorreo" class="form-control" />
        </div>

        <div>
          <label for="rol" class="form-label">Rol</label>
          <input :value="userData.roles[0]?.name" type="text" id="rol" class="form-control" disabled />
        </div>

        <div>
          <label for="password" class="form-label">Nueva Contraseña</label>
          <input v-model="userData.password" type="password" id="password" class="form-control" />
        </div>

        <div>
          <label for="verificarPassword" class="form-label">Verificar Contraseña</label>
          <input v-model="userData.verificarPassword" type="password" id="verificarPassword" class="form-control" />
        </div>
      </div>

      <button
        type="submit"
        class="btn btn-success mt-6"
        :disabled="!cambiosRealizados || !camposCompletos"
      >
        Guardar Cambios
      </button>
    </form>
  </div>

</template>
  
  <style scoped>
  /* Contenedor del perfil */
  .perfil-container {
    max-width: 800px;
    margin-top: 3rem;
    padding: 3rem;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
    background-image: linear-gradient(to bottom, #f8f9fa, #ffffff);
  }
  
  /* Ícono del usuario */
  .icono-usuario {
    margin-bottom: 1rem;
    font-size: 6rem;
    color: #38a169;
    display: flex;
    justify-content: center;
  }
  
  /* Etiquetas de los inputs */
  .form-label {
    font-weight: bold;
    color: #0f3d28;
    margin-bottom: 0.5rem;
    display: block;
    text-align: left;
  }
  
  /* Estilo de los inputs */
  .form-control {
    width: 100%;
    padding: 0.75rem;
    margin-bottom: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s, border-color 0.3s;
  }
  
  .form-control:focus {
    border-color: #38a169;
    box-shadow: 0 0 8px rgba(56, 161, 105, 0.5);
    outline: none;
  }
  
  /* Botón de guardar */
  .btn-success {
    width: 100%;
    padding: 1rem;
    border-radius: 10px;
    background-color: #38a169;
    color: white;
    font-weight: bold;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
  }
  
  .btn-success:hover {
    background-color: #2f855a;
    transform: scale(1.03);
  }
  
  .btn-success:disabled {
    background-color: #a0aec0;
    cursor: not-allowed;
  }
  
  /* Ajustes para dispositivos pequeños */
  @media (max-width: 768px) {
    .perfil-container {
      padding: 2rem;
    }
  
    .icono-usuario {
      font-size: 4rem;
    }
  }
  </style>
  
