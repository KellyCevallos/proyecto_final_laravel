import { defineStore } from 'pinia';

export const Sesion = defineStore('sesionStore', {
  state: () => ({
    sesion: false,
    user: null, 
    token: null,
    rol: null,
    url: import.meta.env.VITE_API_URL,
  }),
  actions: {
    async getSesion() {
      try {
        const userData = localStorage.getItem('user');
        this.user = userData ? JSON.parse(userData) : null;
        this.token = localStorage.getItem('token');
        this.rol = localStorage.getItem('rol');
        if (this.user && this.token && this.rol) {
          this.sesion = true; // Marcar la sesión como válida
        } else {
          this.sesion = false; // No hay sesión activa
        }
      } catch (error) {
        this.sesion = false;
        return error;
      }
    },
    async login(formData) {
      try {
        const response = await fetch(`${this.url}/login`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          credentials: 'include',
          body: JSON.stringify(formData),
        });
        const data = await response.json();
        console.log(data);
        if (data.success === true) {
          localStorage.setItem('user', JSON.stringify(data.user));
          localStorage.setItem('token', data.token);
          localStorage.setItem('rol', data.rol);
          this.user = data.user; // Asigna el objeto directamente al estado
          this.token = data.token;
          this.rol = data.rol;
          this.sesion = true;
          return data;
        } else {
          this.sesion = false;
          return data;
        }
      } catch (error) {
        this.sesion = false;
        return error;
      }
    },
    async logout(token) {
      try {
        const response = await fetch(`${this.url}/logout`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          credentials: 'include',
          body: JSON.stringify({ token }),
        });
        const data = await response.json();
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        localStorage.removeItem('rol');
        this.user = null;
        this.token = null;
        this.rol = null;
        this.sesion = false;
        return data.message;
      } catch (error) {
        return error;
      }
    },
    async checkSesion(token) {
      try {
        const response = await fetch(`${this.url}/checksesion`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            Authorization: `Bearer ${token}`,
          },
          credentials: 'include',
        });
        const data = await response.json();
        console.log(data);
        if (data.message != 'Unauthenticated.') {
          this.sesion = true;
          return true;
        } else {
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        localStorage.removeItem('rol');
        this.user = null;
        this.token = null;
        this.rol = null;
        this.sesion = false;
          return false;
        }
      } catch (error) {
        this.sesion = false;
        return error;
      }
    },
  },
});
