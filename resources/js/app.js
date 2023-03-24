import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import {createStore} from "vuex";
import useAuthStore from "./stores/auth";


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const store = createStore({
    modules: {
        useAuthStore,
    },
    state: {
    },
    mutations: {}
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(store)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
