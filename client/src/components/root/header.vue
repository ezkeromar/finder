  <style type="text/css">
    .notif_number {
        display: block;
        position: absolute;
        top: 7px;
        left: 14px;
        font-size: 10px;
        color: #FFF;
        width: 16px;
        height: 16px;
        line-height: 16px;
        text-align: center;
        border-radius: 50%;
        background: red;
    }
    .header_client .top-header, .header_client .header-social li a, .header_client .header, .header_client .login-logout {
      color: #ffae00;
      background: #000;
    }
  </style>
  <script> 
  import { mapGetters, mapActions } from 'vuex'
  import { getNotifications, readNotifications } from '../services'
  import { bus } from '../../plugins/eventbus'
  export default {
    components: {
    },
    computed: {
      ...mapGetters(['currentUser', 'currentClient', 'isLogged']),
    },
    watch: {
      isLogged(value) {
        if (value === false) {
          this.$router.push({ name: 'auth.signin' })
        }
      },
    },
    created: function() {
        this.loadNotifications();
        // 1000 = 1 seconde
        //this.timer = setInterval(this.loadNotifications, 60000) 
        //this.timer = setInterval(this.loadNotifications, 1000) 
    },  
    methods: {
      ...mapActions(['logout']),

      ebAddoffer() {
        if(this.$route.name != 'dashboard.missions') {
          this.$router.push({ name: 'dashboard.missions', query: {addpopup : 1} })
        } else {
          bus.$emit('show.offer.popup')
        }
      },

      logoutfunc() {
        this.logout()
      },
      search() {
        if(this.showSearchInput == false) {
          this.showSearchInput=!this.showSearchInput
        } else {
          this.showSearchInput=!this.showSearchInput
          if(this.searchTerm != '')
            this.$router.push({ name: 'dashboard.search', query: {term : this.searchTerm} });
          else
            this.$router.push({ name: 'dashboard.search', query: {term : ''} });
        }
      },
      loadNotifications() {
        var hd = this;
        var type_profile = 'consultant';
        var user_id = this.currentUser.id;
        if(this.currentClient) {
          type_profile = 'client';
          user_id = this.currentClient.id;
        }
        //console.log(this.currentUser.id);
        if(this.currentUser.id != undefined) {
          getNotifications(user_id, type_profile).then(function(response){
            hd.notifications = response.notifications;
            hd.notif_count = response.notif_count;
            //console.log(response);
          });
        }
      },
      notification_read() {
        //console.log('notification_read = ' + this.currentUser.id);
        if(this.currentUser.id != undefined) {
          readNotifications(this.currentUser.id).then(function(response){
            //console.log(response);
          });
        }
      },
      logged_redirection() {
        var profil = this;
        console.log('==============> test header infos : <==============');
        console.log('user id = ' + profil.currentUser.id);
        console.log(this.currentUser);
        console.log(this.currentClient);
        console.log('route name : ' + this.$route.name);
        if(this.$route.name == 'home.index' && profil.currentUser.id != undefined) {
          console.log('====[ connecté user ]====');
          // 
          //profil.$router.push({ name: 'dashboard.missions'})
        } else {
          console.log('====[ non connecté ]====');
        }
        console.log('=====================================================');
      }
    },
    mounted() {
      //this.loadNotifications();
      //this.logged_redirection();

      $( ".alert-icon" ).click(function() {
        console.log('alert click from mounted function');          
        $('.alert-menu').toggleClass('active');
      });
      bus.$on('show.navbar', ($event) => {
          this.showNave = true;
      })
      /*if(this.currentClient)
        console.log('client side 2'); 
      else 
        console.log('consultant side');*/
    },
    data() {
      return  {
        showNave:false,
        showSearchInput: false,
        notifications: {},
        notif_count: 0,
        timer: '',
        searchTerm: '',
        agenda:true,
        account:false,
      }
    }
  }
</script>

<template>
  <header v-if="this.$route.name != 'home.index' && this.$route.name != 'home.search'" v-bind:class="[(currentClient != null) ? 'header_client' : '']">
    <div class="top-header">
      <div class="container">
        <div class="content">
          <div class="row">
            <ul class="col-md-3 col-md-offset-9 header-social">
              <li>
                <a href="javascript:void(0);"><i class="fa fa-envelope" aria-hidden="true"></i></a>
              </li>
              <li>
                <a href="javascript:void(0);"><i class="fa fa-facebook"></i></a>
              </li>
              <li>
                <a href="javascript:void(0);"><i class="fa fa-twitter"></i></a>
              </li>
              <li>
                <a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a>
              </li>
              <li>
                <a href="javascript:void(0);"><i class="fa fa-google"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="header">

      <div class="container">
        <div class="content">
          <div class="row">
            <router-link tag="a" class="logo" :to="{ name: 'dashboard.index' }">
              <img v-if="currentClient != null" src="/static/img/logo-orange.png">
              <img v-else src="/static/img/logo.png">
            </router-link>
            <a href="/" class="logo hide">
              <img v-if="currentClient != null" src="/static/img/logo-orange.png">
              <img v-else src="/static/img/logo.png">
            </a>
            <div class="alert-login">
              <a href="javascript:void(0);" class="cursor login-logout alert-icon" @click="notification_read" >
                <!--<img v-if="currentClient != null" src="/static/img/alert-orange.png">
                <img v-else src="/static/img/alert.png">-->
                <i class="fa fa-bell" aria-hidden="true"></i>
                <!--<img src="/static/img/icons/alert.png">-->
                <span v-if="notif_count" class="notif_number" style="">{{notif_count}}</span>
              </a>                
              <ul class="alert-menu">
                    <li  v-for="notification in notifications" >
                      <a class="active" href="javascript:void(0);">{{notification.description}}</a>
                    </li>
                    <a href="javascript:void(0);" class="alert-btn">Afficher toutes les notifications</a>
              </ul>
              <a @click="logoutfunc" v-if="isLogged" class="cursor login-logout">
                <!--<img src="/static/img/icons/logout.png">-->
                <!--<img v-if="currentClient != null" src="/static/img/logout-orange.png">-->
                <!--<img v-else src="/static/img/logout.png">-->
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Déconnexion
              </a>
              <router-link v-else  class="cursor login-logout" :to=" { name:'auth.signin' } ">
                <!--<img src="/static/img/icons/logout.png">-->
                <i class="fa fa-sign-in" aria-hidden="true"></i>
                Connexion
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="isLogged && showNave && currentClient != {}" class="nav-search">
      <nav>
        <ul>
          <li>                   
            <router-link tag="a" class="cursor link-compte" :class="{current:this.$router.name == 'dashboard.index'}" :to="{ name: 'dashboard.index' }">
              <span>Mon compte</span>
            </router-link>
          </li>
          <li>
            <router-link tag="a" class="cursor link-agenda" :class="{current:this.$router.name == 'dashboard.agenda'}" :to="{ name: 'dashboard.agenda' }">
              <span>Mon agenda</span>
            </router-link>
          </li>
          <li v-if="currentClient != null">
            <router-link tag="a" class="cursor link-offre" :class="{current:this.$router.name == 'dashboard.missions'}" :to="{ name: 'dashboard.missions' }">
              <span>Mes offres</span>
            </router-link>
            <ul class="sub-menu">
              <li>
                <a @click='ebAddoffer' class="cursor">Nouvelle Offre</a>
              </li>
              <li>
                <router-link tag="a" class="cursor" :to="{ name: 'dashboard.missions' }">Status</router-link>
              </li>
              <li>
                <router-link tag="a" class="cursor link-offre" :to="{ name: 'dashboard.missions', query: {archive : 1} }">Historique</router-link>
              </li>
            </ul>
          </li>
          <li v-if="currentClient == null">
            <router-link tag="a" class="cursor link-offre" :class="{current:this.$router.name == 'dashboard.missions'}" :to="{ name: 'dashboard.missions' }">
              <span>Mes missions</span>
            </router-link>
          </li>
          <li>
            <router-link tag="a" class="cursor link-usage" :class="{current:this.$router.name == 'dashboard.assistance'}" :to="{ name: 'dashboard.assistance' }">
              <span>Assistance</span> 
            </router-link>
          </li>
        </ul>
      </nav>
      <div class="search" :class="{active:showSearchInput}">
        <a href="javascript:void(0);" class="cursor search-icon" @keyup.enter="search" @click="search"></a>
        <input type="text" placeholder="Type to search..." v-show="showSearchInput" v-on:keyup.enter="search" v-model="searchTerm">
      </div>
    </div> 
  </header>
</template>

<style scoped>
</style>