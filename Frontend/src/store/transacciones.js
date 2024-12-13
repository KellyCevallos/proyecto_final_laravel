import { defineStore } from 'pinia';

export const Transacciones = defineStore('transaccionStore', {
  state: () => ({
    transaccion : [],
    url: import.meta.env.VITE_API_URL,
  }),
  actions: {
    //todas las transacciones no pagadas
    async getTransaccion(token) {
      try {
        const caso = 1;
        const response = await fetch(`${this.url}/transaccion/${caso}`, {
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
        this.transaccion = data.data.data;
        } catch (error) {
            console.error('Error fetching transaction:', error);
        }
    },
    //Todos los cursos pagados
    async getCursosPagados(token){
      try {
        const caso = 5;
        const response = await fetch(`${this.url}/transaccion/${caso}`, {
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
        this.transaccion = data.data.data;
        } catch (error) {
            console.error('Error fetching transaction:', error);
            }
    
    },
    //Cursos pagados no finalizados de un solo usuario
    async getTransaccionUserEnCurso(token, id){
      try {
        const caso = 2;
        const response = await fetch(`${this.url}/transaccion/${caso}/${id}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            Authorization: `Bearer ${token}`,
          },
          credentials: 'include',
        });
        const data = await response.json();
        this.transaccion = data.data.data;
        } catch (error) {
            console.error('Error fetching transaction:', error);
        }
    },
    //Cursos pagados finalizados de un solo usuario
    async getTransaccionUserFinalizado(token, id){
      try {
        const caso = 3;
        const response = await fetch(`${this.url}/transaccion/${caso}/${id}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            Authorization: `Bearer ${token}`,
          },
          credentials: 'include',
        });
        const data = await response.json();
        this.transaccion = data.data.data;
        } catch (error) {
            console.error('Error fetching transaction:', error);
            }
    },
    //Cursos de un usuario que no han sido pagados
    async getTransaccionUserNoPagado(token, id){
      try {
        const caso = 4;
        const response = await fetch(`${this.url}/transaccion/${caso}/${id}`, {
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
        this.transaccion = data.data.data;
        } catch (error) {
            console.error('Error fetching transaction:', error);
            }
    },
    //delete de transaccion
    async deleteTransaccion(token, id){
      try {
        const response = await fetch(`${this.url}/deleteClase/${id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            Authorization: `Bearer ${token}`,
          },
          credentials: 'include',
        });
        const data = await response.json();
        console.log(data);
        return data;
        } catch (error) {
            console.error('Error fetching transaction:', error);
            }
    },
    //post de finalizar transaccion
    async finalizarTransaccion(token, dataPago) {
      const formData = new FormData();
      formData.append('pagoMonto', dataPago.pagoMonto);
      formData.append('cursoId', dataPago.cursoId);
      formData.append('pagoType_pagoTypeId', dataPago.pagoType_pagoTypeId);
      formData.append('pagoComprobante', dataPago.pagoComprobante);
    
      try {
        const response = await fetch(`${this.url}/finalizarTransaccion`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`, // No incluir Content-Type manualmente
          },
          credentials: 'include',
          body: formData,
        });
    
        const data = await response.json();
        if (data.success) {
          const DataPago2 = {
            claseId: dataPago.cursoId,
            pagoId: data.pago.pagoId,
            caso: 1,
          };
    
          const response2 = await fetch(`${this.url}/editarTransaccion`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              Authorization: `Bearer ${token}`,
            },
            credentials: 'include',
            body: JSON.stringify(DataPago2),
          });
    
          const data2 = await response2.json();
          return data2;
        }
      } catch (error) {
        console.error('Error fetching transaction:', error);
      }
    }
    
  },
});
