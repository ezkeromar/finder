
<script>
  import { mapActions, mapGetters } from 'vuex'
  import CcSpinner from '../general/spinner'
 
  export default {
    components: {
      CcSpinner,
    },
    computed: {

      ...mapGetters(['currentUser', 'isLogged']),
      version() {
        return version
      },
    },
    watch: {
      isLogged(value) {
        if (value === false) {
          this.$router.push({ name: 'auth.signin' })
        }
      },
    },
    methods: {

      ...mapActions(['logout']),

      navigate(name) {
        switch (name) {
          case 'codecasts':
            window.location = 'https://codecasts.com.br/'
            break;
          case 'logout':
            this.logout()
            break;
          default:
            this.$router.push({ name })
            break;
        }
      },
    },
  }
</script>

<template>
  <div class="container">
    <cc-spinner></cc-spinner>
     <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="/">Blog</a>
          </div>
          <ul class="nav navbar-nav">
            
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li v-show="currentUser.name">
              <a href="#null">
                <span class="glyphicon glyphicon-user"></span> {{ currentUser.name }}
              </a>
            </li>
            <li v-show="!isLogged">
              <router-link :to="{ name: 'auth.signin'}">
                <span class="glyphicon glyphicon-log-in"></span> Login
              </router-link>
            </li>
            <li v-show="isLogged">
              <a @click='logout' href="#null">
                <span class="glyphicon glyphicon-log-in"></span> Logout
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
</template>

<style scoped>
  .cc-navigation {
    padding-left: 115px;
    padding-right: 30px;
  }
  .brand {
    font-size: 1.2em;
  }
  .logout-button {
    float: right;
  }
  .version {
    position: absolute;
    right: 15px;
    top: 65px;
  }
</style>
