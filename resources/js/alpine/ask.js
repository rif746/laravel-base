import "../plugin/sweetalert2.js";

export default function alpineAsk(Alpine) {
    Alpine.magic('ask', () => {
        return {
            livewire: (eventName, { id, title, textMessage, confirmText, cancelText, successMessage, onSuccess = () => { } }) => {
                return window.Swal.fire({
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
                            const failureEvent = eventName + '-failed'; // 👈 Optional error listener hook

                            // 1. Success Event Handler Loop
                            const successListener = Livewire.on(completeEvent, () => {
                                try {
                                    onSuccess();
                                    cleanup();
                                    resolve(true); // Tells SweetAlert to close successfully
                                } catch (e) {
                                    cleanup();
                                    reject(new Error(`Success callback failed: ${e.message}`));
                                }
                            });

                            // 2. Failure Event Handler Loop (If your backend dispatches a failure state explicitly)
                            const failureListener = Livewire.on(failureEvent, (eventPayload) => {
                                cleanup();
                                // Extract custom error messages dispatched straight from your backend model actions
                                const message = eventPayload?.message || eventPayload?.[0]?.message || 'Backend execution failure';
                                Swal.showValidationMessage(message);

                                resolve(false); // 💡 Returning false stops the loader and keeps the modal open for a retry!
                            });

                            // 3. Centralized event unbinding utility
                            function cleanup() {
                                if (typeof successListener === 'function') successListener();
                                if (typeof failureListener === 'function') failureListener();
                            }

                            // 4. Emergency Request Timeout Safety Boundary
                            const timeoutId = setTimeout(() => {
                                cleanup();
                                Swal.showValidationMessage('Server timeout while waiting for completion event.');
                                resolve(false); // Stops the loader and allows them to try clicking confirm again
                            }, 10000);

                            // Override cleanup to clear the running timeout instance if things complete on time
                            const originalCleanup = cleanup;
                            cleanup = () => {
                                clearTimeout(timeoutId);
                                originalCleanup();
                            };

                            // 5. Fire off the request payload execution hook directly to Livewire
                            try {
                                Livewire.dispatch(eventName, {id: id});
                            } catch (dispatchError) {
                                cleanup();
                                reject(dispatchError);
                            }
                        })
                    }
                }).then(result => {
                    if (result.isConfirmed && successMessage) {
                        toast(successMessage, 'success');
                    }
                });
            },
            ajax: ({ title, textMessage, confirmText, cancelText, successMessage, onSuccess = () => { } }) => {
                return window.Swal.fire({
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
                            return true;
                        } catch (error) {
                            console.error(error);
                            const errorMsg = error?.message || 'Execution processing failed.';
                            Swal.showValidationMessage(errorMsg);
                            return false;
                        }
                    }
                }).then(result => {
                    if (result.isConfirmed && successMessage) {
                        toast(successMessage, 'success');
                    }
                });
            }
        }
    })
}
