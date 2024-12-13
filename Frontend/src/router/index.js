import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Clases from '../views/Clases.vue';
import Admin from '../views/Admin.vue';
import Register from '../views/Register.vue';
import AdminCursos from '../views/AdminCursos.vue';
import Usuarios from '../views/Usuarios.vue';
import Categorias from '../views/Categorias.vue';
import Docentes from '../views/Docentes.vue';
import Perfil from '../views/Perfil.vue';
import Homead from '../views/Homead.vue';
import Cursos from '../views/Cursos.vue';
import { Sesion } from '../store/sesion';
import HomeEs from '../views/HomeEs.vue';
import Transacciones from '../views/Transacciones.vue';
import TeacherCursos from '../views/TeacherCursos.vue';
import Swal from 'sweetalert2';

const router = createRouter({
    history: createWebHistory('/Ulemi/'), 
    routes: [
        { path: '/', name: 'Home', component: Home, meta: { requiresAuth: false } },
        { path: '/login', name: 'Login', component: Login, meta: { requiresAuth: false } },
        { path: '/register', name: 'Register', component: Register, meta: { requiresAuth: false } },
        { path: '/cursos/:categoriaId', name: 'Cursos', component: Cursos, meta: { requiresAuth: false }, props: true  },
        { path: '/clases/:claseId/:pasado/:cursoId', name: 'Clases', component: Clases, meta: { requiresAuth: true }, props: true },
        { path: '/admin', name: 'Admin', component: Admin, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/adcursos', name: 'AdminCursos', component: AdminCursos, meta: { requiresAuth: true, role: ['admin'] } },
        { path: '/tccursos', name: 'TeacherCursos', component: TeacherCursos, meta: { requiresAuth: true, role: ['teacher'] } },
        { path: '/docentes', name: 'Docentes', component: Docentes, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/categorias', name: 'Categorias', component: Categorias, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/usuarios', name: 'Usuarios', component: Usuarios, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/perfil', name: 'Perfil', component: Perfil, meta: { requiresAuth: true, role: ['teacher', 'student'] } },
        { path: '/homead', name: 'homead', component: Homead, meta: { requiresAuth: true, role: ['admin', 'teacher'] } },
        { path: '/homees', name: 'homeEs', component: HomeEs, meta: { requiresAuth: true, role: ['student'] } },
        { path: '/transacciones', name: 'Transacciones', component: Transacciones, meta: { requiresAuth: true, role: ['admin'] } },
    ]
});

// Navigation guard mejorado
router.beforeEach(async (to, from, next) => {
    const sesionStore = Sesion(); // Obtén el store de sesión

    if (sesionStore.token) {
        const response = await sesionStore.checkSesion(sesionStore.token);
        console.log(response);  
        if(response.status === 401){
            console.log('hola');
            sesionStore.logout();
        }
    }

    // Obtener los datos de la sesión
    await sesionStore.getSesion();
    const userRole = sesionStore.rol;
    const isAuthenticated = !!userRole;
    console.log(userRole);

    // Redirigir según el rol y la ruta
    if (to.name === 'Home') {
        if (userRole === 'admin' || userRole === 'teacher') {
            return next({ name: 'homead' });
        } else if (userRole === 'student') {
            return next({ name: 'homeEs' });
        }
    }

    // Restricción de acceso a Login y Registro si ya está autenticado
    if (isAuthenticated && (to.name === 'Login' || to.name === 'Register')) {
        Swal.fire('Ya estás autenticado', 'No puedes acceder a esta página.', 'warning');
        return next({ path: '/' });
    }

    // Verificar si la ruta requiere autenticación
    if (to.meta.requiresAuth && !isAuthenticated) {
        Swal.fire('No autenticado', 'Debes iniciar sesión para acceder a esta página.', 'warning');
        return next({ name: 'Login' });
    }

    // Verificar restricciones de rol
    if (to.meta.role) {
        const allowedRoles = Array.isArray(to.meta.role) ? to.meta.role : [to.meta.role];
        if (!allowedRoles.includes(userRole)) {
            Swal.fire('Acceso denegado', 'No tienes permiso para acceder a esta página.', 'error');
            return next({ path: '/' });
        }
    }

    // Permitir navegación si todo está correcto
    next();
});




export default router;
