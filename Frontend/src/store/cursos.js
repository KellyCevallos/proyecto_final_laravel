import { defineStore } from 'pinia'
export const Cursos = defineStore('cursoStore',{
    state: ()=>(
        {
            cursos : [],
            cursoTeacher: [],
            cursoIndividual: [],
            url: import.meta.env.VITE_API_URL,
            
        }
    ),
    actions:{
        //get de cursos home
        async getCursosHome(){
            const response = await fetch (`${this.url}/courses`,{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                },
                credentials:'include',
            })
            const data = await response.json()
            this.cursos = data
        },
        //get de cursos por un solo usuario
        async getCursosTeacher(token, id, page){
            const response = await fetch (`${this.url}/getCursosTeacher/${id}?page=${page}`,{
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                    Authorization: `Bearer ${token}`,
                },
                credentials:'include',
            })
            const data = await response.json()
            this.cursoTeacher = data
            return data
        },
        //Get de curso individual
        async getCurso(id) {
            try {
                const response = await fetch(`${this.url}/courses/${id}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                });
                const data = await response.json();
                console.log(data);
                this.cursoIndividual = data;
                return data;
            } catch (error) {
                console.error('Error fetching course:', error);
            }
        },
        //get de cursos global
        async getCursos(token, page, search) {
            const response = await fetch (`${this.url}/getCursos?page=${page}`,{
                //&search=${search}
                method:'GET',
                headers:{
                    'Content-Type':'application/json',
                    'Accept': 'application/json',
                    Authorization: `Bearer ${token}`,
                },
                credentials:'include',
            })
            const data = await response.json()
            console.log(data);
            this.cursos = data

        },
        async getCursosCategorizados(page, id){
          console.log('id', id);
          try{
            const response = await fetch (`${this.url}/getCursosC/${id}?page=${page}`,{
              //&search=${search}
              method:'GET',
              headers:{
                  'Content-Type':'application/json',
                  'Accept': 'application/json',
              },
              credentials:'include',
          })
          const data = await response.json()
          this.cursos = data.data
          }catch(error){
            console.error('Error fetching courses:', error);
          }
        },
        async crearCurso(token, curso) {
          try {
            console.log(curso); // Muestra el objeto antes de enviarlo
            const response = await fetch(`${this.url}/postCurso`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json', // Cambia a JSON
                'Accept': 'application/json',
                Authorization: `Bearer ${token}`,
              },
              credentials: 'include',
              body: JSON.stringify(curso), // Serializa el objeto a JSON
            });
        
            const data = await response.json();
            console.log(data);
            return data;
          } catch (error) {
            console.error('Error creating course:', error);
            throw error; // Lanzar el error para manejarlo en el componente
          }
        },
        async deleteCurso(token, id) {
            try {
              console.log*('id', id);
              const response = await fetch(`${this.url}/deleteCurso/${id}`, {
                method: 'DELETE',
                headers: {
                  'Accept': 'application/json',
                  Authorization: `Bearer ${token}`,
                },
                credentials: 'include',
              });
          
              const data = await response.json();
              console.log(data);
              
              return data;
            } catch (error) {
              console.error('Error deleting course:', error);
              throw error;
            }
          },
          async updateCurso(token, id, curso) {
            console.log('curso', curso);
            console.log('id', id);
            try {
              const response = await fetch(`${this.url}/updateCurso/${id}`, {
                method: 'PUT',
                headers: {
                  'Accept': 'application/json',
                  'Content-Type': 'application/json', // <-- Asegúrate de agregar esta línea
                  Authorization: `Bearer ${token}`,
                },
                credentials: 'include',
                body: JSON.stringify(curso), // Enviar como JSON
              });
          
              const data = await response.json();
              console.log(data);
              return data;
            } catch (error) {
              console.error('Error updating course:', error);
              throw error;
            }
          },
          async inscripcion(token, formData) {
            console.log('formData', formData);
            try {
              const response = await fetch(`${this.url}/inscripcion`, {
                method: 'POST',
                headers: {
                  'Accept': 'application/json',
                  Authorization: `Bearer ${token}`,
                },
                credentials: 'include',
                body: formData,
              });
          
              const data = await response.json();
              console.log(data);
              return data;
            } catch (error) {
              console.error('Error enrolling in course:', error);
              throw error;
            }
          },
          //Examen
          async postExamen(token, payload, claseId, cursoId) {
            try {
              // Realiza la solicitud al backend
              const response = await fetch(`${this.url}/postExamen/${cursoId}`, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  Authorization: `Bearer ${token}`,
                },
                credentials: 'include',
                body: JSON.stringify(payload), 
              });
              const data = await response.json();
              console.log(data.calificacion)
              if(data.calificacion >= 7){
                const rawr = {
                  claseId: claseId,
                  nota: data.calificacion,
                  caso: 2
                };
                const response2 = await fetch(`${this.url}/editarTransaccion`, {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    Authorization: `Bearer ${token}`,
                  },
                  credentials: 'include',
                  body: JSON.stringify(rawr),
                });
                const data2 = await response2.json();
                if(data2.success == true){
                  return { success: true, calificacion: data.calificacion }
                }else{
                  console.log(data);
                }
              }else{
                return { success: false, calificacion: data.calificacion }
              }
          
            } catch (error) {
              console.error('Error enviando el examen:', error);
              throw error;
            }
          }          
          
    },
    
})