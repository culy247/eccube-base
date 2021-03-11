import Vue from 'vue'
import VueRouter from 'vue-router'
import ProductDetail from './components/ProductDetail'

Vue.use(VueRouter)

const EmptyComponent = '';

const routes = [
  { path: '/products/detail/:id', component: ProductDetail },
  { path: '/', component: ProductDetail },
]

const router = new VueRouter({
  mode: 'history',
  routes // short for `routes: routes`
})

export default router;
