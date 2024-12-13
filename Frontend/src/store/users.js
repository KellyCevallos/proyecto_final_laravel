import { defineStore } from 'pinia'
import Docentes from '../views/Docentes.vue';
export const User = defineStore('userStore',{
    state: ()=>(
        {
            user: [],
            url: import.meta.env.VITE_API_URL,
            docentes: [],
        }
    ),
    actions:{
        async registrar(formData){
            try {
                console.log(formData);  
                const response = await fetch(`${this.url}/postUser`,{
                    method: 'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                    },
                    credentials:'include',
                    body: JSON.stringify(formData),
                });
                const data = await response.json()
                return data;
            } catch (error) {
                return error;
            }
        },
        //get de usuario que pertenecen a un curso
        async getUserCurso(token, id){
            try {
                const response = await fetch(`${this.url}/getUserCurso/${id}`,{
                    method: 'GET',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                });
                const data = await response.json()
                console.log(data);
                this.user = data;
                return data;
            } catch (error) {
                return error;
            }
        },
        async getDocentes(token, page){
            try {
                const response = await fetch(`${this.url}/getDocentes?page=${page}`,{
                    method: 'GET',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                });
                const data = await response.json()
                console.log(data);
                this.docentes = data;
            } catch (error) {
                return error;
            }
        },
        async createDocente(token, formData){
            console.log(formData);
            try {
                const response = await fetch(`${this.url}/postDocente`,{
                    method: 'POST',
                    headers:{
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                    body: formData,
                });
                console.log(response);
                const data = await response.json()
                console.log(data);
                return data;
            } catch (error) {
                return error;
            }
        },
        async deleteDocente(token, id){
            try {
                const response = await fetch(`${this.url}/deleteUser/${id}`,{
                    method: 'DELETE',
                    headers:{
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                });
                const data = await response.json()
                console.log(data);
                return data;
            } catch (error) {
                return error;
            }
        },
        async updateDocente(token, formData, id){
            console.log(formData);
            try {
                const response = await fetch(`${this.url}/updateUser/${id}`,{
                    method: 'PUT',
                    headers:{
                        'Accept': 'application/json',
                        'Content-Type': 'application/json', // <-- Asegúrate de agregar esta línea
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                    body: JSON.stringify(formData), // Enviar como JSON
                });
                const data = await response.json()
                console.log(data);
                return data;
            } catch (error) {
                return error;
            }
        },
        async updateUserProfile(token, user, id){
            try {
                const response = await fetch(`${this.url}/updateUser/${id}`,{
                    method: 'PUT',
                    headers:{
                        'Accept': 'application/json',
                        'Content-Type': 'application/json', // <-- Asegúrate de agregar esta línea
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                    body: JSON.stringify(user), // Enviar como JSON
                });
                const data = await response.json()
                console.log(data);
                return data;
            } catch (error) {
                return error;
            }
        },
        async getEstudiantes(token, page){
            try {
                const response = await fetch(`${this.url}/getEstudiantes?page=${page}`,{
                    method: 'GET',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    credentials:'include',
                });
                const data = await response.json()
                console.log(data);
                this.docentes = data;
            } catch (error) {
                return error;
            }
        },
    },

})