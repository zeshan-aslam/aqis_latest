<template>
    <!-- <div> -->
    <!-- <div class="select md-menu md-select"> -->
    <select
        class="select md-menu md-select"
        data-placeholder="Choose a City..."
        v-bind:value="value"
        v-on:input="$emit('input', $event.target.value)"
    >
        <option value="" disabled selected>Choose a city...</option>
        <option v-for="city in getCities" :value="city.city" :key="city.id">
            {{ city.city }}
        </option>
    </select>
    <!-- </div> -->
    <!-- </div> -->
</template>

<script>
import { mapGetters } from 'vuex';
    export default {
        props: ["value"],
        computed: {
        // mix the getters into computed with object spread operator
        ...mapGetters([
        'getCities',
        ])
    },

        created() {
            this.fetchData()
        },
        data() {
            return {
                cities: []
            }
        },
        methods: {
            fetchData(){
          if(this.getCities.length ==0)
                axios.get('/api/getCities')
                    .then(response => {
                        this.$store.commit('SET_CITIES', response.data)
                        this.cities = response.data
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            }
        },
    };
</script>
