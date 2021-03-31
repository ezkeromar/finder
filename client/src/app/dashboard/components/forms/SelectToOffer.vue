<script>
	import { loadMissions, addUserToMission } from '../../services.js'
	import { mapGetters } from 'vuex'
	import { bus } from '../../../../plugins/eventbus'
	import moment from 'moment'
	import Datepicker from 'vuejs-datepicker';
	export default {
		props: ['consultent'],
		data() {
			return {
				setectionType:0,
				missions:null,
				selected:null,
				mission_id:null,
				showSelectOffer:false
			}
		},
		mounted() {
			bus.$on('select.to.offer', ($event) => {
	            this.showSelectOffer = !this.showSelectOffer;
	        })
      		this.getMissions()
		},
		watch: {
      	},
      	computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
      	methods: {
      		getMissions() {
      			var pf = this;
      			if(this.currentClient) {
			        loadMissions(this.currentClient.id, this.currentUser.id).then(function(response) {
			            pf.missions = response.missions;
			        });
				}
      		},
      		onChangeMissions(obj) {
	        	this.mission_id = obj.value;
      		},
      		selectConsultant() {
      			var suo = this;
      			addUserToMission(this.consultent, this.mission_id, this.setectionType).then(function(response) {
      				$('body').removeClass('no-scroll');
      			suo.showSelectOffer = !suo.showSelectOffer;
      			bus.$emit('user.added.to.mission');
      			})
      		},
      		close() {
      			$('body').removeClass('no-scroll');
      			this.showSelectOffer = !this.showSelectOffer;
      		}
      	},
      	components:{
      	}
	}
</script>

<template>
	<div v-show="showSelectOffer" class="popup active">
		<div class="popup-container popup-rdv active">
			<div class="popup-consultantajout-content">
				<span class="title small black">Choisissez votre mission</span>
				<div class="addmission-input">
					<v-select v-if="this.missions" v-model="selected" :on-change="onChangeMissions" :options="this.missions" :searchable="true" placeholder="Nom de la mission"></v-select>
				</div>
				<div class="radio-btns">
					<label>
						<input type="radio" v-model="setectionType" value="1" name="mission">
						<span>Shortlist</span>
					</label>
					<label>
						<input type="radio" v-model="setectionType" value="0" name="mission">
						<span>Séléction</span>
					</label>
				</div>
			</div>
			<div class="popup-btns"> 
				<a @click="selectConsultant" class="envoye-demande-btn cursor">Selectionner Consultant</a>
			</div>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
	</div>
</template>