<script>
	import { loadMissions, meetRequest } from '../../../services.js'
	import { mapGetters } from 'vuex'
	import { bus } from '../../../../../plugins/eventbus'
	import moment from 'moment'
	import Datepicker from 'vuejs-datepicker';
	export default {
		props: ['experience'],
		data() {
			return {
				showExperience:false,
				experience_selected: null,
				selected:null,
				experience_id:'',
				title:'',
				date_start:'',
				date_end:'',
				client:'',
			}
		},
      	mounted() {
      		bus.$on('edit.experience', ($experience_sent) => {
	            this.showExperience = !this.showExperience;
	            this.experience_selected = $experience_sent;
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
      			bus.$emit('edit.experience');
      			this.$router.push({name : 'dashboard.profile.edit'});
      		},
      		editExperience() {
      			var mt = this;
      			mt.close();
      			
      			//this.client = this.title = this.date_start = this.date_end = '';      			
      		}
      	},
	    components:{
	         'datepicker':Datepicker
	    },
    }
</script>

<template>
<div v-show="showExperience" class="popup active">

	<div class="popup-container popup-addCV active" >
				<div class="popup-content">
					<span class="title black">Expérience</span>
					<label class="full-label">
						<input type="text" v-model="experience.title" placeholder="intitulé du poste" />
					</label>
					<label class="full-label">
						<input type="text" v-model="experience.client" placeholder="Entreprise" />
					</label>
					<label class="half-label">
						<!--<input placeholder="Date de départ" type="text"/>-->
						<datepicker v-model="experience.date_start" placeholder="Date de départ"></datepicker>
					</label>
					<label class="half-label">
						<!--<input placeholder="Date de fin" type="text"/>-->
						<datepicker v-model="experience.date_end" placeholder="Date de fin"></datepicker>
					</label>					
				</div>	
				<a @click="editExperience" class="envoye-demande-btn orange cursor">Sauvegarder la modification</a>
				<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
			</div>
</div>
</template>