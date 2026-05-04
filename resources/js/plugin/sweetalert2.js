import Swal from "sweetalert2/dist/sweetalert2.js";
import "../../scss/plugin/sweetalert.scss";

window.Swal = Swal;

window.toast = function (text, type = 'success') {
    Swal.fire({
        icon: type,
        text: text,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    })
}

window.ask = function (title, text, type = 'success') {
    return Swal.fire({
        icon: type,
        title: title,
        text: text,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    })
}

window.logout = function () {
    Swal.fire({
        icon: 'warning',
        text: 'Apakah anda yakin ingin keluar?',
        width: '400px',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.post(route('logout'));
                window.location.reload();
            } catch (error) {
                console.log(error)
                toast('Terjadi kesalahan saat logout.', 'error')
            }
        }
    })
}