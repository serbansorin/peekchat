import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import _ from './helpers';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

// attach the app to the window object
window._ = _;

console.log(_.isNull(1));

// const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const appName = "Peekchat";


createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({
            render: () => h(App, props)
          });
          app.config.globalProperties._ = _; // Make _ a global variable

        return app
            .use(plugin)
            .use(ElementPlus)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
