<script>
  import { mapActions, mapGetters } from 'vuex'
  import {ressetPass} from '../../services'
  import hello from 'hellojs'
  import { baseUrl } from '../../../../config'
  import localforage from 'localforage'


  export default {

    data() {
      return {
        email: '',
        password: '',
        showloader:false,
        baseUrlAttr:baseUrl,
        msg_reset_password: '',
      }
    },
    computed: {
         ...mapGetters(['currentUser', 'currentClient'])
    },
    mounted() {
      // this.checkUserToken()
      // if(this.currentUser)
      //   this.$router.push({ name: 'dashboard.index' });

      this.navigate()
    },
    methods: {
      ...mapActions(['attemptLogin', 'setMessage', 'navigateTab', 'checkUserToken']),
      navigate() {
        this.navigateTab(this.$route.name)
      },
      submit(event) {
        event.preventDefault()
        this.showloader = !this.showloader
        const { email, password } = this
        var lg = this;
        this.attemptLogin({ email, password })
          .then(() => {
            lg.setMessage({ type: 'error', message: [] })
            lg.showloader = !lg.showloader; 
            lg.$router.push({ name: 'dashboard.index' })
          }).catch(function (error) {
            lg.showloader = !lg.showloader; 
            swal('Invalid email ou mot de passe')
          })
      },

      reset() {
        this.email = ''
        this.password = ''
      },

      forgotpass() {
          swal({
              title: 'Tapez votre email',
              content: {
                  element: "input",
                  attributes: {
                      placeholder: "E-mail",
                      type: "email",
                  },
              },
          }).then((result) => {
              ressetPass(result)
                .then((data) => {
                  console.log(data);
                  this.setMessage({ type: 'error', message: [] })
                  if(data.data.token != null)
                    this.msg_reset_password = 'Veuillez vérifier votre boîte de réception';
                    //this.$router.push({ name: 'auth.forgotpass', params: {token : data.data.token, email: data.data.email} });
                    // Veuillez vérifier votre boîte de réception
                  else
                      swal({type: 'warning',title:'Email not found!!!'});
                })
          })
      }
    },
  }
</script>

<template>
  <transition
    name="slide-fade"
  >
  <div :class="{loading:showloader}">
    <div class="seconnecter">
      <span class="title black">{{msg_reset_password}}</span>
      <form v-on:submit.prevent="submit">
        <input class="input-text" v-model="email" required type="email" placeholder="Nom d'utilisateur">
        <input class="input-text" v-model="password" required type="password" placeholder="Mot de passe">
        <input type="submit" class="btn-orange cursor" value="Se connecter" />
      </form>
      <a class="forgotpassword-link cursor" @click="forgotpass">Mot de passe oublié ?</a>
      <a :href="baseUrlAttr+'linkedin'" title="LinkedIn" class="btn btn-linkedin btn-lg">
        <i class="fa fa-linkedin fa-fw"></i> LinkedIn
      </a>
      <br/>
      

    </div>
    <ul class="connexion-social">
      <li>
        <a href="javascript:void(0);"><i class="fa fa-facebook-f"></i></a>
      </li>
      <li>
        <a href="javascript:void(0);"><i class="fa fa-twitter"></i></a>
      </li>
      <li>
        <a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a>
      </li>
    </ul>
  </div>
  </transition> 
</template>

<style scoped>
  .slide-fade-enter-active {
    transition: all .8s ease;
  }
  .slide-fade-leave-active {
    transition: all .0s cubic-bezier(1.0, 0.5, 0.8, 1.0);
  }
  .slide-fade-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
  }


  .btn-linkedin {
    background: #0E76A8;
    border-radius: 0;
    color: #fff;
    border-width: 1px;
    border-style: solid;
    border-color: #084461;
  }
  .btn-linkedin:link, .btn-linkedin:visited {
    color: #fff;
    font-family: firesans;
    margin-bottom: 5%;
  }
  .btn-linkedin:active, .btn-linkedin:hover {
    background: #084461;
    color: #fff;
  }

</style>