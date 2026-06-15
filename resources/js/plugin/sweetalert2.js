import Swal from "sweetalert2";
import "../../scss/plugin/sweetalert.scss";

window.Swal = Swal.mixin({
    target: document.getElementById('swal-container') || 'body',
})

window.toast = function (text, type = 'success') {
    window.Swal.fire({
        icon: type,
        text: text,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    })
}
