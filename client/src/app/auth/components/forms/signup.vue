<template>
<transition name="slide-fade">
	<div class="senregister" :class="{loading:showloader}">
      <span>Vous êtes?</span>
      <div class="vousetes-choices">
        <label><input v-model="is_client" value="0" name="is_client" type="radio" checked>Consulant</label>
        <label><input v-model="is_client" value="1" name="is_client" type="radio">Client</label>
      </div>
	    <form v-on:submit.prevent="submit">
	      <div class="senregistrer-inputs">
	        <input class="input-text" v-model="firstname" required type="text" placeholder="Prenom d'utilisateur">
	        <input class="input-text" v-model="lastname" required type="text" placeholder="Nom d'utilisateur">
	        <input class="input-text" v-show="is_client == 1" v-model="brand" type="text" placeholder="Nom de client">
	        <input class="input-text" v-model="email" required type="email" placeholder="Email@exemple.com">
	        <!--<input class="input-text" required v-model="phone" type="tel" pattern="[0]{1}[5-7]{1}[0-9]{8}" placeholder="Téléphone">-->
	        <input class="input-text" required v-model="phone" type="tel" pattern="(00|\+)[0-9]{10,14}" placeholder="Téléphone">
	        <input class="input-text" required v-model="password" type="password" placeholder="Mot de passe">
	      </div>
	      <label class="conditions">
	        <!--<input required  value="1" v-model="conditions" name="conditions" type="radio">-->
	        <input required value="1" name="conditions" type="checkbox" id="conditions" v-model="conditions">
	        <!--<a @click="(conditions == 0) ? conditions = 1 : conditions = 0" href="javascript:void(0);">Accepter les termes et conditions</a>-->
	        <a @click="showTermesGenerale">Accepter les termes et conditions</a>

	      </label>
	      <input type="submit" class="btn-orange cursor" name="">
	    </form>
	    <ModalTermes></ModalTermes>
    </div>

	

</transition>

</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import ModalTermes from './ModalTermes.vue'
import { bus } from '../../../../plugins/eventbus'
export default {
	data() {
		return {
			email:'',
			password:'',
			firstname:'',
			lastname:'',
			phone:'',
			is_client:0,
			conditions:0,
			showloader:false
		}
	},
	mounted(){
		// try {
		// 	this.checkUserToken()
		// } catch(error) {

		// }
      	// if(this.currentUser)
       //  	this.$router.push({ name: 'dashboard.index' });


		this.$bus.$on('client.created', ($event) => {
			console.log($event);
			this.showloader = !this.showloader;
	      	swal({type: 'warning',title:'Client created wait after activation!!!'});
	    })
	    this.$bus.$on('user.created', ($event) => {
	    	this.showloader = !this.showloader;
	      	swal({type: 'warning',title:'Consultant created check your email for activation link activation!!!'});
	    })
	    this.navigate()
	},
	computed: {
         ...mapGetters(['currentUser', 'currentClient'])
    },
	methods: {
		...mapActions(['register', 'setMessage', 'navigateTab', 'checkUserToken']),
		
		navigate() {
			this.navigateTab(this.$route.name)
		},
		
		submit() {
	    	this.showloader = !this.showloader;
	        const { email, password, firstname, lastname, brand, phone, is_client, conditions } = this 
	        if(conditions == 1) {
	        	this.register({ email, password, firstname, lastname, brand, phone, is_client }) 
	          	.then((data) => {
	          		console.log(data)
	          		if(data.token != null)
		          		this.setMessage({ type: 'error', message: [] })	
					else
						swal('Se email déjà exist');
	        	})
	      	}
	    },

	    reset() {
	        this.email = ''
	        this.password = ''
	        this.firstname = ''
	        this.lastname = ''
	        this.brand = ''
	        this.phone = ''
	        this.is_client = 1
	    },
	    showTermesGenerale() {
	        $('body').addClass('no-scroll');
	        console.log('click termes generale');
	        bus.$emit('show.termes');
	    },
	},
    components:{
      'ModalTermes' : ModalTermes,
    },
}
</script>

<style scoped>
  .slide-fade-enter-active {
    transition: all .8s ease;
  }
  .slide-fade-leave-active {
    transition: all .0s cubic-bezier(1.0, 0.8, 0.5, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
  }
</style>