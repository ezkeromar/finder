<script>
	import { loadMissions, meetRequest } from '../../../services.js'
	import { mapGetters } from 'vuex'
	import { bus } from '../../../../../plugins/eventbus'
	import Datepicker from 'vuejs-datepicker'
	import moment from 'moment'
	export default {
		props: ['mission', 'baseUrlAttr'],
		data() {
			return {
				showMission: false,
				selected_mission: null,
			}
		},
      	mounted() {
      		bus.$on('show.mission', ($event) => {
	            this.showMission = !this.showMission;
	            $('<div class="number-button number-up">+</div><div class="number-button number-down">-</div>').insertAfter('.number input');			    
	        })
      	},
      	watch: {
      	},
      	computed: {
	      ...mapGetters(['currentUser']),
	    },
      	methods: {
      		moment: function (date) {
			    return moment(date).format('DD MMMM YYYY');
			},
	      	close() {
      			$('body').removeClass('no-scroll');
      			bus.$emit('show.mission');
      		},
      	},
	    components:{
	         'datepicker':Datepicker
	    },
    }
</script>

<template>
	<div v-show="showMission" class="popup active">
		<div class="popup-container popup-addCV active" >
			<div class="popup-content popup-consultant-content">
				<div class="half-label">
					<span class="title black">Mission</span>
					<p><i>{{mission.title}}</i></p>
				</div>
				<div class="half-label">
					<img v-if="mission.client" :src="baseUrlAttr+mission.client['logo']" class="img-responsive" :alt="mission.title">
				</div>
				<div class="full-label">
					<span class="title black">Ville</span>
					<p><i>{{mission.city.title}}</i></p>
				</div>				
				<div class="half-label">
					<span class="title black">Date début</span>
					<p><i>{{moment(mission.date_start)}}</i></p>
				</div>
				<div class="half-label">
					<span class="title black">Date de fin</span>
					<p><i>{{moment(mission.date_end)}}</i></p>
				</div>
				<div class="full-label">
					<span class="title black">Description</span>
					<p><i>{{mission.description}}</i></p>
				</div>
				<div class="half-label">
					<span class="title black">Durée</span>
					<p><i>{{mission.duration}} jours</i></p>
				</div>
				<div class="half-label">
					<span class="title black">TJM</span>
					<p><i>{{mission.tjm}} Dhs</i></p>
				</div>
				<label class="half-label">
					<span class="title black">Compétences</span>
					<p><i v-for='skill in mission.skill' v-if="skill.title">{{skill.title}}, </i></p>
				</label>
				<label class="half-label">
					<span class="title black">Technologies</span>
					<p><i v-for='technology in mission.technology' v-if="technology.title">{{technology.title}}, </i></p>
				</label>
			</div>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
	</div>
</template>