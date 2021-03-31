
<script>
	import { loadMissions, meetRequest } from '../../services.js'
	import { mapGetters } from 'vuex'
	import { bus } from '../../../../plugins/eventbus'
	export default {
		props: ['cv', 'baseUrlattr'],
		data() {
			return {
				showUserCv:false,
				cvUrl:null,
				downloadUrl:null
			}
		},
		mounted() {
			bus.$on('see.cv.user', ($event) => {
				this.cvUrl='https://docs.google.com/viewer?embedded=true&url='+this.baseUrlattr+this.cv,
	            this.showUserCv = !this.showUserCv;
	            this.downloadUrl = this.baseUrlattr+this.cv;
	        })
		},
		computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
	    methods: {
	    	close() {
      			$('body').removeClass('no-scroll');
      			bus.$emit('see.cv.user');
      		},
	    }
	}
</script>

<template>
	<div v-if="showUserCv" class="popup active">
		<div class="popup-container popup-rdv active">
			<div class="popup-content">
				<a :href="downloadUrl">Vue Syntax Highlight</a>
				<iframe :src="cvUrl" frameborder='0' width="100%" height="600px"></iframe>
			</div>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
		<span @click="close" class="popup-click"></span>
	</div>
</template>