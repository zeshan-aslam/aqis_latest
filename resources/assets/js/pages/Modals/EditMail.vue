<template>
  <transition name="modal">
    <div class="modal is-active">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">
           Edit Mail To Selective Cilents
            <slot name="header">
              <button
                class="delete closeButton"
                aria-label="close"
                @click="close"
              ></button>
            </slot>
          </p>
          <slot name="header-button"></slot>
        </header>
        <section class="modal-card-body">
          <md-card>
            <md-card-content>
              {{ id }}
             <div class="md-layout-item">
                <md-field style="margin-bottom: 44px;">
                  <label>E_Mail Title</label>
                  <!-- <md-input v-model="mail.header"></md-input> -->
                </md-field>
                 <vue-editor v-model="mail.header"></vue-editor>
                <md-field style="margin-bottom: 44px;">
                  <label>Content</label>
                  <!-- <md-textarea v-model="mail.content"/> -->
                </md-field>
                 <vue-editor v-model="mail.content"></vue-editor>
                <md-field style="margin-bottom: 44px;">
                  <label>Footer</label>
                  <!-- <md-input v-model="mail.footer"></md-input> -->
                </md-field>
                <vue-editor v-model="mail.footer"></vue-editor>
                <md-field>
                   <md-button class="md-success" @click="SendMail">Send Mail</md-button>
                </md-field>
              </div>
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
import { VueEditor } from "vue2-editor";
export default {
  components: {
    VueEditor,
  },
  props: ["bulkmailid"],
  name: "EditMail",
  created() {
    console.log(this.id);
  },
  data() {
    return {
      id: [],
      mail:[],
    };
  },
  created()
  {
   this.mail=this.$store.state.mail;
   console.log(this.$store.state.mail);
  },
  methods: {
    SendMail()
    {
     axios.post('/api/sendBulkMail',{id:this.bulkmailid,mail:this.mail}).then(response=>{
        console.log(response);
     }).catch(error=>{
       console.log(error);
     });
    }, 
    close() {
      this.$emit("close");
    },
  },
};
</script>
<style>
.closeButton {
  float: right;
}
.margin{
  margin-bottom: 44px!important;
}
</style>