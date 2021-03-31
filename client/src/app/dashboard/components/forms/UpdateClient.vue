<script>
	import { mapActions, mapGetters } from 'vuex'
	import { loadCities, updateClient } from '../../services.js'
	import { baseUrl } from '../../../../config'
	import { bus } from '../../../../plugins/eventbus'
	import Datepicker from 'vuejs-datepicker';
	import moment from 'moment'
	export default {
		props: [],
	    data() {
	        return {
	        	showAddUserPop:false,
	        	name:null,
	        	email:null,
	        	phone:null,
	        	selectCities:null,
		        selectedCitiesVal:null,
		        cities:null,
		        
	        	picture:null,
	        }
	    },
	    mounted() {
		    this.citiesFunc();
	    	this.name = this.currentClient.name;
        	this.email = this.currentClient.email;
        	this.phone = this.currentClient.phone;
	        this.selectedCitiesVal = this.currentClient.city_id;
        	this.picture = this.currentClient.logo;
	    	bus.$on('update.client.pop', ($event) => {
		        this.showAddUserPop = !this.showAddUserPop;
		    })
		    this.picture = new FormData();
    	},
    	computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
	    methods: {
	    	onChangeCity(obj) {
		        this.selectedCitiesVal = obj.value;
		    },

		    citiesFunc() {
		        var sr = this;
		        loadCities().then(function(response) {
		          	sr.cities = response.cities;
		          	if(sr.cities != null) {
				        for (var i = sr.cities.length - 1; i >= 0; i--) {
				        	if(sr.cities[i].value == sr.currentClient.city_id)
				        		sr.selectCities = sr.cities[i];
				        }
				    }
		        })
		    },
	    	close(){
      			$('body').removeClass('no-scroll');
      			bus.$emit('update.client.pop');
      		},
      		updateClientFunc() {
      			this.picture.append('email', this.email)
      			this.picture.append('name', this.name)
      			this.picture.append('city', this.selectedCitiesVal)
      			this.picture.append('phone', this.phone)
      			this.picture.append('is_client', this.currentClient.id)
      			this.$http.post('/update/client', this.picture).then(function(response) {
      				$('body').removeClass('no-scroll');
	      			bus.$emit('update.client.pop');
	      			bus.$emit('client.updated');
      			});
		    	this.picture = new FormData();

      		},
      		onFileChange() {
	            var file = this.$refs.image.files;
	            console.log(file);
	            this.picture.append('logo', file[0]);
	        }
	    },
	}
</script>

<template>
	<div v-show="showAddUserPop" class="popup active">
		<div class="popup-container popup-rdv active">
			<div class="popup-content">
				<label class="one-element">
					<span class="title small black">Name</span>
					<input v-model="name" placeholder="Name" type="text" />
				</label>
				<label class="one-element">
					<span class="title small black">Email</span>
					<input v-model="email" placeholder="Email" type="email" />
				</label>
				<span class="title small black">La ville</span>
				<div class="addmission-input">
					<v-select v-if="this.cities" v-model="selectCities" :on-change="onChangeCity" :options="this.cities" :searchable="true" placeholder="Ville"></v-select>
				</div>
				<label class="one-element">
					<span class="title small black">Téléphone</span>
					<input v-model="phone" placeholder="Téléphone" type="text" />
				</label>
				<div class="full-label" style="padding: 0px;">
					<span class="title black">Logo</span>
					<label class="input-file-holder">
						<input ref="image" :value="currentClient.picture" type="file" @change="onFileChange" class="input-file">
						<span>Ajoutez Logo</span>
					</label>
				</div>
			</div>
			<a @click="updateClientFunc" class="envoye-demande-btn cursor">Ajouter Utilisateur</a>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
		<span @click="close" class="popup-click"></span>
	</div>
</template>