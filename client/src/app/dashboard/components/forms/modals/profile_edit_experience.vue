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
				selected:null,
				experience_id:'',
				title:'',
				date_start:'',
				date_end:'',
				client:'',
			}
		},
      	mounted() {
      		console.log(this.experience);
      		bus.$on('add.experience', ($event) => {
	            this.showExperience = !this.showExperience;
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
      			bus.$emit('add.experience');
      		},
      		addExperience() {
      			var mt = this;
      			mt.close();
      			this.experience.push({
      				title: this.title, 
      				date_start: moment(this.date_start).format('DD/MM/YYYY'), 
      				date_end: moment(this.date_end).format('DD/MM/YYYY'),
      				client: this.client
      			});
      			this.client = this.title = this.date_start = this.date_end = '';
      			/*meetRequest(this.currentClient.id, this.consultent, this.mission_id, moment(this.date_start).format('DD/MM/YYYY'), moment(this.date_end).format('DD/MM/YYYY'), this.hour_start, this.hour_end, this.min_start, this.min_end).then(function(response) {
      				mt.close();
      				swal("Votre demande est sous le traitement");
      			})*/
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
						<!--<input placeholder="Intitulé de poste" type="text"/>-->
						<input type="text" v-model="title" placeholder="intitulé du poste" />
					</label>
					<label class="full-label">
						<input type="text" v-model="client" placeholder="Entreprise" />
					</label>
					<label class="half-label">
						<!--<input placeholder="Date de départ" type="text"/>-->
						<datepicker v-model="date_start" placeholder="Date de départ"></datepicker>
					</label>
					<label class="half-label">
						<!--<input placeholder="Date de fin" type="text"/>-->
						<datepicker v-model="date_end" placeholder="Date de fin"></datepicker>
					</label>
					<label class="half-label">
						<select>
							<option value="Année" disabled>Année</option>
							<option value="2000">2000</option>
							<option value="2001">2001</option>
							<option value="2002">2002</option>
						</select>
					</label>
				</div>	
				<a @click="addExperience" class="envoye-demande-btn orange cursor">Ajoutez au CV</a>
				<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
			</div>
</div>
</template>