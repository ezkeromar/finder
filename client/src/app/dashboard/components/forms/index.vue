<script>
import { mapActions, mapGetters } from 'vuex'
import { loadClient, deleteUser, loadConsultent } from '../../services.js'
import { baseUrl, uploadUrl } from '../../../../config'
import moment from 'moment'
import AddUser from './AddUser.vue'
import UpdateClient from './UpdateClient.vue'
import mission_detail from './modals/mission_detail.vue'
import { bus } from '../../../../plugins/eventbus'
  export default {
    data() {
      return {
        clientInfo: null,
        consultentInfo: null, 
        baseUrlAttr: baseUrl,
        uploadUrlAttr: uploadUrl,
        selected_mission: null,
        max_desc: 100,
        max_title: 45,
      }
    },
    computed: {
      ...mapGetters(['currentUser', 'currentClient']),
    },
    mounted() {
      $(".input-file").change(function () {
        $(".input-file").each(function() {
          var fileName = $(this).val().split('/').pop().split('\\').pop();
          $(".input-file-holder span").empty();
          $( ".input-file-holder span" ).append(fileName);
        });
      });
      this.navigate()
      this.loadInfo()
      bus.$on('user.added', ($event) => {
        swal('Utilisateur ajouté avec success');
        this.loadInfo();
      })
      bus.$on('client.updated', ($event) => {
        swal('Information modifié avec success');
        this.loadInfo();
      })
    },
    methods: {
      ...mapActions(['navigateTab']),      
      moment: function (date) {
        return moment(date).format('DD / MM / YYYY');
      },
      short_text: function (word, max_text) {
        if(word.length < max_text)
          return word;
        else if(word.length >= max_text)
          return word.substring(0, max_text) + "..";
      },
      deleteuser(id) {
        if(this.currentUser.id != id) {
          var that = this;
          deleteUser(id).then(function(response) {
            swal('Utilisateur suprimé avec success')
            that.loadInfo();
          });
        } else {
          swal('Vous ne pouvez pas suprimer votre compte')
        }
      },
      loadInfo(pageNum = 1) {
        var pf = this;
        if(this.currentClient) {
          loadClient(this.currentUser.id, this.currentClient.id).then(function(response) {
            pf.clientInfo = response.client_info;
            
          });
        } else {
          loadConsultent(this.currentUser.id, pageNum).then(function(response) {
            //console.log(response.consultent_info);
            pf.consultentInfo = response.consultent_info;
          });
        }
      },
      loadFinishedMissions(pageNum = 1) {
        var pf = this;
        loadConsultent(this.currentUser.id, pageNum).then(function(response) {
            //console.log(response.consultent_info);
            pf.consultentInfo = response.consultent_info;
          });
      },
      showAddUserPopFunc() {
        $('body').addClass('no-scroll');
        bus.$emit('user.add.pop');
      },
      showUpdateClientPopFunc() {
        $('body').addClass('no-scroll');
        bus.$emit('update.client.pop');
      },
      showDetailMission(mission) {
        //$('body').addClass('no-scroll');
        this.selected_mission = mission;
        console.log('selected mission ');
        console.log(mission);
        bus.$emit('show.mission');
      },
      navigate() { 
        this.navigateTab(this.$route.name)
      },
    },
    components:{
      'add-user':AddUser,
      'update-client':UpdateClient,
      'MissionDetail' : mission_detail,
    },
  }
</script>

<template>
<div>
  <div v-if="clientInfo" class="container page-profile">
    <div class="content row">
      <div class="col-md-3">
        <div class="sidebar sidebar-profile">
          <div class="profile-avatar">
            <div class="img-holder">
              <img v-if="clientInfo.logo" :src="baseUrlAttr+clientInfo.logo">
            </div>
          </div>
          <span class="profile-username">{{clientInfo.name}}</span>
          <p v-if="clientInfo.city">{{clientInfo.city['title']}}</p>
          <p class="profile-email">{{clientInfo.email}}</p>
          <p class="profile-phone">{{clientInfo.phone}}</p>
          <a @click="showUpdateClientPopFunc" class="sidebar-profile-btn cursor">Modifier coordonnées</a>
        </div>
      </div>
      <div class="page-content col-md-9">
        <section class="mes-utilisateurs">
          <span class="title black">Mes utilisateurs</span>
          <div class="row">
            <div v-if="clientInfo.users" v-for="user in clientInfo.users" class="col-md-4">
              <div class="utilisateur">
                <div class="img-holder">
                  <img v-if="user['logo']" :src="baseUrlAttr+user['logo']">
                </div>
                <a href="javascript:void(0);" class="utilisateur-name">{{user['firstname']}} {{user['lastname']}}</a>
                <span>{{user.profession}}</span>
                <a v-if="currentUser.is_admin == 1" @click="deleteuser(user['id'])" class="cursor delete-utilisateur"></a>
              </div>
            </div>  
          </div>
        </section>
        <a class="btn-orange small plus cursor" @click="showAddUserPopFunc">Ajouter un utilisateur</a>
        <section class="mesoffres">
          <span class="title black">Mes offres en cours</span>
          <ul>
            <li v-if="clientInfo.missions" v-for="offer in clientInfo.missions">
              <div class="img-holder">
                <div class="img">
                  <img v-if="clientInfo.logo" :src="baseUrlAttr+clientInfo.logo">
                </div>
              </div>
              <div class="row offre-content">
                <div class="col-md-12">
                  <span>{{offer.title}}</span>
                  <p>{{offer.description}}</p>
                </div>
                <div class="col-md-12">
                  <span>Date début</span>
                  <p>{{offer.date_start}}</p>
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
            </li>
          </ul>

        </section>
        <router-link tag="a" :to="{ name: 'dashboard.missions' }" class="btn-orange small plus cursor">Voir toutes les offres</router-link>
      </div>
    </div>
  </div>
  <div v-if="consultentInfo" class="container page-profile">
    <div class="content row">
      <div class="col-md-3">
        <div class="sidebar sidebar-profile">
          <div class="profile-avatar">
            <div class="img-holder">
              <img v-if="consultentInfo.picture" :src="baseUrlAttr+consultentInfo.picture">
            </div>
          </div>
          <span class="profile-username">{{consultentInfo.firstname}} {{consultentInfo.lastname}}</span>
          <span>{{consultentInfo.profession}}</span>
          <p v-if="consultentInfo.city">{{consultentInfo.city['title']}}</p>
          <p class="profile-email">{{consultentInfo.email}}</p>
          <p class="profile-phone">{{consultentInfo.phone}}</p><br/><br/><br/><br/>
          <span>TJM</span>
          <p>{{consultentInfo.tjm}} DHs</p>
          <span>Date disponibilité</span>
          <p>{{moment(consultentInfo.disponibility_date)}}</p>
          <span>Compétence</span>
          <p><i v-for='skill in consultentInfo.skill' v-if="skill.title">{{skill.title}}, </i></p>
          <span>Technologies</span>
          <p><i v-for='technology in consultentInfo.technology' v-if="technology.title">{{technology.title}}, </i></p>
          <div class="siderbar-btns">
            <router-link tag="a" class="btn-grey" :to="{ name: 'dashboard.profile.edit' }">
              Voir mon CV
            </router-link>
            <router-link tag="a" class="btn-edit" :to="{ name: 'dashboard.profile.edit' }"></router-link>
          </div>
        </div>
      </div>
      <div class="page-content col-md-9">
        <!-- mission current -->
        <div v-if="consultentInfo.missions_current" class="mesoffres consultant">
          <span class="title black">Mes missions en cours</span>
          <ul>
            <li v-if="consultentInfo.missions_current" v-for="offer in consultentInfo.missions_current">
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
                    <a @click="showDetailMission(offer)" class="btn-black cursor">Voir mission</a>
                  </div>
            </li>
          </ul>
        </div>
        
        <!-- mission finished -->
        <div v-if="consultentInfo.missions_finished" class="mesoffres consultant">
          <span class="title black">Mes missions complétes</span>
          <ul>
            <li class="inactive" v-if="consultentInfo.missions_finished" v-for="offer in consultentInfo.missions_finished.data">
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
                  <div class="right">
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
                    <a @click="showDetailMission(offer)" class="btn-black cursor">Voir mission</a>
                  </div>
            </li>
          </ul>
        </div>
        <paginate v-show="parseInt(consultentInfo.missions_finished.last_page)>1"
            :page-count="parseInt(consultentInfo.missions_finished.last_page)"
            :click-handler="loadFinishedMissions"
            :prev-text="'Prev'"
            :next-text="'Next'"
            :container-class="'pagination'">
          </paginate>
        <router-link tag="a" class="cursor btn-orange small plus" :to="{ name: 'dashboard.missions' }">Voir toutes les offres</router-link>
      </div>
    </div>
  </div>
  <add-user></add-user>
  <update-client></update-client>
  <MissionDetail :mission="selected_mission" :baseUrlAttr="baseUrlAttr"></MissionDetail>
</div>
</template>
