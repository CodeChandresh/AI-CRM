import { createApp } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'ziggy-js';
import { createHead } from '@inertiajs/inertia-vue3';
import { Head } from '@inertiajs/inertia-vue3';
import AppLayout from './Shared/Layouts/AppLayout.vue';
import { Ziggy } from 'ziggy-js';
import { createToast } from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const app = createApp({
  components: {
    AppLayout,
    Head,
  },
});

app.use(createInertiaApp({
  resolve: (name) => resolvePageComponent(name),
  render: ({ Component, props }) => {
    return (
      <div>
        <Component {...props} />
      </div>
    );
  },
  link: (href, inRoute) => {
    const route = Ziggy.route(href, inRoute);
    return route;
  },
}));

app.use(createHead());

app.use(createToast);

app.mount('#app');