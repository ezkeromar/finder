

import Vue from 'vue'

export const bus = new Vue()

export default function install(Vue) {
	Object.defineProperty(Vue.prototype, '$bus', {
	    get() {
	      return bus
	    },
	})
}
