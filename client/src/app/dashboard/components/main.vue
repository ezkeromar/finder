<template>
    <div class="main-content">
		<router-view></router-view>
    </div>
</template>

<script>
import { bus } from '../../../plugins/eventbus'
export default {
    data() {
        return {

        }
    },
    mounted() {
    	bus.$emit('show.navbar');
    },
    computed: {
    },
    methods: {
    }
}
</script>


<style lang="css">
</style>