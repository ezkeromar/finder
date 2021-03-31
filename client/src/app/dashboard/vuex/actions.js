import localforage from 'localforage'
import { userTokenStorageKey } from '../../../config'
import { isEmpty } from 'lodash'
import * as TYPES from './mutations-types'
import * as services from '../services'
import { bus } from '../../../plugins/eventbus'
import route from '../routes'

export const navigateTab = function({ dispatch }, routeName) {
  if( routeName == 'dashboard.index' ){
    dispatch('setCurrentIndex', true)
    dispatch('setCurrentSearch', false)
  }

  if( routeName == 'dashboard.search' ){
    dispatch('setCurrentIndex', false)
    dispatch('setCurrentSearch', true)
  }
}

export const setCurrentIndex = ({ commit }, currentIndex) => {

  commit(TYPES.SET_CURRENTINDEX, currentIndex)

  Promise.resolve(currentIndex)
}

export const setCurrentSearch = ({ commit }, currentSearch) => {

  commit(TYPES.SET_CURRENTSEARCH, currentSearch)

  Promise.resolve(currentSearch)
}
