import '../css/app.css';
import './bootstrap';
// import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
// import { createVuetify } from 'vuetify';
// import * as components from 'vuetify/components';
// import * as directives from 'vuetify/directives';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            // .use(createVuetify({
            //     theme: {
            //         defaultTheme: "light",
            //         themes: {
            //             light: {
            //                 colors: {
            //                     primary: "#72A161",
            //                     secondary: "#9EC350",
            //                     tertiary: "#F3FFDA",
            //                     white: "#F9F9F9",
            //                     yellow: "#FFD700",
            //                 },
            //             },
            //         },
            //     },
            //     icons: {
            //         defaultSet: 'mdi',
            //     },
            //     components,
            //     directives,
            // }))
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
