import { Signup, Signin, Main, Resetpass, linkedlogin, ValidateConsultant } from './components'

const children = [{
  name: 'auth.signin',
  path: 'signin',
  component: Signin,
  meta: { requiresAuth: false },
},
{
  name: 'auth.forgotpass',
  path: 'forgotpassword/:token/:email',
  component: Resetpass,
  meta: { requiresAuth: false },
},{
  name: 'auth.validateconsultant',
  path: 'validateconsultant/:token/:email',
  component: ValidateConsultant,
  meta: { requiresAuth: false },
}, {
  name: 'auth.signup',
  path: 'signup',
  component: Signup,
  meta: { requiresAuth: false },
}, {
  name: 'auth.linkedin',
  path: 'linkedin/loged',
  component: linkedlogin,
  meta: { requiresAuth: false },
}]

export default [{
  children,
  name: 'auth',
  path: '/auth',
  component: Main,
  redirect: { name: 'auth.signin' },
  meta: { requiresAuth: false },
}]
