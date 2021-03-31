import { Search, Main, Index, Missions, Agenda, ProfileEdit, SingleOffer, Assistance} from './components'

const children = [{
  name: 'dashboard.index',
  path: 'index',
  component: Index,
  meta: { requiresAuth: true },
}, {
  name: 'dashboard.missions',
  path: 'missions',
  component: Missions,
  meta: { requiresAuth: true },
}, {
  name: 'dashboard.mission',
  path: 'mission',
  component: SingleOffer,
  meta: { requiresAuth: true },
}, {
  name: 'dashboard.profile.edit',
  path: 'profile/edit',
  component: ProfileEdit,
  meta: { requiresAuth: true },
}, {
  name: 'dashboard.agenda',
  path: 'agenda',
  component: Agenda,
  meta: { requiresAuth: true },
},
{
  name: 'dashboard.assistance',
  path: 'assistance',
  component: Assistance,
  meta: { requiresAuth: true },
}, {
  name: 'dashboard.search',
  path: 'search/',
  component: Search,
  meta: { requiresAuth: true },
}]

export default [{
  children,
  name: 'dashboard',
  path: '/dashboard',
  component: Main,
  redirect: { name: 'dashboard.index' },
  meta: { requiresAuth: true },
}]


