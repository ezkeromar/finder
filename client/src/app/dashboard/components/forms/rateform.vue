<script>
	import { mapActions, mapGetters } from 'vuex'
	import { loadConsultentInfo, RateConsultent } from '../../services.js'
	import { baseUrl } from '../../../../config'
	import { bus } from '../../../../plugins/eventbus'
	import Datepicker from 'vuejs-datepicker';
	import moment from 'moment'
	export default {
		props: [],
	    data() {
	        return {
	        	showRatePop:false,
	        	consultentId:null,
	        	consultant:null,
	        	baseUrlAttr:baseUrl,
	        	satisfaction:2,
	        	competance:0,
	        	methodology:0,
	        	reach_goal:0,
	        	respect_details:0,
	        	respect_rules:0,
	        	qualities:0
	        }
	    },
	    mounted() {
	    	bus.$on('user.rate.pop', ($consultent) => {
		        this.showRatePop = !this.showRatePop;
		        this.consultentId = $consultent;
		        var ro = this;
		        if(this.showRatePop == true) {
			        loadConsultentInfo(this.consultentId).then(function(response) {
			        	ro.consultant = response.consultent_info;
			        })
			    }
				$('ul.tabs li').click(function(){
					var tab_id = $(this).attr('data-tab');
					$('ul.tabs li').removeClass('current');
					$('.tab-content').removeClass('current');

					$(this).addClass('current');
					$("#"+tab_id).addClass('current');
				})
		    })
    	},
    	computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
	    methods: {
	    	close(){
      			$('body').removeClass('no-scroll');
      			bus.$emit('user.rate.pop');
      		},
      		giveRate() {
      			RateConsultent(this.satisfaction, this.competance, this.methodology, this.reach_goal, this.respect_details, this.respect_rules, this.qualities, this.consultentId, this.$route.query.id).then(function(response){
      				swal('Thank you for your rate')
      			})
      			bus.$emit('create.rate')
      			this.close()
      		},
      		satisfactionFunc(id) {
      			this.satisfaction = id;
      		},
      		satisfactionSelect(val) {
      			if(val == this.satisfaction)
      				return true
      			else
      				return false
      		},
      		cOtherCriteria(val) {
      			if(val <= this.competance)
      				return true
      			else
      				return false
      		},
      		mOtherCriteria(val) {
      			if(val <= this.methodology)
      				return true
      			else
      				return false
      		},
      		rOtherCriteria(val) {
      			if(val <= this.reach_goal)
      				return true
      			else
      				return false
      		},
      		rdOtherCriteria(val) {
      			if(val <= this.respect_details)
      				return true
      			else
      				return false
      		},
      		rrOtherCriteria(val) {
      			if(val <= this.respect_rules)
      				return true
      			else
      				return false
      		},
      		qOtherCriteria(val) {
      			if(val <= this.qualities)
      				return true
      			else
      				return false
      		}
	    },
	}
</script>
<template>
	<div v-if="showRatePop" class="popup active">
		<div class="popup-container popup-consultant eval active" >
			<div class="popup-consultant-header">
				<div class="img-holder">
					<img v-if='consultant' :src="baseUrlAttr+consultant.picture">
				</div>
				<div v-if='consultant' class="consultant-info">
					<div class="consutlant-name">
						<p>{{consultant.firstname}} {{consultant.lastname}}</p>
					</div>
					<span>{{consultant.profession}}</span> 
				</div>
			</div>
			<div class="popup-consultant-content">
				<div class="block">
					<span class="title black">Satisfaction</span>
					<div class="satisfaction-level">
						<a @click="satisfactionFunc(2)" class="cursor" :class="{active:satisfactionSelect(2)}">
							<img src="/static/img/icons/eval-good.png">
						</a>
						<a @click="satisfactionFunc(1)" class="cursor" :class="{active:satisfactionSelect(1)}">
							<img src="/static/img/icons/eval-normal.png">
						</a>
						<a @click="satisfactionFunc(0)" class="cursor" :class="{active:satisfactionSelect(0)}">
							<img src="/static/img/icons/eval-bad.png">
						</a>
					</div>
				</div>
				<div class="block">
					<span class="title black">Appréciation Consultant </span>
					<div class="eval-stars-holder">
						<span>Du niveau de compétence métier</span> 
						<div class="eval-stars">
							<span @click="competance = 0" :class="{active:cOtherCriteria(0)}"><i class="fa fa-star"></i></span>
							<span @click="competance = 1" :class="{active:cOtherCriteria(1)}"><i class="fa fa-star"></i></span>
							<span @click="competance = 2" :class="{active:cOtherCriteria(2)}"><i class="fa fa-star"></i></span>
							<span @click="competance = 3" :class="{active:cOtherCriteria(3)}"><i class="fa fa-star"></i></span>
							<span @click="competance = 4" :class="{active:cOtherCriteria(4)}"><i class="fa fa-star"></i></span>
						</div>  
					</div>
					<div class="eval-stars-holder">
						<span>De la méthodologie de travail</span> 
						<div class="eval-stars">
							<span @click="methodology = 0" :class="{active:mOtherCriteria(0)}"><i class="fa fa-star"></i></span>
							<span @click="methodology = 1" :class="{active:mOtherCriteria(1)}"><i class="fa fa-star"></i></span>
							<span @click="methodology = 2" :class="{active:mOtherCriteria(2)}"><i class="fa fa-star"></i></span>
							<span @click="methodology = 3" :class="{active:mOtherCriteria(3)}"><i class="fa fa-star"></i></span>
							<span @click="methodology = 4" :class="{active:mOtherCriteria(4)}"><i class="fa fa-star"></i></span>
						</div>  
					</div>
					<div class="eval-stars-holder">
						<span>De l'atteinte des objectif fixé</span> 
						<div class="eval-stars">
							<span @click="reach_goal = 0" :class="{active:rOtherCriteria(0)}"><i class="fa fa-star"></i></span>
							<span @click="reach_goal = 1" :class="{active:rOtherCriteria(1)}"><i class="fa fa-star"></i></span>
							<span @click="reach_goal = 2" :class="{active:rOtherCriteria(2)}"><i class="fa fa-star"></i></span>
							<span @click="reach_goal = 3" :class="{active:rOtherCriteria(3)}"><i class="fa fa-star"></i></span>
							<span @click="reach_goal = 4" :class="{active:rOtherCriteria(4)}"><i class="fa fa-star"></i></span>
						</div>  
					</div>
					<div class="eval-stars-holder">
						<span>Du respéct des délais</span> 
						<div class="eval-stars">
							<span @click="respect_details = 0" :class="{active:rdOtherCriteria(0)}"><i class="fa fa-star"></i></span>
							<span @click="respect_details = 1" :class="{active:rdOtherCriteria(1)}"><i class="fa fa-star"></i></span>
							<span @click="respect_details = 2" :class="{active:rdOtherCriteria(2)}"><i class="fa fa-star"></i></span>
							<span @click="respect_details = 3" :class="{active:rdOtherCriteria(3)}"><i class="fa fa-star"></i></span>
							<span @click="respect_details = 4" :class="{active:rdOtherCriteria(4)}"><i class="fa fa-star"></i></span>
						</div>  
					</div>
					<div class="eval-stars-holder">
						<span>Du réspect de votre réglement intérieur</span> 
						<div class="eval-stars">
							<span @click="respect_rules = 0" :class="{active:rrOtherCriteria(0)}"><i class="fa fa-star"></i></span>
							<span @click="respect_rules = 1" :class="{active:rrOtherCriteria(1)}"><i class="fa fa-star"></i></span>
							<span @click="respect_rules = 2" :class="{active:rrOtherCriteria(2)}"><i class="fa fa-star"></i></span>
							<span @click="respect_rules = 3" :class="{active:rrOtherCriteria(3)}"><i class="fa fa-star"></i></span>
							<span @click="respect_rules = 4" :class="{active:rrOtherCriteria(4)}"><i class="fa fa-star"></i></span>
						</div>  
					</div>
					<div class="eval-stars-holder">
						<span>Des téchnique personnelles du consultant</span> 
						<div class="eval-stars">
							<span @click="qualities = 0" :class="{active:qOtherCriteria(0)}"><i class="fa fa-star"></i></span>
							<span @click="qualities = 1" :class="{active:qOtherCriteria(1)}"><i class="fa fa-star"></i></span>
							<span @click="qualities = 2" :class="{active:qOtherCriteria(2)}"><i class="fa fa-star"></i></span>
							<span @click="qualities = 3" :class="{active:qOtherCriteria(3)}"><i class="fa fa-star"></i></span>
							<span @click="qualities = 4" :class="{active:qOtherCriteria(4)}"><i class="fa fa-star"></i></span>
						</div>  
					</div>
				</div> 
			</div>
			<div class="popup-btns">
				<a @click="giveRate()" class="cursor btn-orange full">VALIDER</a> 
			</div>
			<a @click="close()" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
		<span class="popup-click"></span>
	</div>
</template>