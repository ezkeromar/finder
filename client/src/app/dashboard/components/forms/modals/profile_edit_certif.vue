<script>
	import { mapGetters } from 'vuex'
	import { bus } from '../../../../../plugins/eventbus'
	import moment from 'moment'
	import Datepicker from 'vuejs-datepicker';
	export default {
		props: ['certification'],
		data() {
			return {
				showCertif: false,
				title: '',
				certif: '',
				year: '',
				years: {},
			}
		},
      	mounted() {
      		bus.$on('add.certif', ($event) => {
	            this.showCertif = !this.showCertif;
	            $('<div class="number-button number-up">+</div><div class="number-button number-down">-</div>').insertAfter('.number input');			    
	        })

      	},
      	watch: {
      	},
      	computed: {
	      ...mapGetters(['currentUser']),
	    },
      	methods: {
      		close() {
      			bus.$emit('add.certif');
      		},
      		addCertif() {
      			var mt = this;
      			mt.close();
      			this.certification.push({
      				title: this.title, 
      				certif: this.certif, 
      				year: this.year,
      			});
      			this.title = this.certif = '';
      		}
      	},
	    components:{
	         'datepicker':Datepicker
	    },
    }
</script>

<template>
	<div v-show="showCertif" class="popup active">
		<div class="popup-container popup-addCV active" >
			<div class="popup-content">
				<span class="title black">Certification</span>
				<label class="full-label">
					<input type="text" v-model="title" placeholder="Titre" />
				</label>
				<label class="full-label">
					<input type="text" v-model="certif" placeholder="Description" />
				</label>
						
				<label class="half-label">
					<input type="number" placeholder="Année" v-model="year" min="1960" max="2018" name="">
				</label>
			</div>	
			<a @click="addCertif" class="envoye-demande-btn orange cursor">Ajoutez Certification</a>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
	</div>
</template>