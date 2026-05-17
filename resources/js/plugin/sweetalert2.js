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

window.logout = function ({
    text = 'Are you sure to logout?',
    width = '400px',
    type = 'warning',
    confirmButtonText = 'Yes',
    cancelButtonText = 'No',
    onSuccess = () => { }
}) {
    Swal.fire({
        icon: type,
        text: text,
        width: width,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        preConfirm: () => {
            return new Promise(async (resolve, reject) => {
                try {
                    await onSuccess();
                    resolve();
                } catch (error) {
                    console.error(error)
                    reject('Error occured when trying to logout.')
                }
            });
        }
    })
}