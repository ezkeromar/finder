import { routes as auth } from './auth'
import { default as dashboard } from './dashboard/routes'
import { default as home } from './frontpage/routes'

export default [...auth, ...dashboard, ...home]
