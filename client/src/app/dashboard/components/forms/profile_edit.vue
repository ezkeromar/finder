<script>
import { mapActions, mapGetters } from 'vuex'
import { loadCountries, loadSkills, loadTechnologies, loadConsultent, editConsultant, deleteExperience, deleteCertif, deleteDiploma, loadDiplomas } from '../../services.js'
import profile_edit_experience from './modals/profile_edit_experience.vue'
import profile_edit_experience_update from './modals/profile_edit_experience_update.vue'
import profile_edit_diploma from './modals/profile_edit_diploma.vue'
import profile_edit_certif from './modals/profile_edit_certif.vue'
import { baseUrl, uploadUrl } from '../../../../config'
import { bus } from '../../../../plugins/eventbus'
import Datepicker from 'vuejs-datepicker'
import moment from 'moment'
 
  export default { 
    data() {
      return {
        consultentInfo: null, 
        baseUrlAttr: baseUrl,
        uploadUrlAttr: uploadUrl,
        experience_selected: null,
        skills: null,
        technologies: null,
        countries: null,
        diplomas: null,
        selectTechnologies: null, 
        selectedTechnologiesVal: null,
        selectSkills: null,
        selectedSkillsVal: null, 
        showloader: false,
		profileData:null,
      }
    },
    computed: {
      ...mapGetters(['currentUser']),
    },
    mounted() {
		this.profileData = new FormData();
    	$(document).on('change', '.input-file', function () {
    		//alert('file change');
    		
	        $(".input-file").each(function() {
	          var fileName = $(this).val().split('/').pop().split('\\').pop();
	          $(".input-file-holder span").empty();
	          $( ".input-file-holder span" ).append(fileName);
	        });
	    });

		$(document).on('click', "#upload_link", function(e){
			e.preventDefault();
			$("#upload").trigger('click');
		});

		
      this.navigate()
      this.loadInfo()
      this.load_diplomas()
      this.load_skills()
      this.load_technologies()
      this.load_countries()
    },
    methods: {
      ...mapActions(['navigateTab']),

	  	onFilePictChange: function() {
			var file = this.$refs.logoimage.files;
			console.log('object file : ');
			console.log(file[0]);
	        this.profileData.append('logopict', file[0]);
	    },

      	moment: function (date) {
		    return moment(date).format('MMMM YYYY');
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
     	consultant_edit_do() {
     		var pf = this;
     		pf.showloader = true;
    		this.consultentInfo.disponibility_date = moment(this.consultentInfo.disponibility_date).format('DD/MM/YYYY')
	        this.profileData.append('consultentInfo', JSON.stringify(this.consultentInfo));
	        //this.profileData.append('firstname', this.consultentInfo.firstname);
	        //this.profileData.append('lastname', this.consultentInfo.lastname);
	        //this.profileData.append('experience', JSON.stringify(this.consultentInfo.experience));
	        /*editConsultant(this.consultentInfo).then(function(response) {
	          	console.log(response);
	          });*/
			this.$http.post('/consultant/edit', this.profileData).then(function(response){
    			//swal("Modification profile", response.data.message, "success");
				pf.showloader = false;
				pf.profileData = null;
				pf.profileData = new FormData();
    			pf.loadInfo()
    		});
    	},
    	onFileChange() {
	    	var file = this.$refs.image.files;
	    	console.log('detail');
	    	console.log(file[0]);
	    	this.consultentInfo.curriculum_vitae.append('curriculum_vitae', file[0]);
	    },
    	loadInfo() {
	        var pf = this; 
	        if(this.currentUser.id) {
	          loadConsultent(this.currentUser.id).then(function(response) {
	          	pf.consultentInfo = response.consultent_info;
	          });
	        }
    	},
    	load_skills() {
    		var pf = this;
    		loadSkills().then(function(response) {
		          pf.skills = response.skills;
		        });
    	},
    	load_diplomas() {
    		var pf = this;
    		loadDiplomas().then(function(response) {
		          pf.diplomas = response.diplomas;
		        });
    	},
    	load_technologies(){
    		var pf = this;
    		loadTechnologies().then(function(response) {
    			pf.technologies = response.technologies;
		    });
    	},
    	load_countries() {
    		var pf = this;
    		loadCountries().then(function(response) {
		          pf.countries = response.countries;
		        });
    	},
    	experience_delete_do(experience_id) {
    		swal({
                    text: "Voulez vous vraiment supprimer cet expérience ?", icon: "warning", buttons: true, dangerMode: true,
                }).then((willDelete) => {
				  if (willDelete) {
				  	var pf = this; 
		    		deleteExperience(experience_id).then(function(response){
		    			swal(response.data.message); 
		    			pf.loadInfo()
		    		});
				  }
				});     		
    	},
    	diploma_delete_do(diploma_id) {
    		swal({
                    text: "Voulez vous vraiment supprimer ce diplôme ?", icon: "warning", buttons: true, dangerMode: true,
                }).then((willDelete) => {
				  if (willDelete) {
				  	var pf = this; 
		    		deleteDiploma(diploma_id).then(function(response){
		    			swal(response.data.message); 
		    			pf.loadInfo()
		    		});			    
				  }
				});    		
    	},
    	showAddExperience() {
    		bus.$emit('add.experience');
    	},
    	showEditExperience(experience) {
    		this.experience_selected = experience;
    		bus.$emit('edit.experience', experience);
    	},
    	showAddDiploma() {
    		bus.$emit('add.diploma');
    	},
    	showAddCertif() {
    		bus.$emit('add.certif');
    	},
    	navigate() { 
        	this.navigateTab(this.$route.name)
    	},
    	certif_delete_do: function(certif_id) {
            swal({
                    text: "Voulez vous vraiment supprimer cette certification ?", icon: "warning", buttons: true, dangerMode: true,
                }).then((willDelete) => {
				  if (willDelete) {
				  	var pf = this; 
		    		deleteCertif(certif_id).then(function(response){
		    			swal(response.data.message, { icon: "success", });
		    			pf.loadInfo()
		    		});				    
				  }
				});
        },
    },    
    components:{
        'profileEditExperience':profile_edit_experience,
        'profileEditDiploma':profile_edit_diploma,        
        'profileEditCertif':profile_edit_certif,
        'profileEditExperienceUpdate':profile_edit_experience_update,
        'datepicker':Datepicker
    },
  }
</script>
<template>
<div class="main-content">
<div class="container page-profile">
				<div class="content row">
					<div class="page-content col-md-12">
						<span class="title black">Modifier mon profile</span>
						<section class="modifier-profil" :class="{loading:showloader}">
							<div class="modifier-profil-holder">
								<div class="img-container">
										<input id="upload" ref="logoimage" @change="onFilePictChange" type="file"/>​
									<div class="img-holder">
										<img v-if="consultentInfo.picture" :src="uploadUrlAttr+consultentInfo.picture">
										<img v-else src="http://placehold.it/200x200">
										<a id="upload_link" class="edit-img cursor"><i class="fa fa-pen"></i></a>
									</div>
								</div>
								<label class="half-label">
									<span class="title black">Nom</span>
									<input type="text" placeholder="Nom" v-model="consultentInfo.firstname" />
								</label>
								<label class="half-label">
									<span class="title black">Prénom</span>
									<input type="text" placeholder="Prénom" v-model="consultentInfo.lastname" />
								</label>
								<label class="half-label">
									<span class="title black">Email</span>
									<input disabled="disabled" type="email" placeholder="Mail" v-model="consultentInfo.email" />
								</label>
								<label class="half-label"> 
									<span class="title black">Téléphone</span>
									<input type="tel" placeholder="Tél" v-model="consultentInfo.phone" />
								</label>
								<label class="full-label">
									<span class="title black">Adresse</span>
									<textarea placeholder="Votre adresse..." v-model="consultentInfo.address"></textarea>
								</label>
								<label class="full-label">
									<span class="title black">Disponibilité</span>
									<datepicker v-model="consultentInfo.disponibility_date" placeholder="Date de disponibilité"></datepicker>
								</label>
								<label class="col-md-12">
									<span class="title black">Nationnalité</span>
									<div class="addmission-input">
										<v-select v-if="this.countries" v-model="consultentInfo.country" :options="this.countries" :searchable="true" placeholder="Pays"></v-select>
									</div>
								</label>
								<label class="full-label">
									<span class="title black">Poste actuel</span>
									<input type="text" placeholder="Poste actuel" v-model="consultentInfo.profession" />
								</label>
								<label class="full-label">
									<span class="title black">Taux journalier moyen (TJM)</span>
									<input type="text" placeholder="TJM" v-model="consultentInfo.tjm" />
								</label>
								<label class="col-md-12">
									<span class="title black">Technologies</span>
									<select class="technologies select2 hide"> 
										<option v-if="technologies" v-for="technology_item in technologies">
											{{technology_item.label}}
										</option>
									</select>
									<div class="addmission-input">
										<v-select v-if="technologies" multiple v-model="consultentInfo.technology" :on-change="onChangeTechnology" :options="technologies" :searchable="true" placeholder="Technologies"></v-select>
									</div>
								</label>
								<label class="col-md-12">
									<span class="title black">Compétences</span>
									<select class=" select2 hide"> 
										<option v-if="skills" v-for="skill in skills">
											{{skill.label}}
										</option>
									</select>
									<div class="addmission-input">
										<v-select v-if="skills" multiple v-model="consultentInfo.skill" :on-change="onChangeSkill" :options="skills" :searchable="true" placeholder="Compétences"></v-select>
									</div>
								</label>  
								<label class="full-label add_experiences">
									<span class="title black">Expérience</span>
							        <div v-if="consultentInfo.experience" v-for="exp in consultentInfo.experience" class="block-shadow">
										<span>
											De {{moment(exp['date_start'])}} 
											à {{moment(exp['date_end'])}} 
										</span>
										<p>{{exp['title']}} - {{exp['client']}}</p>

              							<a @click="showEditExperience(exp)" class="cursor update-offer">Update</a>
										<a @click="experience_delete_do(exp['id'])" class="cursor delete-utilisateur"></a>
									</div>
									<a @click="showAddExperience" class="btn-orange small plus add-exp cursor">Ajoutez expérience</a>
								</label>

								<label class="full-label add_experiences">
									<span class="title black">Formation</span>
							        <div v-if="consultentInfo.userdiplome" v-for="diploma in consultentInfo.userdiplome" class="block-shadow">
										<span>{{diploma['year']}} - {{diploma['speciality']}}</span>
										<p>{{diploma['school']}} - {{diploma['speciality']}}</p>
										<a @click="diploma_delete_do(diploma['id'])" class="cursor delete-utilisateur"></a>
									</div>
									<a @click="showAddDiploma" class="btn-orange small plus add-exp cursor">Ajoutez diplôme</a>
								</label>

								<label class="full-label add_experiences">
									<span class="title black">Certificats</span>
							        <div v-if="consultentInfo.certif" v-for="certification in consultentInfo.certif" class="block-shadow">
										<span>{{certification['title']}}</span>
										<p>{{certification['certif']}}</p>
										<a @click="certif_delete_do(certification['id'])" class="cursor delete-utilisateur"></a>
									</div>
									<a @click="showAddCertif" class="btn-orange small plus add-exp cursor">Ajoutez certificat</a>
								</label>
								<div class="full-label">
									<span class="title black">Ajouter CV</span>
									<label class="input-file-holder">
										<input ref="image" :value="consultentInfo.curriculum_vitae" type="file" :on-change="onFileChange" class="input-file" id="cv_input">						
										<span>Ajoutez votre CV</span>
									</label>

								</div>

							</div>
							<a @click="consultant_edit_do" class="envoye-demande-btn cursor">Sauvegarder les changement</a>
						</section>
					</div>
				</div>
			</div>
    <profileEditExperience v-if="consultentInfo.experience" :experience="consultentInfo.experience"></profileEditExperience>
    <profileEditExperienceUpdate :experience="experience_selected"></profileEditExperienceUpdate>
    <profileEditDiploma v-if="diplomas" :diplomas="diplomas" :diploma="consultentInfo.userdiplome"></profileEditDiploma>
    <profileEditCertif v-if="consultentInfo.certif" :certification="consultentInfo.certif"></profileEditCertif>

</div>
</template>