<template>
	<div>
		<link rel="stylesheet" href="/static/css/homepage.css">
		<link rel="stylesheet" href="/static/css/responsive.css">
		<link rel="stylesheet" href="/static/css/slick.css">

		<header>
			<div class="home-header fluid-container">
				<div style="width: 60%; padding: 1%; font-size: 2em; color: #fff;">
					<div class="col-md-6">
						+212 5 22 55 55 55
					</div>
					<div class="col-md-6">
						<ul>
							<li><a href="javascript:void(0);"><i class="fa fa-envelope"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-facebook"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-linkedin"></i></a></li>
							<li><a href="javascript:void(0);"><i class="fa fa-google-plus"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		
		<div class="fluid-container">
			<div class="content">
				<section id="one" class="main-homepage">
					<div class="homepage-main-content">
						<video autoplay muted loop id="background-video">
							<source src="static/img/background.mp4" type="video/mp4">
						</video>
						<!--<div class="logo-holder">
							<a href="/" class="logo">
								<img src="static/img/logo-white.png">
							</a>
						</div>-->
						<div class="search-holder"> 
							<input type="text" placeholder="RECHERCHE">
							<!--<a href="javascript:void(0);" class="search-btn"></a>-->
							<router-link tag="a" class="search-btn" :to="{ name: 'home.search' }"></router-link>
						</div>
						<div class="btn-holder">
							<!--<a href="javascript:void(0);" class="btn-orange">S’INSCRIRE</a>
							<a href="javascript:void(0);" class="btn-black">SE CONNECTER</a>-->

							<!--<a href="javascript:void(0);" class="btn-orange">S’INSCRIRE</a>
							<a href="javascript:void(0);" class="btn-black">SE CONNECTER</a>-->

							<router-link tag="a" class="btn-orange" :to="{ name: 'auth.signup' }">
				              <span>S’INSCRIRE</span>
				            </router-link>

				            <router-link tag="a" class="btn-black" :to="{ name: 'auth.signin' }">
				              <span>SE CONNECTER</span>
				            </router-link>

						</div>	
					</div>
					<a href="javascript:void(0);" class="scroll-btn animated"></a>
				</section>
				<section class="wanted-news">
					<div class="wanted">
						<div class="wanted-holder">
							<p>The <br>wanted</p>
						</div>
						<ul class="wanted-items">
							<li>
								<div class="img-holder">
									<img src="static/img/wanted1.jpg">
									<div class="wanted-detail">
										<div class="wanted-info">
											<p>Fettah flyou</p>
											<span>Expert</span>
											<p>Compétence</p>
											<span>Java;  Oracle;  CSS;  HTML 5Java;  Oracle;  CSS;  HTML 5Java;  </span>
											<p>Technologies</p>
											<span>Java ; Oracle; CSS; HTML 5</span>
											<div class="wanted-stars">
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
											</div>
										</div>
										<a href="/dashboard/search?term=" class="wanted-btn">Consulter profile</a>
									</div>
								</div>
							</li>
							<li>
								<div class="img-holder">
									<img src="static/img/wanted1.jpg">
									<div class="wanted-detail">
										<div class="wanted-info">
											<p>Fettah flyou</p>
											<span>Expert</span>
											<p>Compétence</p>
											<span>Java;  Oracle;  CSS;  HTML 5Java;  Oracle;  CSS;  HTML 5Java;  </span>
											<p>Technologies</p>
											<span>Java ; Oracle; CSS; HTML 5</span>
											<div class="wanted-stars">
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
											</div>
										</div>
										<a href="/dashboard/search?term=" class="wanted-btn">Consulter profile</a>
									</div>
								</div>
							</li>
							<li>
								<div class="img-holder">
									<img src="static/img/wanted1.jpg">
									<div class="wanted-detail">
										<div class="wanted-info">
											<p>Fettah flyou</p>
											<span>Expert</span>
											<p>Compétence</p>
											<span>Java;  Oracle;  CSS;  HTML 5Java;  Oracle;  CSS;  HTML 5Java;  </span>
											<p>Technologies</p>
											<span>Java ; Oracle; CSS; HTML 5</span>
											<div class="wanted-stars">
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
											</div>
										</div>
										<a href="/dashboard/search?term=" class="wanted-btn">Consulter profile</a>
									</div>
								</div>
							</li>
							<li>
								<div class="img-holder">
									<img src="static/img/wanted1.jpg">
									<div class="wanted-detail">
										<div class="wanted-info">
											<p>Fettah flyou</p>
											<span>Expert</span>
											<p>Compétence</p>
											<span>Java;  Oracle;  CSS;  HTML 5Java;  Oracle;  CSS;  HTML 5Java;  </span>
											<p>Technologies</p>
											<span>Java ; Oracle; CSS; HTML 5</span>
											<div class="wanted-stars">
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
											</div>
										</div>
										<a href="/dashboard/search?term=" class="wanted-btn">Consulter profile</a>
									</div>
								</div>
							</li>
							<li>
								<div class="img-holder">
									<img src="static/img/wanted1.jpg">
									<div class="wanted-detail">
										<div class="wanted-info">
											<p>Fettah flyou</p>
											<span>Expert</span>
											<p>Compétence</p>
											<span>Java;  Oracle;  CSS;  HTML 5Java;  Oracle;  CSS;  HTML 5Java;  </span>
											<p>Technologies</p>
											<span>Java ; Oracle; CSS; HTML 5</span>
											<div class="wanted-stars">
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
											</div>
										</div>
										<a href="/dashboard/search?term=" class="wanted-btn">Consulter profile</a>
									</div>
								</div>
							</li>
							<li>
								<div class="img-holder">
									<img src="static/img/wanted1.jpg">
									<div class="wanted-detail">
										<div class="wanted-info">
											<p>Fettah flyou</p>
											<span>Expert</span>
											<p>Compétence</p>
											<span>Java;  Oracle;  CSS;  HTML 5Java;  Oracle;  CSS;  HTML 5Java;  </span>
											<p>Technologies</p>
											<span>Java ; Oracle; CSS; HTML 5</span>
											<div class="wanted-stars">
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
												<span class="active"><i class="fa fa-star"></i></span>
											</div>
										</div>
										<a href="/dashboard/search?term=" class="wanted-btn">Consulter profile</a>
									</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="news hide">
						<div class="news-holder">
							<p>What’s<br>News ?</p>
						</div>
						<div class="news-items-holder">
							<ul class="news-items">
								<li class="news-item" v-for="item_news in news">
									<div class="img-holder">
										<img :src="baseUrlAttr+item_news.picture"> 
									</div>
									<div class="news-item-content">
										<p>{{item_news.title}}</p>
										<span>{{item_news.description}}</span>
									</div><!-- 
									<a href="javascript:void(0);" class="link"></a> -->
								</li>
								<li class="news-item">
									<div class="img-holder">
										<img src="static/img/slider1.jpg"> 
									</div>
									<div class="news-item-content">
										<p>Lorem ipsum Dolors Inscriptions</p>
										<span>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum </span>
									</div><!-- 
									<a href="javascript:void(0);" class="link"></a> -->
								</li>
								<li class="news-item">
									<div class="img-holder">
										<img src="static/img/slider1.jpg"> 
									</div>
									<div class="news-item-content">
										<p>Lorem ipsum Dolors Inscriptions</p>
										<span>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum </span>
									</div><!-- 
									<a href="javascript:void(0);" class="link"></a> -->
								</li>
							</ul>
						</div>
					</div>	
					


					<div class="news">
						<div class="news-holder">
							<p>What’s<br>News ?</p>
						</div>
						<div class="news-items-holder home-offers">
							<ul>
					            <li v-if="missionslist" v-for="offer in missionslist">
					                  <div class="left">
					                    <div class="left-holder">
					                      <div class="row offre-content">
					                        <div class="col-md-12">
					                        	<span>{{ short_text(offer.title, max_title)}}</span>
                          						<p>{{ short_text(offer.description, max_desc) }}</p>
					                        </div>
					                        <div class="col-md-4">
					                          <span>Date début</span>
					                          <p>{{moment(offer.date_start)}}</p>
					                        </div>
					                        <div class="col-md-4">
					                          <span>Durée</span> 
					                          <p>{{offer.duration}} jours</p>
					                        </div>
					                        <div class="col-md-4">
					                          <span>Ville</span>
					                          <p>{{offer.city['title']}}</p>
					                        </div>
					                        <div class="col-md-4" v-if="offer.skill">
					                          <span>Compétence</span>
					                          <p><i v-for='skill in offer.skill.slice(0,3)' v-if="skill.title">{{skill.title}}, </i>..</p>
					                        </div>
					                        <div class="col-md-4" v-if="offer.technology">
					                          <span>Technologies</span>
					                          <p><i v-for='technology in offer.technology.slice(0,3)' v-if="technology.title">{{technology.title}}, </i> ..</p>
					                        </div>
					                        <div class="col-md-4">
					                          <span></span>
					                          <p></p>
					                        </div>
					                      </div>
					                    </div>
					                  </div>
					            </li>
					          </ul>
					          <router-link tag="a" class="btn-black link-offers" :to="{ name: 'dashboard.search' }">Voir toutes les offres</router-link>
						</div>
					</div>



					<a href="javascript:void(0);" class="scroll-btn animated"></a>				
				</section>
				<div class="services">
					<div class="row">
						<div class="col-md-4">
							<div class="service">
								<p>Consultants</p>
								<span>3329</span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="service">
								<p>Missions</p>
								<span>30</span>
							</div>
						</div>
						<div class="col-md-4">
							<div class="client">
								<p>Clients</p>
								<span>129</span>
							</div>
						</div><!-- 
						<div class="col-md-6">
							<div class="souscring">
								<p>Souscring</p>
								<div>En<span>3</span><br>cliques</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="commission">
								<p>Commission</p>
								<div>de<br><span>5</span>%</div>
							</div>
						</div> -->
					</div>
				</div>
				<section id="three">
					<iframe src="https://www.youtube.com/embed/AOEk4FQKXxk" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
				</section>
				<section id="four" class="securite">
					<span class="title white big">Votre securite est <br>notre priorite</span>
					<div class="row">
						<div class="col-md-4">
							<span class="title orange">Gestion</span>
							<ul>
								<li>CNSS</li>
								<li>IGR</li>
								<li>Bulletin de paie</li>
							</ul>
						</div>
						<div class="col-md-4">
							<span class="title orange">Paiement</span>
							<ul>
								<li>Instantané à j+15 du<br>
								dépôt de la facture</li>
							</ul>
						</div>
						<div class="col-md-4">
							<span class="title orange">Assurance</span>
							<ul>
								<li>Risques travail</li>
								<li>Risques exploitations</li>
								<li>Risques maladies</li>
							</ul>
						</div>
					</div>
				</section>
				<section id="five" class="partners">
					<span class="title big orange">Nos<br>Partenaires</span>
					<ul>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner1.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner2.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner3.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner4.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner5.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner6.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner7.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner8.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner9.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner10.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner11.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner12.png">
							</a>
						</li>
						<li>
							<a href="javascript:void(0);">
								<img src="static/img/partners/partner13.png">
							</a>
						</li>
					</ul>
				</section>
			</div>
		</div>
		
	</div>

</template>
<script>
	import { newsFront, getHomeMissions } from "../../services.js"
  import { mapActions, mapGetters } from 'vuex'
  import { baseUrl } from '../../../../config'
  import moment from 'moment'
  export default { 
	data() {
      return {
      	news : null,
      	baseUrlAttr : baseUrl,
      	missionslist: null,
      	max_desc: 100,
        max_title: 45,
      	}
      },

      computed: {
         ...mapGetters(['token', 'currentClient'])
      },
      mounted() {

      	this.newsFunc();
      	this.getmissions();
		

      	$(document).ready(function(){
		    $('section, .wanted, .news, .wanted-items, .news-items, .news-item, .news-items-holder').css('height', $(window).height());
		});
		$(window).resize(function(){
		    $('section, .wanted, .news, .wanted-items, .news-items, .news-item, .news-items-holder').css('height', $(window).height());
		});
		$(document).ready(function(){
		    $('.wanted-items li').css('height', $(window).height() / 2);
		});
		$(window).resize(function(){
		    $('.wanted-items li').css('height', $(window).height() / 2);
		});
		$(".main-homepage .scroll-btn").click(function() {
		    $('html, body').animate({
		        scrollTop: $(".wanted-news").offset().top //services
		    }, 1000);
		}); 
		$(".wanted-news .scroll-btn").click(function() {
		    $('html, body').animate({
		        scrollTop: $(".services").offset().top
		    }, 1000);
		    $('.services .col-md-4 span').each(function () {
			    $(this).delay(200).prop('Counter',0).animate({
			        Counter: $(this).text()
			    }, {
			        duration: 2000,
			        easing: 'swing',
			        step: function (now) {
			            $(this).text(Math.ceil(now));
			        }
			    });
			});
		});
		$('.wanted-holder').click(function(){
		    $(this).parent().toggleClass('zindex-plus');
		    $(this).parent().siblings().toggleClass('zindex-minus');
		    $(this).parent().toggleClass('active');
		})
		let x = (jQuery(document).width() * 60) / 100
		$('.news-items-holder').css({'width' : x})
		$('.news-holder').click(function(){
			$(this).parent().toggleClass('zindex-plus');
			$(this).parent().siblings().toggleClass('zindex-minus');
		    $(this).parent().toggleClass('active');
		    if ($(".news-items-holder").hasClass("hide-load")) {
		        $(this).removeClass('hide-load')
		    }
		})
		$('.news-items').slick({
		    infinite: true,
		    slidesToShow: 1,
		    slidesToScroll: 1,
		    arrows: false,
		    variableWidth: false,
		    autoplay: true,
		    dots: true,
		    autoplaySpeed: 2000
		});
		$('.partners ul').slick({
		    infinite: true,
		    slidesToShow: 4,
		    slidesToScroll: 1, 
		    variableWidth: false,
		    autoplay: true,
		    dots: false,
		    autoplaySpeed: 1500,
		    pauseOnHover: false,
		    responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow: 1,
		      }
		    }]
		});
		/*
		$(window).bind("load", function() {
		    setTimeout(function() {
		        $('body').removeClass('load');
		    }, 1000);
		});*/

	},
	methods: {
		getmissions() {
    		var offer = this;
          	getHomeMissions().then(function(response) {
	            offer.missionslist = response.missions;
	            //console.log('mission list : ');
	            //console.log(offer.missionslist.current);
	        })
    	},
    	moment: function (date) {
          return moment(date).format('DD / MM / YYYY');
        },
      	newsFunc() {
	        var sr = this;
	        newsFront().then(function(response){
	            sr.news = response.news;
	        });
	    },	    
        short_text: function (word, max_text) {
          if(word.length < max_text)
            return word;
          else if(word.length >= max_text)
            return word.substring(0, max_text) + "..";
        },
	}
}
</script>