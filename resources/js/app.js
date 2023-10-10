/*eslint no-unused-vars: 0 */
/*global Livewire process */
import "./bootstrap"
import Swal from "sweetalert2"

window.Swal = Swal

window.notify = function({ icon, title, message }) {
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

if(import.meta.env.VITE_DEBUG) {
	document.addEventListener("livewire:init", () => {
		Livewire.hook("request", ({ fail }) => {
			fail(({ status, preventDefault }) => {
				switch (status) {
				case 419:
					window.notify({
						icon: "warning",
						title: "419 Expired",
						message: "Invalid or Expired Token"
					})
					break

				case 403:
					window.notify({
						icon: "warning",
						title: "403 Unauthorized",
						message: "You are not authorized to do this action"
					})
					break

				case 500:
					window.notify({
						icon: "error",
						title: "500 System Error",
						message: "Your action causing error, please contact developer."
					})
					break

				case 404:
					window.notify({
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
}
