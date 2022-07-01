<template>
    <transition name="modal">
        <div class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Add Outcome</p>
                    <button class="delete" aria-label="close" @click="close"></button>
                </header>
                <section class="modal-card-body">
                    <form method="post" @submit.prevent="saveItem()" id="addOutcomeForm" @keydown="form.errors.clear($event.target.name)">

                        <!-- <div class="md-layout">
                        <label class="md-layout-item md-size-15 md-form-label">
                          Outcome
                        </label>
                        <div class="md-layout-item">
                          <md-field>
                            <md-input name="outcome" v-model="form.outcome" type="text"></md-input>
                          </md-field>
                          <span class="help is-danger" v-if="form.errors.has('outcome')"
                            v-text="form.errors.get('outcome')"></span>
                        </div>
                      </div> -->

                        <!-- <div class="md-layout">
                          <label class="md-layout-item md-size-15 md-form-label">
                          Status
                        </label>
                          <div class="md-layout-item">
                              <md-radio name="status" v-model="form.status" :value=1 @change="form.errors.clear('status')">Active</md-radio>
                              <md-radio name="status" v-model="form.status" :value=0 @change="form.errors.clear('status')">Inactive</md-radio>
                              <span class="help is-danger" v-if="form.errors.has('status')"
                            v-text="form.errors.get('status')"></span>
                          </div>
                        </div> -->

                        <div class="md-layout-item md-small-size-100 md-size-100">
                            <md-field>
                                <div class="md-field md-theme-default md-has-value">
                                    <label for="serviceProvided">Status</label>
                                    <select class="select md-menu md-select" v-model="form.status"
                                        name="serviceProvided" id="serviceProvided">
                                        <option v-for="(status, index) in statuses.items" :key="index" :value="status.item">
                                            {{ status.item }} </option>
                                    </select>
                                     <span class="help is-danger" v-if="form.errors.has('status')"
                            v-text="form.errors.get('status')"></span>
                                </div>
                            </md-field>
                            <span class="help is-danger" v-if="form.errors.has('serviceProvided')"
                                v-text="form.errors.get('serviceProvided')"></span>
                        </div>
                        <div class="md-layout-item md-small-size-100 md-size-100">
                            <md-field>
                                <div class="md-field md-theme-default md-has-value">
                                    <label for="serviceProvided">Category</label>
                                    <select class="select md-menu md-select" v-model="form.category_id"
                                    @change="outcomes()"
                                        name="serviceProvided" id="serviceProvided">
                                        <option v-for="(category, index) in categories" :key="index" :value="category.id">
                                            {{ category.name }} </option>
                                    </select>
                                </div>
                            </md-field>
                            <span class="help is-danger" v-if="form.errors.has('serviceProvided')"
                                v-text="form.errors.get('serviceProvided')"></span>
                        </div>

                        <div class="md-layout-item md-small-size-100 md-size-100">
                            <md-field>
                                <div class="md-field md-theme-default md-has-value">
                                    <label for="serviceProvided">Outcomes</label>
                                    <select class="select md-menu md-select" v-model="form.outcome_id"
                                        name="serviceProvided" id="serviceProvided">
                                        <option v-for="(outcome, index) in outcomes_data" :key="index" :value="outcome.id">
                                            {{ outcome.outcome }} </option>
                                    </select>
                                </div>
                            </md-field>
                            <span class="help is-danger" v-if="form.errors.has('serviceProvided')"
                                v-text="form.errors.get('serviceProvided')"></span>
                        </div>

                    </form>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" type="submit" form="addOutcomeForm">
                        <span>Save</span>
                        <span class="icon is-small">
                            <i class="far fa-save"></i>
                        </span>
                    </button>
                    <button class="button" @click="close">Cancel</button>
                </footer>
            </div>
        </div>
    </transition>
</template>

<script>
import Form from "../../Forms.js";

export default {
  name: "AddOutcomeModal",

  beforeMount() {
      this.outcomes()
  },

  computed: {
    categories() {
        return this.$store.state.categories
    },

    // outcomes() {
    //     let outcomes = this.$store.state.dropdowns.find(function (el) {
    //         return el.name === "OutComes"
    //     })
    //     return outcomes
    // },
    statuses() {
        let outcomes = this.$store.state.dropdowns.find(function (el) {
            return el.name === "OutComes Status"
        })
        return outcomes
    },

  },

  data() {
    return {
      users: [],
      outcomes_data: [],
      form: new Form({
        category_id: null,
        outcome_id: null,
        status: null,
        client_id: this.$route.params.id,
      }),
    };
  },
  components: {},

  methods: {
    close() {
      this.$emit("close");
    },
    saveItem() {
      return axios.post('/api/outcomes',{
            category_id: this.form.category_id,
            outcome_id:  this.form.outcome_id,
            status:  this.form.status,
            client_id: this.$route.params.id
      })
      .then (response => {
        this.close()
      })
      .catch (error => {
        console.log(error)
      })
    },
    outcomes() {

      return axios.
      get(`/api/outcomes/${this.form.category_id}`)
      .then (response => {
        console.log(response)
         this.outcomes_data= response.data
      })
      .catch (error => {
        console.log(error)
      })
    }
  }
};
</script>
