<template>
    <div class="container page-connexion">
      <div class="content">
        <div class="row">
          <div class="page-connexion-content">
            <div class="left">
              <span>BIENVENU<br>Créer votre propre compte gratuit et rapide</span>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
            </div>
            <div class="right">
              <ul class="tabs">
                <li class="tab-link" :class="{current:currentSignin}">
                  <router-link tag="span" :to="{ name: 'auth.signin' }" class="cursor">Se connecter</router-link>
                </li>
                <li class="tab-link" :class="{current:currentSignup}">
                  <router-link tag="span" :to="{ name: 'auth.signup' }" class="cursor">S'inscrire</router-link>
                </li>
              </ul>
              <div id="tab-1" class="tab-content current">
                <router-view></router-view> 
              </div>
            </div>      
          </div>
        </div>
      </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import { bus } from '../../../plugins/eventbus'
export default {
    data() {
        return {
            email : ''
        }
    },
    computed: {
      ...mapGetters(['currentSignin', 'currentSignup']),
    },
    methods: {
        ...mapActions(['ressetpass', 'setMessage']),
    }
}
</script>


<style lang="css">
</style>