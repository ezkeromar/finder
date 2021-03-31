<template>
	<div>
		<link rel="stylesheet" href="/static/css/homepage.css">

		<header>
			<div class="home-header">
				<ul>
					<li><a href="javascript:void(0);"><i class="fa fa-envelope"></i></a></li>
					<li><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
					<li><a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a></li>
					<li><a href="javascript:void(0);"><i class="fa fa-google-plus"></i></a></li>
				</ul>
			</div>
		</header>
		
		<div class="fluid-container">
			<div class="content">
				<section id="one" class="main-homepage" style="margin-top: 52px; z-index: 1;">
					<div class="homepage-main-content">
						<router-link tag="a" class="btn-orange" style="border: 0;" :to="{ name: 'auth.signup' }">
				            <span>S’INSCRIRE</span>
				        </router-link>

				        <router-link tag="a" class="btn-black" style="margin-top: 30px;" :to="{ name: 'auth.signin' }">
				            <span>SE CONNECTER</span>
				        </router-link>
					</div>
				</section>
			</div>
		</div>

		<div class="container page-list">
			<div class="content" :class="{loading:showloader}">
				<div class="list-container">
					<div class="row" style="margin-top: 12em;">
			            <div v-for="consultant in consultants" class="col-md-3">
			              <div class="list-profile" @click="showcreateacount()">
			                <div class="img-holder" @click="seeProfile(consultant.id)">
			                  <img v-if="consultant.picture != null" :src="baseUrlattr+consultant.picture">
			                </div>
			                <div class="list-profile-data">
			                  <span>{{consultant.firstname}}. {{consultant.lastname}}.</span>
			                  <p>{{consultant.profession}}</p>
			                  <div class="profile-stars" v-if='consultant.rate'>
			                    <span v-for='rate in parseInt(consultant.rate)' class="active"><i class="fa fa-star"></i></span>
			                    <span v-for='rate in 5-parseInt(consultant.rate)'><i class="fa fa-star"></i></span>
			                  </div>
			                </div>
			                <a @click="seeProfile(consultant.id)" class="cursor consulter-profile">Consulter le profile</a>
			                <a @click="selectToOfferFunc(consultant.id)" class="cursor consulter-plus">+</a>
			              </div>
			            </div>
			        </div>
				</div>
			</div>
		</div>
		
	</div>

</template>
<script>
  import { searchFrontConsultants } from "../../services.js"
  import { baseUrl } from '../../../../config'

  export default { 
	data() {
      return {
      		consultants:null,
      		showloader:true,
	        baseUrlattr:baseUrl,
      	}
      },
      mounted() {
      	this.search();
      },
      methods: {
      	search() {
	        this.showloader = true;
	        var sr = this;
	          searchFrontConsultants().then(function(response){
	            sr.consultants = response.consultants;
	            sr.showloader = false;
	          });
	    },
	    showcreateacount() {
	    	swal("veuillez crée un compte tout d'abord")
	    }
	}
}
</script>