import Alpine from 'alpinejs';
import 'flowbite';
import { initFlowbite } from 'flowbite';
import htmx from 'htmx.org';
import './bootstrap';

window.flowbite = initFlowbite();
window.Alpine = Alpine;
window.htmx = htmx;


Alpine.start();
