<script>
	import { loadMissions, meetRequest } from '../../services.js'
	import { mapGetters } from 'vuex'
	import { bus } from '../../../../plugins/eventbus'
	import moment from 'moment'
	import Datepicker from 'vuejs-datepicker';
	export default {
		props: ['baseUrlattr', 'consultent', 'mission'],
		data() {
			return {
				missions:null,
				showMeeting:false,
				selected:null,
				mission_id:'',
				date_start:'',
				hour_start:8,
				min_start:0,
				date_end:'',
				hour_end:8,
				min_end:0,
			}
		},
      	mounted() {
      		if(this.mission != null)
      			this.mission_id = this.mission;
      		bus.$on('fix.meeting', ($event) => {
	            this.showMeeting = !this.showMeeting;
	        })
      		this.getMissions()
      	},
      	watch: {
      	},
      	computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
      	methods: {
      		close() {
      			$('body').removeClass('no-scroll');
      			bus.$emit('fix.meeting');
      		},
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
      		sendRequest() {
      			if(moment().diff(moment(this.date_start)) <= 0 && moment().diff(moment(this.date_end)) <= 0) {
	      			var mt = this;
	      			meetRequest(this.currentClient.id, this.consultent, this.mission_id, moment(this.date_start).format('DD/MM/YYYY'), moment(this.date_end).format('DD/MM/YYYY'), this.hour_start, this.hour_end, this.min_start, this.min_end).then(function(response) {
	      				mt.close();
	      				swal("Votre demande est sous le traitement");
	      			})
	      		} else {
	      			swal("la date doit etre superieur a aujourd'huit")
	      		}
      		},
      		upFunc(element) {
      			switch(element) {
      				case 'hs':
      					if(this.hour_start < 20)
      						this.hour_start = this.hour_start + 1;
      					break;
      				case 'ms':
      					if(this.min_start < 59)
      						this.min_start = this.min_start + 1;
      					break;
      				case 'he':
      					if(this.hour_end < 20)
      						this.hour_end = this.hour_end + 1;
      					break;
      				case 'me':
      					if(this.min_end < 59)
      						this.min_end = this.min_end + 1;
      					break;
      			}
      		},
      		downFunc(element) {
      			switch(element) {
      				case 'hs':
      					if(this.hour_start > 8)
      						this.hour_start = this.hour_start - 1;
      					break;
      				case 'ms':
      					if(this.min_start > 0)
      						this.min_start = this.min_start - 1;
      					break;
      				case 'he':
      					if(this.hour_end > 8)
      						this.hour_end = this.hour_end - 1;
      					break;
      				case 'me':
	      				if(this.min_end > 0)
	      					this.min_end = this.min_end - 1;
      					break;
      			}
      		}
      	},
	    components:{
	         'datepicker':Datepicker
	    },
    }
</script>

<template>
<div v-show="showMeeting" class="popup active">
	<div class="popup-container popup-rdv active">
		<div class="popup-content">
			<span  v-if="mission == null" class="title small black">Choisissez votre mission</span>
			<div  v-if="mission == null" class="addmission-input">
				<v-select v-if="this.missions" v-model="selected" :on-change="onChangeMissions" :options="this.missions" :searchable="true" placeholder="Nom de la mission"></v-select>
			</div>					
			<div class="three-elements">
				<span class="title small black">Choisissez un créneau</span>
				<div class="element">
				    <div class="form-group ">
				       <div class="input-group">
				        <datepicker v-model="date_start" class="form-control"></datepicker>
				        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				       </div>
				    </div>
				</div>
				<div class="number element">
					<input type="number" min="8" max="20" step="1" v-model="hour_start">
					<div class="number-button number-up" @click="upFunc('hs')">+</div>
					<div class="number-button number-down" @click="downFunc('hs')">-</div>
				</div>
				<div class="number element">
				  	<input type="number" min="0" max="59" step="1" v-model="min_start">
					<div class="number-button number-up" @click="upFunc('ms')">+</div>
					<div class="number-button number-down" @click="downFunc('ms')">-</div>
				</div>
			</div>				
			<div class="three-elements">
				<div class="element">
					<div class="form-group ">
				       <div class="input-group">
				        <datepicker v-model="date_end" class="form-control"></datepicker>
				        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
				       </div>
				    </div>
				</div>
				<div class="number element">
				  	<input type="number" min="8" max="20" step="1" v-model="hour_end">
					<div class="number-button number-up" @click="upFunc('he')">+</div>
					<div class="number-button number-down" @click="downFunc('he')">-</div>
				</div>
				<div class="number element">
				  <input type="number" min="0" max="59" step="1" v-model="min_end">
					<div class="number-button number-up" @click="upFunc('me')">+</div>
					<div class="number-button number-down" @click="downFunc('me')">-</div>
				</div>
			</div>
		</div>
		<a @click="sendRequest" class="envoye-demande-btn cursor">Envoyer une demande de contact</a>
		<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
	</div>
	<span @click="close" class="popup-click"></span>
</div>
</template>