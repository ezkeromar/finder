<script>
  import { mapActions, mapGetters } from 'vuex'
  import { addUserToMission, searchConsultants, searchMissions, loadSkills, loadTechnologies, loadProfile, loadSecteurs } from '../../services'
  import { baseUrl } from '../../../../config'
  import { bus } from '../../../../plugins/eventbus'
  import Single from './Single.vue'
  import SelectToOffer from './SelectToOffer.vue'
  import Datepicker from 'vuejs-datepicker'
  import moment from 'moment'
  
  import vueSlider from 'vue-slider-component'

  export default { 

    data() {
      return {
        selectedConsultent:null,
        consultants: {},
        missions: {},
        seniorities:[],
        disponibilities:[],
        tjmMax:'',
        tjmMin:'',
        rates:[],
        skills:[], 
        selectSkills:[],
        selectedSkillsVal:[],
        selectTechnologies:[],
        selectedTechnologiesVal:[],
        technologies:[],
        baseUrlattr:baseUrl,
        showFilters:false,
        searchTerm:'', 
        consultent:null,
        showPupup:false,
        showloader:false,
        profil_connected: null,
        secteurs:null,
        selectSecteurs:null,
        selectedSecteursVal:[],
        disponibility:null,
        xhr:null,

        slider_pretty: {
          value: [0, 5000],
          width: '100%',
          height: 8,
          dotSize: 20,
          min: 0,
          max: 5000,
          disabled: false,
          show: true,
          tooltip: 'always',
          tooltipDir: ['bottom', 'top'],
          lazy: true,
          'real-time': true,
          piecewise: false,
          style: {
            marginBottom: '30px'
          },
          bgStyle: {
            backgroundColor: '#fff',
            boxShadow: 'inset 0.5px 0.5px 3px 1px rgba(0,0,0,.36)'
          },
          sliderStyle: [{
            backgroundColor: '#ffbe33'
          }, {
            backgroundColor: '#000'
          }],
          tooltipStyle: [{
            backgroundColor: '#ffbe33',
            borderColor: '#ffbe33'
          }, {
            backgroundColor: '#000',
            borderColor: '#000'
          }],
          processStyle: {
            backgroundImage: '-webkit-linear-gradient(left, #ffbe33, #000)'
          }
        },


      } 
    },
    computed: {
      ...mapGetters(['currentUser', 'currentClient']),
    },
    mounted() {
      //console.log('client id = ' + this.currentClient.id);
      //console.log('user id = ' + this.currentUser.id);

      this.searchTerm = this.$route.query.term;
      this.technologiesFunc();
      this.secteursFunc();
      this.skillsFunc();
      bus.$on('search.consultants', ($event) => {
          this.search();
      })
      bus.$on('change.termsearch', ($event) => {
          this.searchTerm = this.$route.query.term;
      })
      bus.$on('user.added.to.mission', ($event) => {
        swal('Consultent ajouté dans cette mission avec success')
      })
      this.search()
    },
    watch: {
      '$route': function(newRoute, oldRoute) {
        this.searchTerm = newRoute.query.term;
        this.search();
      },
      'slider_pretty.value': function(newValue, oldValue) {
        this.tjmMin = newValue[0];
        this.tjmMax = newValue[1];
        this.launcheFilter();
      }
    },
    methods: {
      launcheFilter: function() {
        bus.$emit('search.consultants');
      },
      moment: function (date) {
          return moment(date).format('DD / MM / YYYY');
      },
      onChangeSkill(obj) {
        this.selectedSkillsVal = [];
        for (var i = obj.length - 1; i >= 0; i--) {
          this.selectedSkillsVal.push(obj[i].value);
        }
        bus.$emit('search.consultants');
      },

      onChangeTechnology(obj) {
        this.selectedTechnologiesVal = [];
        for (var i = obj.length - 1; i >= 0; i--) {
          this.selectedTechnologiesVal.push(obj[i].value);
        }
        bus.$emit('search.consultants');
      },

      onChangeSecteurs(obj) {
        if(obj != null) {
          this.selectedSecteursVal = obj.value;
        }
        else {
          this.selectedSecteursVal = null;
        }
        bus.$emit('search.consultants');
      },

      onChangeFilterConsultant(obj) {
        this.selectedTechnologiesVal = [];
        for (var i = obj.length - 1; i >= 0; i--) {
          this.selectedTechnologiesVal.push(obj[i].value);
        }
        bus.$emit('search.consultants');
      },

      technologiesFunc() {
        var sr = this;
        loadTechnologies().then(function(response) {
          sr.technologies = response.technologies;
        })
      },

      secteursFunc() {
        var sr = this;
        loadSecteurs().then(function(response) {
          sr.secteurs = response.secteurs;
        })
      },

      skillsFunc() {
        var sr = this;
        loadSkills().then(function(response) {
          sr.skills = response.skills;
        })
      },

      filter() {
        if(this.showFilters == false){
          this.showFilters = !this.showFilters;
        } else {
          this.showFilters = !this.showFilters;
        }
      },
      sendCandidateMission(mission) {
        var suo = this;
        addUserToMission(this.currentUser.id, mission.id, 2).then(function(response) {
          swal('Votre candidature envoyé avec succés')
        })
      },
      search(pageNum = 1) {
        this.showloader = !this.showloader;
        var sr = this;
       if(this.currentClient != undefined && this.currentClient != null) {
          this.xhr = searchConsultants(this.searchTerm, pageNum, this.seniorities, this.disponibilities, this.tjmMin, this.tjmMax, this.rates, this.selectedSkillsVal, this.selectedTechnologiesVal, this.selectedSecteursVal, this.currentClient.id).then(function(response){
            sr.consultants = response.consultants;
            sr.showloader = !sr.showloader;
            sr.profil_connected = 'client';
          });
        } else if(this.currentUser.id) {
          searchMissions(this.searchTerm, pageNum, this.tjmMin, this.tjmMax).then(function(response){
            sr.missions = response.missions;
            sr.showloader = !sr.showloader;
            sr.profil_connected = 'consultant';
          });
        }
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
      },

      selectToOfferFunc(id) {
        $('body').addClass('no-scroll');
        this.selectedConsultent = id;
        bus.$emit('select.to.offer');
      },

      change_slider() {
        getValue('slider6');
      }
    },
    components:{
        'single':Single,
        'select-to-offer':SelectToOffer,
        'datepicker':Datepicker,
        vueSlider,
    },
  }
</script>

<template>
  <div :class="{loading:showloader}">


    
    <div class="col-md-12"></div>
        


    <div class="filter-holder" :class="{active:showFilters}">
      <a @click="filter" class="filter-btn cursor"><i class="fa fa-chevron-left"></i></a>
      <div v-if="profil_connected == 'client'" class="filter-block">
        <span class="title black">Séniorité</span>
        <label>
          <input @change="launcheFilter" id="seniority10" value="4" type="checkbox" v-model="seniorities">
          <span class="checkbox-span"></span>
          <span class="filter-text">Expert <br><p>+10 ans</p></span>
        </label>
        <label>
          <input @change="launcheFilter" id="seniority5" value="3" type="checkbox" v-model="seniorities">
          <span class="checkbox-span"></span>
          <span class="filter-text">Sénior<br><p>5 à 10 ans</p></span>
        </label>
        <label>
          <input @change="launcheFilter" id="seniority3" value="2" type="checkbox" v-model="seniorities">
          <span class="checkbox-span"></span>
          <span class="filter-text">Intérmédiaire<br><p>3 à 5 ans</p></span>
        </label>
        <label>
          <input @change="launcheFilter" id="seniority0" value="1" type="checkbox" v-model="seniorities">
          <span class="checkbox-span"></span>
          <span class="filter-text">Débutant<br><p>0 à 3 ans</p></span>
        </label>
      </div>
      <div class="filter-block">
        <span class="title black">Disponibilité</span>
        <label>
          <input @change="launcheFilter" id="now" value="1" type="checkbox" v-model="disponibilities">
          <span class="checkbox-span"></span>
          <span class="filter-text">Immediate</span>
        </label>
        <label>
          <input @change="launcheFilter" id="towweeks" value="2" type="checkbox" v-model="disponibilities">
          <span class="checkbox-span"></span>
          <span class="filter-text">Dans 2 sem</span>
        </label>
        <label>
          <input @change="launcheFilter" id="oneMonth" value="3" type="checkbox" v-model="disponibilities">
          <span class="checkbox-span"></span>
          <span class="filter-text">1 mois</span>
        </label>
        <label>
          <input @change="launcheFilter" id="plusmonth" value="4" type="checkbox" v-model="disponibilities">
          <span class="checkbox-span"></span>
          <span class="filter-text">+1 mois</span>
        </label>
      </div>
      <!--<div class="filter-block slider">-->
      <div class="filter-block "> 
        <span class="title black">TJM</span>
        <!--<input @change="launcheFilter" style="display:block;" type="range" v-model="tjmMin">
        <input @change="launcheFilter" style="display:block;" type="range" v-model="tjmMax">-->
            
        <div class="col-md-12">
          <vue-slider ref="slider6" id="pretty-slider" v-bind="slider_pretty" v-model="slider_pretty.value"></vue-slider>
        </div>
        
        
      </div>
      <div v-if="profil_connected == 'client'" class="filter-block filter-stars">
        <span class="title black">Avis</span>
        <label>
          <input @change="launcheFilter" id="ratetree" value="1" type="checkbox" v-model="rates">
          <span class="checkbox-span"></span>
          <div class="filter-stars-holder">
            <span><i class="fa fa-star"></i></span>
          </div>
        </label>
        <label>
          <input @change="launcheFilter" id="ratetree" value="2" type="checkbox" v-model="rates">
          <span class="checkbox-span"></span>
          <div class="filter-stars-holder">
            <span><i class="fa fa-star"></i></span>
            <span><i class="fa fa-star"></i></span>
          </div>
        </label>
        <label>
          <input @change="launcheFilter" id="ratetree" value="3" type="checkbox" v-model="rates">
          <span class="checkbox-span"></span>
          <div class="filter-stars-holder">
            <span><i class="fa fa-star"></i></span>
            <span><i class="fa fa-star"></i></span>
            <span><i class="fa fa-star"></i></span>
          </div>
        </label>
      </div>
    </div>
    <div class="container page-list">
      <div class="content">
        <div v-if="profil_connected == 'client'" class="row">
          <div class="row">
            <div class="col-md-4">
              <v-select v-if="this.skills" multiple v-model="selectSkills" :on-change="onChangeSkill" :options="this.skills" :searchable="true" placeholder="Compétences"></v-select>
            </div>
            <div class="col-md-4">
              <v-select v-if="this.technologies" multiple v-model="selectTechnologies" :on-change="onChangeTechnology" :options="this.technologies" :searchable="true" placeholder="Téchnologies"></v-select>
            </div>
            <div class="col-md-4">
              <v-select v-if="this.secteurs" v-model="selectSecteurs" :on-change="onChangeSecteurs" :options="this.secteurs" :searchable="true" placeholder="Secteurs"></v-select>
            </div>
          </div>
        </div>
        <div class="list-container">
          <span v-if="profil_connected == 'client'" class="title black">"{{this.$route.query.term}}" ( {{consultants.total}} )</span>
          <span v-if="profil_connected == 'consultant'" class="title black">"{{this.$route.query.term}}" ( {{missions.total}} )</span>
          <div class="row" v-if="consultants.total > 0">
            <div v-for="consultant in consultants.data" class="col-md-3">
              <!--<p>déjà participé</p>-->
              <div class="list-profile">
                <div class="img-holder" @click="seeProfile(consultant.id)">
                  <img v-if="consultant.picture != null" :src="baseUrlattr+consultant.picture">
                </div>
                <div class="list-profile-data">
                  <span @click="seeProfile(consultant.id)" class="cursor">{{consultant.firstname}}. {{consultant.lastname}}.</span>
                  <span>missions num : {{consultant.missions_number}}</span>
                  <!--<span>ID : {{consultant.id}}</span>-->
                  <p @click="seeProfile(consultant.id)" class="cursor">{{consultant.profession}}</p>
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
          <div class="row emptySearch" v-if="profil_connected == 'client' && !consultants.total">
            Aucun consultant trouvé
          </div>

        <section class="mesoffres">
          <div v-if="missions" class="row missions_side">
            <ul>
              <li v-for="offer in missions.data">
                <div class="img-holder">
                  <div class="img">
                    <img v-if="offer.client.logo" :src="baseUrlattr+offer.client.logo">
                  </div>
                </div>
                <div class="row offre-content">
                  <div class="col-md-12">
                    <span>{{offer.title}}</span>
                    <p>{{offer.description}}</p>
                  </div>
                  <div class="col-md-6">
                    <span>Date début</span>
                    <p>{{ moment(offer.date_start) }}</p>
                  </div>
                  <div class="col-md-6">
                    <span>Date fin</span>
                    <p>{{ moment(offer.date_end) }}</p>
                  </div>
                  <div class="col-md-12">
                    <span>TJM</span>
                    <p>{{ offer.tjm }}</p>
                  </div>
                  <div class="col-md-6" v-if="offer.skill">
                    <span>Compétence</span>
                    <p><i v-for='skill in offer.skill' v-if="skill.title">{{skill.title}}, </i></p>
                  </div>
                  <div class="col-md-6" v-if="offer.technology">
                    <span>Téchnologies</span>
                    <p><i v-for='technology in offer.technology' v-if="technology.title">{{technology.title}}, </i></p>
                  </div>
                </div>

                <a @click="sendCandidateMission(offer)" class="btn-black cursor">Postuler pour cette mission</a>

              </li>
            </ul>
          </div>
          <div class="row emptySearch" v-if="profil_connected == 'consultant' && !missions.total">
            Aucune mission trouvé
          </div>
          
        </section>

          <paginate v-show="parseInt(missions.last_page)>1"
            :page-count="parseInt(missions.last_page)"
            :click-handler="search"
            :prev-text="'Prev'"
            :next-text="'Next'"
            :container-class="'pagination'">
          </paginate>

          <paginate v-show="parseInt(consultants.last_page)>1"
            :page-count="parseInt(consultants.last_page)"
            :click-handler="search"
            :prev-text="'Prev'"
            :next-text="'Next'"
            :container-class="'pagination'">
          </paginate>
        </div>
      </div>
    </div>
    <single :consultent="consultent" :baseUrlattr="baseUrlattr" :disponibility="disponibility"></single>
    <select-to-offer :consultent="selectedConsultent" :mission="null"></select-to-offer>
  </div>
</template>


