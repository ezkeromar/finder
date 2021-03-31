
import * as TYPES from './types'


export default {
  [TYPES.MAIN_SET_FETCHING](state, obj) {
    state.fetching = obj.fetching
  },
  [TYPES.MAIN_SET_MESSAGE](state, obj) {
    state.messages[obj.type] = obj.message
  },
}
