export default function removeData(Alpine) {
    Alpine.directive('remove-data', (el, { expression }, { evaluateLater, cleanup }) => {
        let evaluate = evaluateLater(expression)

        let handler = (e) => {
            e.preventDefault()
            e.stopPropagation()

            let title = el.dataset.title
            let text = el.dataset.text
            let confirmButtonText = el.dataset.confirmText
            let cancelButtonText = el.dataset.cancelText

            if (Swal) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmButtonText,
                    cancelButtonText: cancelButtonText,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        return new Promise((resolve, reject) => {
                            try {
                                evaluate()
                            } catch (error) {

                                reject(error)
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Something went wrong',
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonText: 'OK'
                                })
                            }
                        })
                    }
                })
            }
        }

        el.addEventListener('click', handler)
        cleanup(() => {
            el.removeEventListener('click', handler)
        })
    })
}