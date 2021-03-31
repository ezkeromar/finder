import localforage from 'localforage'
import { userTokenStorageKey } from '../../../config'
import { isEmpty } from 'lodash'
import * as TYPES from './mutations-types'
import * as services from '../services'
import { bus } from '../../../plugins/eventbus'
import route from '../routes'

export const navigateTab = function({ dispatch }, routeName) {
  if( routeName == 'auth.signup' ){
    dispatch('setCurrentSignup', true)
    dispatch('setCurrentSignin', false)
  }

  if( routeName == 'auth.forgotpass' || routeName == 'auth.signin' ){
    dispatch('setCurrentSignup', false)
    dispatch('setCurrentSignin', true)
  }
}

export const attemptLogin = ({ dispatch }, payload) =>
    services.postLogin(payload)
    .then(({ token, data }) => {
      dispatch('setUser', data.user)
      dispatch('setToken', token)
      if(data.client)
        dispatch('setClient', data.client)

      return data.user
    })

export const tokenLogin = ({ dispatch }, payload) =>
    services.postLoginT(payload)
    .then(({ token, data }) => {
      dispatch('setUser', data.user)
      dispatch('setToken', token)

      return data.user
    })

export const register = ({ dispatch }, payload) =>
    services.postRegister(payload)
    .then(({ token, data }) => {
      if(data.user) {
        dispatch('setUser', data.user)
        dispatch('setToken', token)
        bus.$emit("user.created")
      } else {
        bus.$emit("client.created")
      }
    })

export const logout = ({ dispatch }) => {
  services.revokeToken()
  return Promise.all([
    dispatch('setToken', null),
    dispatch('setUser', null), 
    dispatch('setClient', null)
  ])
}

export const setCurrentSignup = ({ commit }, currentSignup) => {

  commit(TYPES.SET_CURRENTSIGNUP, currentSignup)

  Promise.resolve(currentSignup)
}

export const setCurrentSignin = ({ commit }, currentSignin) => {

  commit(TYPES.SET_CURRENTSIGNIN, currentSignin)

  Promise.resolve(currentSignin)
}

export const setUser = ({ commit }, user) => {
  commit(TYPES.SET_USER, user)

  Promise.resolve(user)
}

export const setClient = ({ commit }, client) => {
  commit(TYPES.SET_CLIENT, client)

  Promise.resolve(client)
}

export const setToken = ({ commit }, payload) => {
  const token = (isEmpty(payload)) ? null : payload.token || payload

  commit(TYPES.SET_TOKEN, token)

  return Promise.resolve(token)
}

export const checkUserToken = ({ dispatch, state }) => {
  if (!isEmpty(state.token)) {
    return Promise.resolve(state.token)
  }

  return localforage.getItem(userTokenStorageKey)
    .then((token) => {
      if (isEmpty(token)) {
        return Promise.reject('NO_TOKEN')
      }
      return dispatch('setToken', token)
    })
    .then(() => dispatch('loadUser'))
}

export const loadUser = ({ dispatch }) => services.loadUserData()
  .then(function(data){
    dispatch('setUser', data.data.user)
    dispatch('setClient', data.data.client)
  })
  .catch(() => {
    dispatch('setToken', '')
    return Promise.reject('FAIL_IN_LOAD_USER')
  })
