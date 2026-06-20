import Swal from "sweetalert2";
import "../../scss/plugin/sweetalert.scss";

window.Swal = Swal.mixin({
    target: '#swal-container',
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


document.addEventListener('livewire:navigating', () => {
    // If a user clicks a link while a SweetAlert is processing/open,
    // cleanly destroy it so the overlay doesn't carry over to the new page.
    if (window.Swal && window.Swal.isVisible()) {
        window.Swal.close();
    }
});
