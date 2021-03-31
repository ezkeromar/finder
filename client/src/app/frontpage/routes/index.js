import { Main, Index, Search } from './components'

const children = [{
  name: 'home.index',
  path: '',
  component: Index,
  meta: { requiresAuth: false },
},{
  name: 'home.search',
  path: '/search',
  component: Search,
  meta: { requiresAuth: false },
}]

export default [{
  children,
  name: 'home',
  path: '/',
  component: Main,
  redirect: { name: 'home.index' },
  meta: { requiresAuth: false },
}]
