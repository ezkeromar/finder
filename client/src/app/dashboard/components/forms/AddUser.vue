<script>
	import { mapActions, mapGetters } from 'vuex'
	import { addUserToClient } from '../../services.js'
	import { baseUrl } from '../../../../config'
	import { bus } from '../../../../plugins/eventbus'
	import Datepicker from 'vuejs-datepicker';
	import moment from 'moment'
	export default {
		props: [],
	    data() {
	        return {
	        	showAddUserPop:false,
	        	firstname:null,
	        	lastname:null,
	        	position:null,
	        	password:null,
	        	email:null,
	        	phone:null,
	        	picture:null
	        }
	    },
	    mounted() {
	    	bus.$on('user.add.pop', ($event) => {
		        this.showAddUserPop = !this.showAddUserPop;
		    })
		    this.picture = new FormData();
    	},
    	computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
	    methods: {
	    	close(){
      			$('body').removeClass('no-scroll');
      			bus.$emit('user.add.pop');
      		},
      		addUser() {
      			this.picture.append('email', this.email)
      			this.picture.append('password', this.password)
      			this.picture.append('firstname', this.firstname)
      			this.picture.append('lastname', this.lastname)
      			this.picture.append('position', this.position)
      			this.picture.append('is_client', this.currentClient.id)
      			this.$http.post('/add/user/client', this.picture).then(function(response) {
      				console.log(response.data.status);
      				if(response.data.status == 'error') {
      					swal(response.data.message);
      				} else {
      					$('body').removeClass('no-scroll');
		      			bus.$emit('user.add.pop');
		      			bus.$emit('user.added');
      				}
      			});
		    	this.picture = new FormData();

      		},
      		onFileChange() {
	            var file = this.$refs.image.files;
	            this.picture.append('logo', file[0]);
	        }
	    },
	}
</script>

<template>
	<div v-show="showAddUserPop" class="popup active">
		<div class="popup-container popup-rdv active">
			<div class="popup-content" style="overflow-x: hidden; overflow-y: auto">
				<label class="one-element">
					<span class="title small black">Prénom</span>
					<input v-model="firstname" placeholder="Prénom" type="text" />
				</label>
				<label class="one-element">
					<span class="title small black">Nom</span>
					<input v-model="lastname" placeholder="Nom" type="text" />
				</label>
				<label class="one-element">
					<span class="title small black">Email</span>
					<input v-model="email" placeholder="Email" type="email" />
				</label>
				<label class="one-element">
					<span class="title small black">Mot de passe</span>
					<input v-model="password" placeholder="Mot de passe" type="password" />
				</label>
				<label class="one-element">
					<span class="title small black">Profession</span>
					<input v-model="position" placeholder="Profession" type="text" />
				</label>
				<label class="one-element">
					<span class="title small black">Téléphone</span>
					<input v-model="phone" placeholder="Téléphone" type="text" />
				</label>
				<div class="full-label" style="padding: 0px;">
					<span class="title black">Photo</span>
					<label class="input-file-holder">
						<input ref="image" @change="onFileChange" type="file" class="input-file">
						<span>Ajoutez une photo</span>
					</label>
				</div>
			</div>
			<a @click="addUser" class="envoye-demande-btn cursor">Ajouter Utilisateur</a>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
		<span @click="close" class="popup-click"></span>
	</div>
</template>