import * as bootstrap from 'bootstrap';
import Sidebar from "./sidebar.js";

window.addEventListener('livewire:navigated', Sidebar)

window.bootstrap = bootstrap
