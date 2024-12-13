import { defineStore } from 'pinia';

export const Pago = defineStore('pagoStore', {
  state: () => ({
    pagoType: [],
    url: import.meta.env.VITE_API_URL,
  }),
  actions: {
    //get pago types
    async getPagoType(token) {
      try {
        const response = await fetch(`${this.url}/getPagoType`, {
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
        this.pagoType = data;
        return data;
      } catch (error) {
        return error;
      }
    },
  },
});