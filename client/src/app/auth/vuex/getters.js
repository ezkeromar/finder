
import { isEmpty } from 'lodash'

export const isLogged = ({ token }) => !isEmpty(token)

export const currentUser = ({ user }) => user

export const currentClient = ({ client }) => client

export const currentSignin = ({ currentSignin }) => currentSignin

export const currentSignup = ({ currentSignup }) => currentSignup

export const currentToken = ({ currentSignup }) => currentSignup
