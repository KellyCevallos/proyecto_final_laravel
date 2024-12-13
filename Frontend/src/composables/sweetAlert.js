import Swal from 'sweetalert2';
import { ref } from 'vue';

export function sweetalert() {
  const alert = ref(null);

  const ShowLoading = () => {
    const loadingAlert = Swal.fire({
      title: 'Verificando',
      html: '<div class="spinner-border text-primary mt-2 mb-2"></div>',
      showConfirmButton: false,
      allowOutsideClick: false,
    });

    const CloseLoading = () => {
      Swal.close();
    };

    return CloseLoading;
  };

  const successAlert = (title, text) => {
    Swal.fire({
      title: title,
      text: text,
      icon: 'success',
      confirmButtonText: 'OK'
    });
  };

  const errorAlert = (title, text, isHtml = false) => {
    Swal.fire({
      title: title,
      [isHtml ? 'html' : 'text']: text, // Cambia entre `html` y `text` según el parámetro
      icon: 'error',
      confirmButtonText: 'OK'
    });
  };

  const confirmAlert = async (title, text) => {
    return Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then((result) => {
      return result.isConfirmed;
    });
  };

  const showAlert = (title, text) => {
    Swal.fire({
      title: title,
      text: text,
      confirmButtonText: 'OK'
    });
  };
  const warningAlert = (title, text) => {
    Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      confirmButtonText: 'OK'
    });
  };

  return { alert, successAlert, errorAlert, confirmAlert, ShowLoading, showAlert, warningAlert };
}
