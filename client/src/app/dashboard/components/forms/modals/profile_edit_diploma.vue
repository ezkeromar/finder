<script>
	import { loadMissions, meetRequest } from '../../../services.js'
	import { mapGetters } from 'vuex'
	import { bus } from '../../../../../plugins/eventbus'
	import moment from 'moment'
	import Datepicker from 'vuejs-datepicker';
	export default {
		props: ['diploma', 'diplomas'],
		data() {
			return {
				showDiploma: false,
				selected:null,
				diploma_id:false,
				speciality: '',
				school: '',
				year: '',
				years: {},
			}
		},
      	mounted() {
      		bus.$on('add.diploma', ($event) => {
	            this.showDiploma = !this.showDiploma;
	            $('<div class="number-button number-up">+</div><div class="number-button number-down">-</div>').insertAfter('.number input');			    
	        })
	        /*for (var i = 2000; i < 2010; i++) {
      			this.years.push({i});
      		}*/

      	},
      	watch: {
      	},
      	computed: {
	      ...mapGetters(['currentUser']),
	    },
      	methods: {
      		close() {
      			bus.$emit('add.diploma');
      		},
      		addDiploma() {
      			var mt = this;
      			mt.close();
      			this.diploma.push({
      				speciality: this.speciality, 
      				school: this.school, 
      				diploma_id: this.diploma_id, 
      				year: this.year, 
      				client: this.client
      			});
      			this.speciality = this.school = this.year = this.diploma_id = '';
      		}
      	},
	    components:{
	         'datepicker':Datepicker
	    },
    }
</script>

<template>
	<div v-show="showDiploma" class="popup active">
		<div class="popup-container popup-addCV active" >
			<div class="popup-content">
				<span class="title black">Formation</span>
				<div class="full-label">
					<v-select v-if="diplomas" v-model="diploma_id" :options="diplomas" :searchable="true" placeholder="Formation"></v-select>
				</div>
				<label class="full-label">
					<input type="text" v-model="speciality" placeholder="Spécialité" />
				</label>
				<label class="full-label">
					<input type="text" v-model="school" placeholder="Ecole" />
				</label>
				<label class="half-label">
					<input type="number" placeholder="Année" v-model="year" min="1960" max="2018" name="">
				</label>
			</div>	
			<a @click="addDiploma" class="envoye-demande-btn orange cursor">Ajoutez Formation</a>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
	</div>
</template>