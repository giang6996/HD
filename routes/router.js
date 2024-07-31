import Home from '../components/home.js';
import Catalog from '../components/catalog.js';
import Login from '../components/login.js';
import Signup from '../components/signup.js';
import Cart from '../components/cart.js';

const routes = [
    { path: '/', component: Home },
    { path: '/catalog', component: Catalog },
    { path: '/login', component: Login },
    { path: '/signup', component: Signup },
    { path: '/cart', component: Cart },
];

const router = VueRouter.createRouter({ 
    history: VueRouter.createWebHashHistory(), 
    routes
   });

   router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user');
    if (to.matched.some(record => record.meta.requiresAuth) && !loggedIn) {
        next('/login');
    } else {
        next();
    }
});

export default router;