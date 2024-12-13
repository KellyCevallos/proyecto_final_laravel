<template>
  <footer class="footer text-center">
    <p>ULEAM © Copyright 2020, Todos los derechos reservados - Universidad Laica Eloy Alfaro de Manabí</p>
    <p>Dirección: Av. Circunvalación - Vía a San Mateo</p>
    <p>Manta - Manabí - Ecuador</p>
  </footer>
</template>

<style scoped>
.footer {
  width: 100%;
  padding: 20px 0;
  color: #f0f0f0; /* Gris claro */
  font-family: 'Montserrat', sans-serif;

  /* Degradado del footer */
  background-image: linear-gradient(90deg, #0f3d28, #0d0f0c);
  background-size: cover;
}

.footer p {
  margin: 5px 0;
  font-size: 0.9rem;
  color: #f0f0f0; /* Texto en gris claro */
  letter-spacing: 0.5px;
}

/* Estilo para enlaces si los necesitas en el futuro */
.footer a {
  color: #3ecf8e; /* Verde claro */
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer a:hover {
  color: #ffffff; /* Blanco en hover */
}

/* Responsividad */
@media (max-width: 768px) {
  .footer {
    padding: 15px 0;
  }

  .footer p {
    font-size: 0.8rem;
  }
}
</style>
