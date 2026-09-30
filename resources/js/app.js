import './firebase';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect'


window.Alpine = Alpine;

Alpine.plugin(intersect)
Alpine.start();


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
