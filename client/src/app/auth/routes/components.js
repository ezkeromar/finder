export const Main = r => require.ensure([], () => r(require('../components/main')), 'auth-bundle')

export const Signin = r => require.ensure([], () => r(require('../components/forms/signin')), 'auth-bundle')

export const linkedlogin = r => require.ensure([], () => r(require('../components/forms/linkedlogin')), 'auth-bundle')

export const Signup = r => require.ensure([], () => r(require('../components/forms/signup')), 'auth-bundle')

export const Resetpass = r => require.ensure([], () => r(require('../components/forms/resetpass')), 'auth-bundle')

export const ValidateConsultant = r => require.ensure([], () => r(require('../components/forms/ValidateConsultant')), 'auth-bundle')
