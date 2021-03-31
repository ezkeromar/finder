<script>
  import { mapActions } from 'vuex'
  import {updatePass} from '../../services'
  export default {

    data() {
      return {
        password: '',
        repeat_password: '',
        token: '',
      }
    },
    mounted() {
      this.token = this.$route.params.token;
      this.navigate()
    },
    methods: {

      ...mapActions(['attemptLogin', 'setMessage', 'navigateTab']),
      navigate() {
        this.navigateTab(this.$route.name)
      },
      submit() {
        const { repeat_password, password, token } = this
        updatePass(password, token) 
          .then(() => {
            this.setMessage({ type: 'error', message: [] }) 
            this.$router.push({ name: 'dashboard.index' })
          })
      },


    },
  }
</script>

<template>
  <div>
    <div class="seconnecter ">
      <input class="input-text" v-model="password" type="password" placeholder="Mot de passe">
      <input class="input-text" v-model="repeat_password" type="password" placeholder="Répetez mot de passe">
      <button class="btn-orange cursor" @click="submit">Envoyer</button>
      <router-link tag="a" class="forgotpassword-link cursor" :to="{ name: 'auth.signin' }">Revenir pour s'identifier</router-link>
    </div>
    <div class="seconnecter hide">
      <span class="title black">Veuillez vérifier votre boîte de réception</span>
      <router-link tag="a" class="forgotpassword-link cursor" :to="{ name: 'auth.signin' }">Revenir pour s'identifier</router-link>
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
</template>
