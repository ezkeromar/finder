import { http } from '../../plugins/http'
import { getData } from '../../utils/get'

export const postLogin = ({ email, password }) =>
  http.post('/login', { email, password })
 .then(getData)

export const postLoginT = ({ token }) =>
  http.post('/login-token', { token })
 .then(getData)

export const postRegister = ({ email, password, firstname, lastname, brand, phone, is_client }) =>
	http.post('/register', { email, password, firstname, lastname, brand, phone, is_client })
	.then(getData)

export const loadUserData = () => http.get('/profile').then(getData)

export const revokeToken = () => http.post('/logout').then(getData)

export const ressetPass = (email) => http.post('/reset-pass', {email}).then(getData)

export const updatePass = (password, token) => http.post('/update-pass', {password, token}).then(getData)

export const ValidateConsultant = (token) => http.post('/aprouve-consultant', {token}).then(getData)