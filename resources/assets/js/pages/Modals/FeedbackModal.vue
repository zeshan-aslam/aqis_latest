<template>
  <transition name="modal">
    <div class="modal is-active">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">
            Meeting Feedback
            <slot name="header">
              <button
                class="delete feedback"
                aria-label="close"
                @click="close"
              ></button>
            </slot>
          </p>
          <slot name="header-button"></slot>
        </header>
        <section class="modal-card-body">
          <md-card>
            <md-card-content class="text-center" >
             <div class="md-progress-spinner-centre">
                <md-progress-spinner v-if="loading" md-mode="indeterminate">
                </md-progress-spinner>
               </div>
            <slot>

               <table class="table" v-if="!loading">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Feedback</th>
                </tr>
                <tr :key="index" v-for="(row, index) in feedbacks">
                    <td> {{row.title}}</td>
                     <td> {{row.description}}</td>
                      <td>
                           <b-form-rating v-model="row.feedback.value" readonly variant="warning" style="font-size: xx-large;"></b-form-rating>
                     </td>

                </tr>
                 <tr v-show="feedbacks==''">
                     <td colspan="3" class="alert alert-danger"> No Records Found</td>

                 </tr>
               </table>




                </slot>

            </md-card-content>
          </md-card>
        </section>
        <footer class="modal-card-foot">
          <slot name="footer"></slot>
        </footer>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: "FeedbackModal.vue",
  props: {
    client_id: "",
    meeting: "",
  },
  data() {
    return {
      feedbacks: [],
      value: 3.5,
      loading:true,
    };
  },

  created() {
    console.log(this.$store.state.user.role);
    this.getFeedbacks();
  },
  methods: {
    getFeedbacks() {
      axios
        .get("/api/clientQuestions/" + this.meeting.id + "/" + this.client_id)
        .then((response) => {
          this.feedbacks = response.data;
          this.loading=false;
          console.log(response.data);
        });
    },
    close() {
      this.$emit("close");
    },
  },
};
</script>
<style scoped>
.feedback {
  float: right;
}
</style>
