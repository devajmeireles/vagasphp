import './bootstrap';
import './modules/tooltip';
import './modules/sweetalert';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask'
import intersect from '@alpinejs/intersect'

Alpine.plugin(intersect)
Alpine.plugin(mask)

window.Alpine = Alpine;

Alpine.start();
