import localforage from 'localforage'
import { userTokenStorageKey } from '../../../config'
import { setToken as httpSetToken } from '../../../plugins/http'
import * as TYPES from './mutations-types'

const subscribe = (store) => {
  store.subscribe((mutation, { Auth }) => {
    if (TYPES.SET_TOKEN === mutation.type) {
      httpSetToken(Auth.token)

      localforage.setItem(userTokenStorageKey, Auth.token)
    }
  })
}

export default (store) => {
  subscribe(store)
};
