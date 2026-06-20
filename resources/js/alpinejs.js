// resources/js/app.js
import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
import bs from './alpine/bs';
import alpineAxios from './alpine/axios';
import alpineSelect2 from "./alpine/select2.js";
import alpineQuill from "./alpine/quill.js";
import alpineAsk from "./alpine/ask.js";
import AlpineChart from "./alpine/chart.js";

// 1. Expose both instances globally so Blade directives can see them
window.Alpine = Alpine;
window.Livewire = Livewire;

// 2. Wrap plugin registration inside the initialization event listener
Alpine.plugin(bs);
Alpine.plugin(alpineAxios);
Alpine.plugin(alpineAsk);
Alpine.plugin(alpineSelect2);
Alpine.plugin(alpineQuill);
Alpine.plugin(AlpineChart);

// 3. Boot Livewire
Livewire.start();
