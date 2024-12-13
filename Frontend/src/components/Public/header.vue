<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Sesion } from '../../store/sesion';
import { Categoria } from '../../store/categoria';

const isMobileMenuOpen = ref(false);
const router = useRouter();
const sesionStore = Sesion();
const categoriaStore = Categoria();
const dropdownVisible = ref(false);

onMounted(async () => {
  await sesionStore.getSesion();
  await categoriaStore.getCategoriasHeader();
});

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

// Mostrar el dropdown
const showDropdown = () => {
  dropdownVisible.value = true;
};

// Ocultar el dropdown
const hideDropdown = () => {
  dropdownVisible.value = false;
};

const handleLogout = async () => {
  router.push({ name: 'Home' });
  await sesionStore.logout();
};
const cursoIr = (categoriaId) => {
  console.log(categoriaId);
  router.push(`/cursos/${categoriaId}`);
};
</script>

<template>
  <header class="navbar">
    <div class="logo">
      <h1>Ulemi</h1>
    </div>
    <nav class="nav-links" :class="{ 'mobile-menu': isMobileMenuOpen }">
      <ul>
        <li>
          <router-link :to="{ name: 'Home' }"><i class="bi bi-house"></i> Home</router-link>
        </li>
        
        <!-- Botón para mostrar el dropdown de cursos -->
        <li v-if="sesionStore.rol == 'student' || sesionStore.sesion == false" class="dropdown" @mouseover="showDropdown" @mouseleave="hideDropdown">
          <div class="dropdown-toggle">
            <i class="bi bi-book"></i> Cursos
            <i class="bi bi-chevron-down"></i>
          </div>
          <!-- Dropdown contenido ahora como hijo del botón -->
          <ul class="dropdown-content" v-if="dropdownVisible">
            <li v-for="categoria in categoriaStore.categorianormal" :key="categoria.categoriaId" @click="cursoIr(categoria.categoriaId)">
              {{ categoria.categoriaName }}
            </li>
          </ul>
        </li>
        
        <li v-if="sesionStore.sesion && sesionStore.rol !== 'admin'">
          <router-link :to="{ name: 'Perfil' }"><i class="bi bi-person-circle"></i> Perfil</router-link>
        </li>
      </ul>
    </nav>
    <div class="login-button">
      <button v-if="!sesionStore.sesion" class="login-register-button">
        <router-link :to="{ name: 'Register' }"><i class="bi bi-person-plus"></i> Registrarse</router-link>
      </button>
      <button v-if="!sesionStore.sesion" class="login-register-button">
        <router-link :to="{ name: 'Login' }"><i class="bi bi-box-arrow-in-right"></i> Acceder</router-link>
      </button>
      <button v-else @click="handleLogout" class="logout-button">
        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
      </button>
    </div>
    <div class="mobile-menu-icon" @click="toggleMobileMenu">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>
</template>

<style scoped>
/* Estilos para el navbar */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: linear-gradient(90deg, #0f3d28, #0d0f0c); 
  color: white;
  position: relative;
  z-index: 100;
}

.logo h1 {
  color: #3ecf8e; 
  font-size: 1.8rem;
  margin: 0;
}

.nav-links ul {
  display: flex;
  gap: 2rem;
  list-style: none;
  margin: 0;
  padding: 0;
}

.nav-links ul li {
  padding: 0.5rem 1rem;
  border-radius: 5px;
}

.nav-links ul li a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  display: flex;
  align-items: center;
  transition: color 0.3s ease-in-out;
}

.nav-links ul li a i {
  margin-right: 0.5rem;
}

.nav-links ul li:hover {
  background-color: #3ecf8e;
  color: white;
  border-radius: 5px;
}

/* Dropdown contenido */
.dropdown {
  position: relative;
}

.dropdown-content {
  display: flex;
  flex-direction: column;
  position: absolute;
  top: 100%;
  left: 0;
  background-color: #0f3d28;
  border-radius: 5px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  min-width: 200px;
  max-width: 250px;
  max-height: 500px; /* Altura máxima del dropdown */
  overflow-y: auto; /* Habilitar scroll vertical */
  z-index: 1;
  padding: 0;
  margin: 0;
}

.dropdown-content li {
  padding: 0.5rem 1rem;
  cursor: pointer;
  white-space: nowrap;
}

.dropdown-content li:hover {
  background-color: #3ecf8e;
}

/* Botones de sesión */
.login-button {
  display: flex;
  gap: 1rem;
}

.login-register-button {
  background-color: transparent;
  color: #f0f0f0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
  display: flex;
  align-items: center;
}

.login-register-button a {
  text-decoration: none;
  color: #f0f0f0;
}

.login-register-button i {
  margin-right: 0.5rem;
}

.login-register-button:hover {
  background-color: #3ecf8e;
  color: #0f3d28;
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
}

.logout-button {
  background-color: transparent;
  color: #f0f0f0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
  display: flex;
  align-items: center;
}

.logout-button i {
  margin-right: 0.5rem;
}

.logout-button:hover {
  background-color: #3ecf8e;
  color: #0f3d28;
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
}

/* Icono para móviles */
.mobile-menu-icon {
  display: none;
  flex-direction: column;
  justify-content: space-between;
  width: 24px;
  height: 24px;
  cursor: pointer;
}

.mobile-menu-icon span {
  display: block;
  height: 3px;
  background-color: white;
  border-radius: 2px;
  transition: all 0.3s;
}

/* Ocultar menú */
.nav-links.mobile-menu {
  display: none;
}

.nav-links.mobile-menu.open {
  display: block;
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background-color: #0f3d28;
}

.nav-links.mobile-menu ul {
  flex-direction: column;
  gap: 1rem;
  padding: 1rem 0;
}

.nav-links.mobile-menu ul li {
  text-align: center;
}

/* Media Query para pantallas móviles */
@media (max-width: 768px) {
  .nav-links {
    display: none;
  }

  .mobile-menu-icon {
    display: flex;
  }

  .nav-links.mobile-menu {
    display: block;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    background-color: #0f3d28;
    padding: 1rem 0;
  }

  .nav-links.mobile-menu ul {
    flex-direction: column;
    gap: 1rem;
    padding: 0;
  }

  .nav-links.mobile-menu ul li {
    text-align: center;
  }
}

/* Scroll bar personalizado para el dropdown */
.dropdown-content::-webkit-scrollbar {
  width: 8px;
}

.dropdown-content::-webkit-scrollbar-thumb {
  background-color: #3ecf8e;
  border-radius: 4px;
}
</style>