<script>
	import { mapActions, mapGetters } from 'vuex'
	import { getMissions, getConsultantMissions, deleteOffer, toggleOffer, addMissionExperience, getOnlyMission } from '../../services.js'
	import { baseUrl } from '../../../../config'
  import { bus } from '../../../../plugins/eventbus'
  import AddMission from './AddMission.vue'
  import moment from 'moment'

	export default {
	    data() {
        return {
  	    	missionslist:[],
          baseUrlAttr: baseUrl,
          showAddPopup: false,
          showloader: false,
          isArchiveAttr: this.$route.query.archive,
          is_client : false,
          max_desc: 100,
          max_title: 45,
	      }
      },
    	mounted() {
        this.getmissions();
        bus.$on('offer.created', ($event) => {
          this.getmissions();
        })
        bus.$on('hide.offer.popup', ($event) => {
          $('body').removeClass('no-scroll');
          this.showAddPopup = false;
        })
        bus.$on('show.offer.popup', ($event) => {
          this.showMissionPopUp()
        })
    	},
      watch: {
        '$route': function(newRoute, oldRoute) {
          this.isArchiveAttr = newRoute.query.archive,
          this.getmissions();
        },
      },
    	computed: {
	      ...mapGetters(['currentClient', 'currentUser']),
	    },
    	methods: {
        showMissionPopUp() {
          $('body').addClass('no-scroll');
          this.showAddPopup = true;
        },
        moment: function (date) {
          return moment(date).format('DD / MM / YYYY');
        },
        short_text: function (word, max_text) {
          if(word.length < max_text)
            return word;
          else if(word.length >= max_text)
            return word.substring(0, max_text) + "..";
        },
        updatemission(id) {
          var oe = this;
          getOnlyMission(id).then(function(response) {
            bus.$emit('update.offer.popup', response.mission)
            oe.showMissionPopUp()
          })
        },
        addLikeExperience(offer) {
          swal({
                text: "Voulez vous vraiment ajouter cette mission à votre CV ?", icon: "warning", buttons: true, dangerMode: true,
                  }).then((willAddMission) => {
            if (willAddMission) {
              var pf = this; 
              addMissionExperience(this.currentUser.id, offer.id).then(function(response) {
                console.log(response.response.message);
                if(response.status == true)
                  swal(response.response.message);
                else swal(response.response.message)
              })
            }
          });
        },
    		getmissions(pageNum = 1) {
    			var offer = this;
          this.showloader = true;
          var $archive = null;
          if(this.isArchiveAttr == 1)
            $archive = 'archive';
          if(this.currentClient == null) {
            // consultant
            this.is_client = false;
            getConsultantMissions(this.currentUser.id, pageNum).then(function(response) {
               offer.missionslist = response.missions;
               offer.showloader = false;
            })
          } else {
            // client
            this.is_client = true;
            getMissions(this.currentClient.id, $archive,pageNum, this.currentUser.id).then(function(response) {
               offer.missionslist = response.missions;
               offer.showloader = false;
               if(offer.$route.query.addpopup == 1) {
                  bus.$emit('show.offer.popup')
                }
            })
          }
    		},
        deletemission(id) {
          var offer = this;
          deleteOffer(id).then(function(response) {
            swal('Offer deleted successefully');
            bus.$emit('offer.created');
          })
        },
        togglemission(id) {
          var offer = this;
          toggleOffer(id).then(function(response) {
            swal('Offer togled successefully');
            bus.$emit('offer.created');
          })
        },
        checkedOrNot(val) {
          if(val == 'current')
            return true;
          else
            return false;
        }
    	},
      components:{
         'addmission':AddMission
      },
	}
</script>

<template>
	<div class="container" :class="{loading:showloader}">
    <!--<a class="cursor addofferbtn" v-if='isArchiveAttr != 1' @click="showAddPopup=!showAddPopup">Add Offer</a>-->
    <div v-if="is_client">
		<section  class="mesoffres">
          <span class="title black">Mes offres en cours</span>
          <ul v-if="parseInt(missionslist.total)>0">
            <li v-for="offer in missionslist.data">
              <a v-if='isArchiveAttr != 1' @click="deletemission(offer.id)" class="cursor delete-utilisateur"></a>
              <a v-if='isArchiveAttr != 1' @click="updatemission(offer.id)" class="cursor update-offer">Update</a>
              <router-link tag="a" class="cursor archiveBtn" :to="{ name: 'dashboard.mission', query: {id : offer.id, archive : 'archive'} }">Archive</router-link>
              <label v-if='isArchiveAttr != 1' class="switch">
                <input type="checkbox" v-if="checkedOrNot(offer.status)" checked @change="togglemission(offer.id)">
                <input type="checkbox" v-else @change="togglemission(offer.id)">
                <span class="slider round"></span>
              </label>
              <div class="img-holder">
                <div class="img">
                  <img v-if="currentClient.logo" :src="baseUrlAttr+currentClient.logo">
                </div>
              </div>
              <div class="row offre-content">
                <div class="col-md-12">
                <router-link tag="span" class="cursor" :to="{ name: 'dashboard.mission', query: {id : offer.id} }">{{offer.title}}</router-link>
                  <p>{{offer.description}}</p>
                </div>
                <div class="col-md-12">
                  <span>Date début</span>
                  <p>{{offer.date_start}}</p>
                </div>
                <div class="col-md-6" v-if="offer.skill">
                  <span>Compétences</span>
                  <p><i v-for='skill in offer.skill' v-if="skill.title">{{skill.title}}, </i></p>
                </div>
                <div class="col-md-6" v-if="offer.technology">
                  <span>Technologies</span>
                  <p><i v-for='technology in offer.technology' v-if="technology.title">{{technology.title}}, </i></p>
                </div> 
              </div>
            </li>
          </ul>

          <!--<router-link tag="a" class="cursor btn-orange small plus" :to="{ name: 'dashboard.missions' }">Voir toutes les offres</router-link>-->

          <a class="cursor addofferbtn btn-orange small plus" v-if='isArchiveAttr != 1' @click="showMissionPopUp">Ajouter une mission</a>
    </section>
    <paginate v-show="parseInt(missionslist.last_page)>1"
          :page-count="parseInt(missionslist.last_page)"
          :click-handler="getmissions"
          :prev-text="'Prev'"
          :next-text="'Next'"
          :container-class="'pagination'">
        </paginate>
    </div>

    <div v-if="is_client==false" class="page-content list-container col-md-12">
        <!-- mission current -->
        <div v-if="missionslist.current" class="mesoffres consultant">
          <span class="title black">Mes missions en cours</span>
          <ul>
            <li v-if="missionslist.current" v-for="offer in missionslist.current">
                  <div class="left">
                    <div class="left-holder">
                      <div class="img-holder">
                        <div class="img">
                          <img v-if="offer.client" :src="baseUrlAttr+offer.client['logo']">
                        </div>
                      </div>
                      <div class="row offre-content">
                        <div class="col-md-12">
                          <span>{{ short_text(offer.title, max_title)}}</span>
                          <p>{{ short_text(offer.description, max_desc) }}</p>
                        </div>
                        <div class="col-md-6">
                          <span>Date début</span>
                          <p>{{moment(offer.date_start)}}</p>
                        </div>
                        <div class="col-md-6">
                          <span>Date fin</span> 
                          <p>{{moment(offer.date_end)}}</p>
                        </div>
                        <div class="col-md-6" v-if="offer.skill">
                          <span>Compétence</span>
                          <p><i v-for='skill in offer.skill.slice(0,3)' v-if="skill.title">{{skill.title}}, </i>..</p>
                        </div>
                        <div class="col-md-6" v-if="offer.technology">
                          <span>Technologies</span>
                          <p><i v-for='technology in offer.technology.slice(0,3)' v-if="technology.title">{{technology.title}}, </i> ..</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="right orange">
                    <p>Etat <br>d’avancement </p>
                    <div class="percent-holder">
                      <div :class="['c100 p' + offer.progression]">
                        <span>{{offer.progression}}%</span>
                          <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                          </div>
                      </div>
                    </div>
                    
                    <a @click="addLikeExperience(offer)" class="btn-black cursor">Ajouter à mon CV</a>
                  </div>
            </li>
          </ul>
        </div>
        <!--<router-link tag="a" class="cursor btn-orange small plus" :to="{ name: 'dashboard.missions' }">Voir toutes les offres</router-link>-->

        <!-- mission finished -->
        <div v-if="missionslist.finished" class="mesoffres consultant">
          <span class="title black">Mes missions complétes</span>
          <ul>
            <li class="inactive" v-if="missionslist.finished" v-for="offer in missionslist.finished.data">
                  <div class="left">
                    <div class="left-holder">
                      <div class="img-holder">
                        <div class="img">
                          <img v-if="offer.client" :src="baseUrlAttr+offer.client['logo']">
                        </div>
                      </div>
                      <div class="row offre-content">
                        <div class="col-md-12">
                          <span>{{ short_text(offer.title, max_title)}}</span>
                          <p>{{ short_text(offer.description, max_desc) }}</p>
                        </div>
                        <div class="col-md-6">
                          <span>Date début</span>
                          <p>{{moment(offer.date_start)}}</p>
                        </div>
                        <div class="col-md-6">
                          <span>Date fin</span> 
                          <p>{{moment(offer.date_end)}}</p>
                        </div>
                        <div class="col-md-6" v-if="offer.skill">
                          <span>Compétence</span>
                          <p><i v-for='skill in offer.skill.slice(0,3)' v-if="skill.title">{{skill.title}}, </i>..</p>
                        </div>
                        <div class="col-md-6" v-if="offer.technology">
                          <span>Technologies</span>
                          <p><i v-for='technology in offer.technology.slice(0,3)' v-if="technology.title">{{technology.title}}, </i> ..</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="right ">
                    <p>Etat <br>d’avancement </p>
                    <!--<img src="/static/img/percent60.png">-->
                    <div class="percent-holder">
                      <div :class="['c100 p' + offer.progression]">
                        <span>{{offer.progression}}%</span>
                          <div class="slice">
                            <div class="bar"></div>
                            <div class="fill"></div>
                          </div>
                      </div>
                    </div>
                    <a href="javascript:void(0);" class="btn-black">Voir mission</a>
                  </div>
            </li>
          </ul>
          
          
        </div>
        <ul class="pagination">
            <paginate v-show="parseInt(missionslist.finished.last_page)>1"
              :page-count="parseInt(missionslist.finished.last_page)"
              :click-handler="getmissions"
              :prev-text="'Prev'"
              :next-text="'Next'"
              :container-class="'pagination'">
            </paginate>
          </ul>
        <!--<router-link tag="a" class="cursor btn-orange small del" :to="{ name: 'dashboard.missions' }">Supp toutes les missions</router-link>-->
      </div>

    <addmission :showAddPopup="showAddPopup"></addmission>
	</div>
</template>