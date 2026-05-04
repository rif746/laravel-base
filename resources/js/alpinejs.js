import Alpine from 'alpinejs';
import bs from './alpine/bs';
import alpineAxios from './alpine/axios';
import removeData from './alpine/remove-data';

Alpine.plugin(bs);
Alpine.plugin(alpineAxios);
Alpine.plugin(removeData);

window.Alpine = Alpine;

Alpine.start();
