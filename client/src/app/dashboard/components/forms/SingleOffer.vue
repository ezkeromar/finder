<script>
  import { mapGetters } from 'vuex'
  import { bus } from '../../../../plugins/eventbus'
  import { getMission, UpdateConsultentSelection, deleteSelection, loadProfile } from '../../services'
  import { baseUrl } from '../../../../config'
  import draggable from 'vuedraggable'
  import RateForm from './rateform.vue'
  import Meeting from './Meeting.vue'
  import Single from './Single.vue'

	export default {
		data() {
      		return {
      			mission: null,
            baseUrlattr: baseUrl,
            shortlist:[],
            select:[],
            condidat:[],
            consultant:[],
            completed:[],
            showloader:false,
            ratedConsultentId:null,
            archive:null,
            selectedConsultent:null,
            disponibility:null,
            consultent:null
      		}
      	},
      	mounted() {
          if(this.$route.query.archive) {
            this.archive = this.$route.query.archive;
          }
      		this.missionFunc()
          bus.$on('create.rate', ($event) => {
            this.missionFunc()
          })
      	},
        computed: {
          ...mapGetters(['currentClient', 'currentUser']),
        },
      	methods: {
          fixMeeting(id) {
            $('body').addClass('no-scroll');
            this.selectedConsultent = id;
            bus.$emit('fix.meeting');
          },
          showRateUserPopFunc(id) {
            $('body').addClass('no-scroll');
            bus.$emit('user.rate.pop', id);
          },
      		missionFunc(){
      			this.showloader = !this.showloader;
            var sr = this;
		        getMission(this.$route.query.id, this.archive).then(function(response) {
		          sr.mission = response.mission;
              sr.showloader = !sr.showloader;
		        })
      		},
          saveSelection(){
            var sr = this;
            sr.showloader = !sr.showloader;
            for (var i = this.mission.shortlist.length - 1; i >= 0; i--) {
              this.shortlist.push(this.mission.shortlist[i].id)
            }
            for (var i = this.mission.condidat.length - 1; i >= 0; i--) {
              this.condidat.push(this.mission.condidat[i].id)
            }
            for (var i = this.mission.select.length - 1; i >= 0; i--) {
              this.select.push(this.mission.select[i].id)
            }
            for (var i = this.mission.consultant.length - 1; i >= 0; i--) {
              this.consultant.push(this.mission.consultant[i].id)
            }
            UpdateConsultentSelection(this.shortlist, this.select, this.consultant, this.condidat, this.mission.id).then(function(response) {
              sr.missionFunc();
              sr.showloader = !sr.showloader;
              sr.select = [];
              sr.shortlist = [];
              sr.consultant = [];
              sr.condidat = [];
            })
          },
          CheckMove() {
            swal('Ne permit pas la retro active');
          },
          deleteSelect(id) {
            var sr = this;
            swal({
              title: "",
              text: "Voulez vous vraimment supprimé se utilisateur !",
              type: "warning",
              showCancelButton: true,
              confirmButtonText: "Oui !",
              cancelButtonText: 'Non'
            }).then((result) => {
                sr.showloader = !sr.showloader;
                deleteSelection(id, this.mission.id).then(function(response) {
                  sr.missionFunc();
                  sr.showloader = !sr.showloader;
                  sr.select = [];
                  sr.shortlist = [];
                })
            })
          },
          seeProfile(id) {
            $('body').addClass('no-scroll');
            this.showloader = !this.showloader;
            var sr = this;
            loadProfile(id).then(function(response) {
              sr.consultent = response.data.consultent;
              if(sr.consultent.exp_days>30) 
                sr.disponibility = '+1 mois' 
              else if(sr.consultent.exp_days<=30 && sr.consultent.exp_days>15)
                sr.disponibility = '1 mois' 
              else if(sr.consultent.exp_days <= 15 && sr.consultent.exp_days >0) 
                sr.disponibility = '2 semaine'
              else if(sr.consultent.exp_days <=0)
                sr.disponibility = 'Immédiatement'
              sr.showloader = !sr.showloader;
            })
            bus.$emit('see.single');
          }
      	},
        components: {
          'draggable':draggable,
          'rate-user':RateForm,
          'meeting':Meeting,
          'single':Single
        }
	}
</script>

<template>
<div class="" :class="{loading:showloader}">
  <div class="container">
    <section class="mesoffres">
      <ul>
        <li v-if="mission">
          <div class="img-holder">
            <div class="img">
              <img v-if="currentClient.logo" :src="baseUrlAttr+currentClient.logo">
            </div>
          </div>
          <div class="row offre-content">
            <div class="col-md-12">
              <span>{{mission.title}}</span>
              <p>{{mission.description}}</p>
            </div>
            <div class="col-md-12">
              <span>Date début</span>
              <p>{{mission.date_start}}</p>
            </div>
            <div class="col-md-6" v-if="mission.skill">
              <span>Compétence</span>
              <p><i v-for='skill in mission.skill' v-if="skill.title">{{skill.title}}, </i></p>
            </div>
            <div class="col-md-6" v-if="mission.technology">
              <span>Téchnologies</span>
              <p><i v-for='technology in mission.technology' v-if="technology.title">{{technology.title}}, </i></p>
            </div>
          </div>
        </li>
      </ul>
    </section>
  </div>
  <a v-if="mission && archive == null" class="cursor btn-orange" @click="saveSelection">Enregistrer</a>
  <div class="drag-holder" style="background-color: rgb(221, 221, 221)">
    <div class="container" :class="{loading:showloader}">
      <br><br>
      <h3 v-if="mission && archive == null" class="consultant-mission title black">
        Consultant
        <span class="tooltip">Consultant</span>
      </h3>
      <draggable v-if="mission && archive == null" style="min-height:60px; background-color:#ddd;" class="row dragzone" v-model="mission.consultant" :options="{group:'consultent'}" @start="CheckMove" @end="drag=false">
        <div v-for="consultant in mission.consultant" :key="consultant.id" class="col-md-3 item" @click="seeProfile(consultant.id)">
          <div class="list-profile">
            <div class="img-holder">
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
            <a @click="showRateUserPopFunc(consultant.id)" class="cursor consulter-terminated">Terminer</a>
          </div>
        </div>
      </draggable>
      <hr style="background-color: #000">
      <h3 v-if="mission && archive == null" class="shortlist-mission title black">
        Shortlist
        <span class="tooltip">Shortlist</span>
      </h3 v-if="mission && archive == null">
      <draggable v-if="mission && archive == null" style="min-height:60px; background-color:#ddd;" class="row dragzone" v-model="mission.shortlist" :options="{group:'consultent'}" @start="drag=true" @end="drag=false">
        <div v-for="consultant in mission.shortlist" :key="consultant.id" class="col-md-3 item" @click="seeProfile(consultant.id)">
          <div class="list-profile">
            <div class="img-holder">
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
            <a @click="deleteSelect(consultant.id)" class="cursor consulter-supprimer">Supprimer</a>
            <a @click="fixMeeting(consultant.id)" class="cursor consulter-plus">+</a>
          </div>
        </div>
      </draggable>
      <hr v-if="mission && archive == null">
      <h3 v-if="mission && archive == null" class="selection-mission title black">
        Selection
        <span class="tooltip">Selectiont</span>
      </h3>
      <draggable v-if="mission && archive == null" style="min-height:60px; background-color:#ddd;" class="row dragzone" v-model="mission.select" :options="{group:'consultent'}" @start="drag=true" @end="drag=false">
        <div v-for="consultant in mission.select" :key="consultant.id" class="col-md-3 item" @click="seeProfile(consultant.id)">
          <div class="list-profile">
            <div class="img-holder">
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
            <a @click="deleteSelect(consultant.id)" class="cursor consulter-supprimer">Supprimer</a>
            <a @click="fixMeeting(consultant.id)" class="cursor consulter-plus">+</a>
          </div>
        </div>
      </draggable>
      <hr v-if="mission && archive == null">
      <h3 v-if="mission && archive == null" class="selection-mission title black">
        Candidatures spontannées
        <span class="tooltip">Candidatures spontannées</span>
      </h3>
      <draggable v-if="mission && archive == null" style="min-height:60px; background-color:#ddd;" class="row dragzone" v-model="mission.condidat" :options="{group:'consultent'}" @start="drag=true" @end="drag=false">
        <div v-for="consultant in mission.condidat" :key="consultant.id" class="col-md-3 item" @click="seeProfile(consultant.id)">
          <div class="list-profile">
            <div class="img-holder">
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
            <a @click="deleteSelect(consultant.id)" class="cursor consulter-supprimer">Supprimer</a>
            <a @click="fixMeeting(consultant.id)" class="cursor consulter-plus">+</a>
          </div>
        </div>
      </draggable>
      <hr v-if="mission && archive == null">
      <h3 class="archive-mission"  v-if="mission && archive != null">
        Archive
        <span class="tooltip">Archive</span>
      </h3>
      <draggable v-if="mission && archive != null" style="min-height:60px; background-color:#ddd;" class="row dragzone" v-model="mission.completed" :options="{group:'consultent'}" @start="drag=true" @end="drag=false">
        <div v-for="consultant in mission.completed" :key="consultant.id" class="col-md-3 item" @click="seeProfile(consultant.id)">
          <div class="list-profile">
            <div class="img-holder">
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
          </div>
        </div>
      </draggable>
      <rate-user></rate-user>
    </div>
  </div>
  <meeting :baseUrlattr="baseUrlattr" :consultent="selectedConsultent" :mission="mission.id"></meeting>
  <single :consultent="consultent" :baseUrlattr="baseUrlattr" :disponibility="disponibility"></single>
</div>
</template>