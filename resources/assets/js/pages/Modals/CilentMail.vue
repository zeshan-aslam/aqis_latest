<template>
  <transition name="modal">
    <div class="modal is-active">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">
             Mail To Selective Cilents
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
                <md-field>
                  <label>E_Mail Title</label>
                  <md-input v-model="data.header"></md-input>
                </md-field>
                <md-field>
                  <label>Content</label>
                  <md-textarea v-model="data.content"/>
                </md-field>
                <md-field>
                  <label>Footer</label>
                  <md-input v-model="data.footer"></md-input>
                </md-field>
                <md-field>
                   <md-button class="md-primary" @click="SaveMail">Save Mail</md-button>
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
export default {
  name: "CilentMail",
  created() {
    console.log(this.id);
  },
  data() {
    return {
      data:{
      header:"",
      content:"",
      footer:"",
      }
    };
  },
  methods: {
    SaveMail()
    {
       this.$store.commit('SET_Mail',this.data);
    }, 
    close() {
      this.$emit("close");
      console.log(this.$store.state.mail);
    },
  },
};
</script>
<style>
.closeButton {
  float: right;
}
</style>