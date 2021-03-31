<script>
	import { mapActions, mapGetters } from 'vuex'
	import { loadSkills, loadTechnologies, addOffer, loadCities } from '../../services.js'
	import { baseUrl } from '../../../../config'
	import { bus } from '../../../../plugins/eventbus'
	import Datepicker from 'vuejs-datepicker';
	import moment from 'moment'
	export default {
		props: ['showAddPopup'],
	    data() {
	        return {
	        	skills:null,
		        selectSkills:null,
		        selectedSkillsVal:null,
		        selectTechnologies:null,
		        selectedTechnologiesVal:null,
		        technologies:null,
		        selectCities:null,
		        selectedCitiesVal:null,
		        cities:null,
		        title:'',
		        description:'',
		        date_start:'',
		        tjm:'',
		        duration:'',
		        showloader: false,
		        id:null,
		        selectedTechnologiesLoad:null,
		        selectedSkillsLoad:null
	        }
	    },
	    mounted() {
	    	this.technologiesFunc();
		    this.skillsFunc();
		    this.citiesFunc();
		    bus.$on('update.offer.popup', ($missionAttr) => {
		    	this.duration = $missionAttr.duration;
		    	this.tjm = $missionAttr.tjm;
		    	this.date_start = $missionAttr.date_start
		    	this.description = $missionAttr.description
		    	this.title = $missionAttr.title
		    	this.selectedCitiesVal = $missionAttr.city_id
		    	this.selectedTechnologiesLoad = $missionAttr.technology
		    	this.selectedSkillsLoad = $missionAttr.skill
		    	this.id = $missionAttr.id
		    	this.citiesSelectFunc()
		    	this.technologiesSelectFunc()
		    	this.skillsSelectFunc()
		    })
    	},
    	computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
	    methods: {
	    	resetFields() {
	    		this.duration = null;
		    	this.tjm = null;
		    	this.id = null;
		    	this.date_start = null
		    	this.description = null
		    	this.title = null
		    	this.selectedCitiesVal = null
		    	this.selectedTechnologiesLoad = null
		    	this.selectedSkillsLoad = null
		    	this.selectCities = null
		    	this.selectTechnologies = null
		    	this.selectSkills = null
	    	},
	    	citiesSelectFunc() {
	    		for (var i = this.cities.length - 1; i >= 0; i--) {
				    if(this.cities[i].value == this.selectedCitiesVal)
				        this.selectCities = this.cities[i];
				}
		    },
		    technologiesSelectFunc() {
		    	this.selectTechnologies = [];
		    	this.selectedTechnologiesVal = [];
		    	for (var i = this.technologies.length - 1; i >= 0; i--) {
		    		for (var j = this.selectedTechnologiesLoad.length - 1; j >= 0; j--) {
		    			if(this.technologies[i].value == this.selectedTechnologiesLoad[j].id) {
					        this.selectTechnologies.push(this.technologies[i]);	
						    this.selectedTechnologiesVal.push(this.selectedTechnologiesLoad[j].id);
						}
		    		}
				}
		    },
		    skillsSelectFunc() {
		    	this.selectedSkillsVal = [];
		    	this.selectSkills = [];
		    	for (var i = this.skills.length - 1; i >= 0; i--) {
		    		for (var j = this.selectedSkillsLoad.length - 1; j >= 0; j--) {
		    			if(this.skills[i].value == this.selectedSkillsLoad[j].id) {
					        this.selectSkills.push(this.skills[i]);	
						    this.selectedSkillsVal.push(this.selectedSkillsLoad[j].id);
						}
		    		}
				}
		    },
	    	onChangeSkill(obj) {
		        this.selectedSkillsVal = [];
		        for (var i = obj.length - 1; i >= 0; i--) {
		          this.selectedSkillsVal.push(obj[i].value);
		        }
		    },

		    onChangeTechnology(obj) {
		        this.selectedTechnologiesVal = [];
		        for (var i = obj.length - 1; i >= 0; i--) {
		          this.selectedTechnologiesVal.push(obj[i].value);
		        }
		    },

		    technologiesFunc() {
		        var sr = this;
		        loadTechnologies().then(function(response) {
		          sr.technologies = response.technologies;
		        })
		    },

		    onChangeCity(obj) {
		        this.selectedCitiesVal = obj.value;
		    },

		    citiesFunc() {
		        var sr = this;
		        loadCities().then(function(response) {
		          sr.cities = response.cities;
		        })
		    },

		    skillsFunc() {
		        var sr = this;
		        loadSkills().then(function(response) {
		          sr.skills = response.skills;
		        })
		    },
	    	updatemission() {
	    		if(moment().diff(moment(this.date_start)) <= 0) {
		    		var offer = this;
		    		this.showloader = true;
		    		if(this.currentClient) {
			    		addOffer(this.id ,this.title, this.description, this.duration, this.tjm, moment(this.date_start).format('DD/MM/YYYY'), this.selectedCitiesVal, this.currentClient.id, this.selectedTechnologiesVal, this.selectedSkillsVal, this.currentUser.id).then(function(response) {
		    			offer.showloader = false;
		    			swal('Offer Updated successfully');
			    		bus.$emit('hide.offer.popup');
		    			bus.$emit('offer.created');
		    			});
		    		} else {
		    			swal('Your are not client');
		    		}
		    		this.$router.push({name : 'dashboard.missions'});
		    		this.resetFields()
		    	} else {
		    		swal("la date doit etre superieur a aujourd'huit")
		    	}
	    	},
	    	addmission() {
	    		if(moment().diff(moment(this.date_start)) <= 0) {
		    		var offer = this;
		    		this.showloader = true;
		    		if(this.currentClient) {
			    		addOffer(this.id, this.title, this.description, this.duration, this.tjm, moment(this.date_start).format('DD/MM/YYYY'), this.selectedCitiesVal, this.currentClient.id, this.selectedTechnologiesVal, this.selectedSkillsVal, this.currentUser.id).then(function(response) {
		    			offer.showloader = false;
		    			swal('Offer created successfully');
			    		bus.$emit('hide.offer.popup');
		    			bus.$emit('offer.created');
		    			});
		    		} else {
		    			swal('Your are not client');
		    		}
		    		this.$router.push({name : 'dashboard.missions'});
		    		this.resetFields()
		    	} else {
		    		swal("la date doit etre superieur a aujourd'huit")
		    	}
	    	},
	    	close() {
	    		bus.$emit('hide.offer.popup');
	    		this.$router.push({name : 'dashboard.missions'});
	    		this.resetFields()
	    	}
	    },
	    components:{
	         'datepicker':Datepicker
	    },
	}
</script>
<template>
<div v-if="showAddPopup" class="popup active" :class="{loading:showloader}">
	<div class="popup-container active">
		<div class="popup-content">
			<label class="one-element">
				<span class="title small black">Titre de la mission</span>
				<input v-model="title" placeholder="Titre de la mission" type="text" />
			</label>
			<label class="one-element">
				<span class="title small black">Description de la mission</span>
				<textarea v-model="description" placeholder="Description de la mission"></textarea>
			</label>
			<label class="one-element">
				<span class="title small black">duration de la mission</span>
				<input type="number" v-model="duration" placeholder="duration de la mission"/>
			</label>
			<label class="one-element">
				<span class="title small black">tjm de la mission</span>
				<input type="number" v-model="tjm" placeholder="tjm de la mission"/>
			</label>
			<label class="one-element">
				<span class="title small black">Date de debut de la mission</span>
				<datepicker v-model="date_start"></datepicker>
			</label>
			<span class="title small black">Les technologies demander</span>
			<div class="addmission-input">
				<v-select v-if="this.technologies" multiple v-model="selectTechnologies" :on-change="onChangeTechnology" :options="this.technologies" :searchable="true" placeholder="Téchnologies"></v-select>
			</div>
			<span class="title small black">Les compétance demander</span>
			<div class="addmission-input">
				<v-select v-if="this.skills" multiple v-model="selectSkills" :on-change="onChangeSkill" :options="this.skills" :searchable="true" placeholder="Compétences"></v-select>
			</div>
			<span class="title small black">La ville</span>
			<div class="addmission-input">
				<v-select v-if="this.cities" v-model="selectCities" :on-change="onChangeCity" :options="this.cities" :searchable="true" placeholder="Ville"></v-select>
			</div>
		</div>
		<a @click="addmission" class="envoye-demande-btn cursor" v-if="id == null">Ajouter une Offer</a>
		<a @click="updatemission" class="envoye-demande-btn cursor" v-else>Modifier une Offer</a>
		<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
	</div>
	<span @click="close" class="popup-click"></span>
</div>
	
</template>