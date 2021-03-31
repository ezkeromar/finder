import * as TYPES from './mutations-types'

export default {
  [TYPES.SET_CURRENTINDEX] (state, value) {
    state.currentIndex = value
  },
  [TYPES.SET_CURRENTSEARCH] (state, value) {
    state.currentSearch = value
  }
}
