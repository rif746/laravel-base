/*eslint no-unused-vars: 0 */
/*global Livewire */
import "./bootstrap"
import Swal from "sweetalert2"

function notify ({ icon, title, message }) {
	Swal.fire({
		icon,
		title,
		text: message,
		showConfirmButton: false,
		customClass: {
			popup: "dark:bg-gray-800 bg-gray-300 text-gray-800 dark:text-gray-300"
		}
	})
}

document.addEventListener("livewire:init", () => {
	Livewire.hook("request", ({ fail }) => {
		fail(({ status, preventDefault }) => {
			switch (status) {
			case 419:
				notify({
					icon: "warning",
					title: "419 Expired",
					message: "Invalid or Expired Token"
				})
				break

			case 403:
				notify({
					icon: "warning",
					title: "403 Unauthorized",
					message: "You are not authorized to do this action"
				})
				break

			case 500:
				notify({
					icon: "error",
					title: "500 System Error",
					message: "Your action causing error, please contact developer."
				})
				break

			case 404:
				notify({
					icon: "warning",
					title: "404 Not Found",
					message: "Action not found"
				})
				break

			default:
				break
			}
			preventDefault()
		})
	})
})
