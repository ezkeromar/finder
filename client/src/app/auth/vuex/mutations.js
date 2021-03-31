import * as TYPES from './mutations-types'

export default {
  [TYPES.SET_TOKEN] (state, value) {
    state.token = value
  },
  [TYPES.SET_USER] (state, value) {
    state.user = value
  },
  [TYPES.SET_CLIENT] (state, value) {
    state.client = value
  },
  [TYPES.SET_CURRENTSIGNIN] (state, value) {
    state.currentSignin = value
  },
  [TYPES.SET_CURRENTSIGNUP] (state, value) {
    state.currentSignup = value
  }
}
