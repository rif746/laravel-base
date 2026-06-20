export default function alpineAsk(Alpine) {
    Alpine.magic('ask', () => {
        return {
            livewire: (eventName, { id, title, textMessage, confirmText, cancelText, successMessage, onSuccess = () => { } }) => {
                return window.Swal.fire({
                    title,
                    text: textMessage,
                    icon: 'warning',
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        return new Promise((resolve) => {
                            const completeEvent = eventName + '-completed';
                            const failureEvent = eventName + '-failed';

                            let unsubscribeSuccess;
                            let unsubscribeFailure;

                            // Centralized event unbinding utility
                            function cleanup() {
                                if (typeof unsubscribeSuccess === 'function') unsubscribeSuccess();
                                if (typeof unsubscribeFailure === 'function') unsubscribeFailure();
                            }

                            // Emergency Request Timeout Safety Boundary
                            const timeoutId = setTimeout(() => {
                                cleanup();
                                window.Swal.showValidationMessage('Server timeout while waiting for completion event.');
                                resolve(false); // Stops loader, keeps modal open
                            }, 10000);

                            // 1. Success Event Handler Loop
                            unsubscribeSuccess = window.Livewire.on(completeEvent, () => {
                                clearTimeout(timeoutId);
                                cleanup();
                                try {
                                    onSuccess();
                                    resolve(true); // Closes SweetAlert
                                } catch (e) {
                                    window.Swal.showValidationMessage(`Success callback error: ${e.message}`);
                                    resolve(false);
                                }
                            });

                            // 2. Failure Event Handler Loop
                            unsubscribeFailure = window.Livewire.on(failureEvent, (eventPayload) => {
                                clearTimeout(timeoutId);
                                cleanup();

                                // Livewire v3 passes event data wrapped in an object or array
                                const payloadData = eventPayload?.detail || eventPayload;
                                const message = payloadData?.message || (Array.isArray(payloadData) ? payloadData[0]?.message : null) || 'Backend execution failure';

                                window.Swal.showValidationMessage(message);
                                resolve(false); // Stops loader, lets user retry
                            });

                            // 3. Fire off the request payload execution hook directly to Livewire
                            try {
                                window.Livewire.dispatch(eventName, { id: id });
                            } catch (dispatchError) {
                                clearTimeout(timeoutId);
                                cleanup();
                                window.Swal.showValidationMessage(`Dispatch failed: ${dispatchError.message}`);
                                resolve(false);
                            }
                        });
                    }
                }).then(result => {
                    if (result && result.isConfirmed && successMessage) {
                        // Safe check for global toast function
                        if (typeof toast === 'function') {
                            toast(successMessage, 'success');
                        } else if (window.toast === 'function') {
                            window.toast(successMessage, 'success');
                        }
                    }
                });
            },
            ajax: ({ title, textMessage, confirmText, cancelText, successMessage, onSuccess = () => { } }) => {
                return window.Swal.fire({
                    title,
                    text: textMessage,
                    icon: 'warning',
                    showConfirmButton: true,
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
                            window.Swal.showValidationMessage(errorMsg);
                            return false;
                        }
                    }
                }).then(result => {
                    if (result && result.isConfirmed && successMessage) {
                        if (typeof toast === 'function') {
                            toast(successMessage, 'success');
                        }
                    }
                });
            }
        }
    })
}
