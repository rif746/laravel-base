import Swal from "sweetalert2";

export default function removeData(Alpine) {
    Alpine.magic('remove', () => {
        return {
            ajax: (url, { title, textMessage, confirmText, cancelText, successMessage, onSuccess = () => { } }) => {
                return Swal.fire({
                    title,
                    text: textMessage,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            await axios.delete(url);
                            onSuccess();
                            toast(successMessage, 'success');
                        } catch (error) {
                            toast(error.message, 'error');
                        }
                    }
                });
            },
            livewire: (eventName, { id, title, textMessage, confirmText, cancelText, successMessage, onSuccess = () => { } }) => {
                return Swal.fire({
                    title,
                    text: textMessage,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        return new Promise((resolve, reject) => {
                            const completeEvent = eventName + '-completed';
                            const cleanupListener = Livewire.on(completeEvent, () => {
                                onSuccess();
                                resolve();
                                cleanupListener();
                                setTimeout(() => {
                                    toast(successMessage, 'success');
                                }, 100);
                            });
                            setTimeout(() => {
                                cleanupListener();
                                reject(new Error('Server timeout while waiting for completion.'));
                            }, 10000);
                            Livewire.dispatch(eventName, { id: id });
                        }).catch((error) => {
                            Swal.showValidationMessage(`Request failed: ${error.message}`);
                        });
                    }
                });
            },
            session: ({ title, textMessage, confirmText, cancelText, successMessage, onSuccess = () => { } }) => {
                return Swal.fire({
                    title,
                    text: textMessage,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            await onSuccess();
                            toast(successMessage, 'success');
                        } catch (error) {
                            toast(error.message, 'error');
                        }
                    }
                });
            }
        }
    })
}