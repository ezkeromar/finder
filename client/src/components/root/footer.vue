<script> 
  import { sendContact, newsFront } from '../services'
  export default {
    data() {
      return {
        name: null, 
        email: null, 
        message: null,
        alert_message: null,
        showContactLoading: false,
        news : null,
      }
    },
    components: {
    },
    computed: {
    },
    watch: {
    },
    mounted() {
      this.newsFunc();
    },
    methods: {
      send_contact() {
        var contact = this;
        if(!contact.name && !contact.email && !contact.message) {
          contact.alert_message = "Merci de remplir tous les champs ";
        } else {
          contact.showContactLoading = true;
          sendContact(contact.name, contact.email, contact.message).then(function(response){
            contact.showContactLoading = false;
            contact.name = null;
            contact.email = null;
            contact.message = null;
            contact.alert_message = response.data.message;
          });
        }
      },
      newsFunc() {
        var sr = this;
        newsFront().then(function(response){
            sr.news = response.news;
        });
      }
    },
  }
</script>

<template>
  <footer>
    <div class="container">
      <div class="content">
        <div class="footer-contact col-md-3">
          <span class="title white">Contacts</span>
          <ul>
            <li>
              <span class="icn-adress">Adress</span>
              <p>Lot 316, Lotissement Lina, <br>
              Sidi Maarouf, <br>
              Casablanca, Morocco</p>
            </li>
            <li>
              <span class="icn-phone">Phone</span>
              <p>+212522990120</p>
            </li>
            <li>
              <span class="icn-email">Email</span>
              <p>contact@finder.net</p>
            </li>
          </ul>
        </div>
        <div class="footer-news col-md-4">
          <span class="title white">News</span>
          <ul class="hide">
            <li><a href="javascript:void(0);">At vero eos et accusamus et iusto odio</a></li>
            <li><a href="javascript:void(0);">At vero eos et accusamus et iusto odio</a></li>
            <li><a href="javascript:void(0);">At vero eos et accusamus et iusto odio</a></li>
            <li><a href="javascript:void(0);">At vero eos et accusamus et iusto odio</a></li>
            <li><a href="javascript:void(0);">At vero eos et accusamus et iusto odio</a></li>
          </ul>
          <ul>
            <li v-for="item_news in news"><a href="">{{item_news.title}}</a></li>
          </ul>
        </div>
        <div class="nousecrire col-md-5" :class="{loading:showContactLoading}">
          <span class="title white">Nous écrire</span>
          <form>
            <input required class="nousecrire-input" type="text" placeholder="Nom" v-model="name">
            <input required class="nousecrire-input" required type="email" placeholder="E-mail" v-model="email">
            <textarea required placeholder="Message" v-model="message"></textarea>
            <span class="title white">{{alert_message}}</span>
            <input type="button" class="submit" value="ENVOYER" @click="send_contact">
          </form>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <a href="javascript:void(0);">
        <!--<img src="/static/img/logo-footer.png">-->
        <img src="/static/img/logo-orange.png">
      </a>
    </div>
  </footer>
</template>

<style scoped>
.nousecrire .submit {
    background: #ffae00;
    border: none;
    padding: 10px 25px;
    float: right;
    margin-top: 10px;
    font-size: 16px;
}
</style>