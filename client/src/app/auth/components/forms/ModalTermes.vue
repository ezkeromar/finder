<script>
	import { mapGetters } from 'vuex'
import { bus } from '../../../../plugins/eventbus'
	export default {
		data() {
			return {
				showTermes: false,
			}
		},
      	mounted() {
      		bus.$on('show.termes', ($event) => {
	            this.showTermes = !this.showTermes;
	            $('<div class="number-button number-up">+</div><div class="number-button number-down">-</div>').insertAfter('.number input');			    
	        })
      	},
      	watch: {
      	},
      	computed: {},
      	methods: {
	      	close() {
      			$('body').removeClass('no-scroll');
      			bus.$emit('show.termes');
      		},
      	},
	    components:{},
    }
</script>

<template>
	<div v-show="showTermes" class="popup active">
		<div class="popup-container popup-addCV active" >
			<div class="popup-content popup-consultant-content">
				<div class="full-label">
					<span class="title black">Termes générals d'utilisation </span>
					<p><i>Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un peintre anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker.</i></p>
				</div>
			</div>
			<a @click="close" class="popup-close cursor"><i class="fa fa-times"></i></a>
		</div>
	</div>
</template>