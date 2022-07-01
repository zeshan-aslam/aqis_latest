<template>
  <div class="content">
    <div class="md-layout">
      <div class="md-progress-spinner-centre">
        <md-progress-spinner
          v-if="loading"
          md-mode="indeterminate"
        ></md-progress-spinner>
      </div>
      <FeedbackModal
        :client_id="client.id"
        :meeting="meeting"
        @close="closeModal('feedbackModal')"
        v-if="showfeedbackModal"
      ></FeedbackModal>
      <CilentMail
        @close="closeModal('CilentMailModal')"
        v-if="showCilentMailModal"
      ></CilentMail>
       <EditMail
        :bulkmailid="bulkmailid"
        @close="closeModal('EditMailModal')"
        v-if="showEditMailModal"
      ></EditMail>
      <div class="md-layout-item">
        <md-card>
          <md-card-header data-background-color="green">
            <h3 class="title">Reports</h3>
            <p class="titleCategory">Current collection of data reports.</p>
          </md-card-header>
          <md-card-content>
            <nav class="panel">
              <div class="panel-block">
                <div>
                  <md-button class="md-warning" @click="showReport(0)"
                    >Overview Report</md-button
                  >
                  <md-button class="md-danger" @click="showReport(1)"
                    >Group Activities</md-button
                  >
                  <md-button class="md-rose" @click="showReport(2)"
                    >Client Meetings</md-button
                  >
                  <md-button class="md-success" @click="showReport(3)"
                    >Demographics</md-button
                  >
                  <md-button class="md-warning" @click="showReport(5)"
                    >Feedbacks</md-button
                  >
                  <md-button class="md-success" @click="showReport(6)"
                    >Outcomes</md-button
                  >
                  <md-button class="md-rose" @click="showReport(7)"
                    >Noc Report</md-button
                  >
                   <md-button class="md-rose" @click="showReport(8)"
                    >Yearly Report</md-button
                  >
                </div>
                &nbsp;
                <!--                                <div>-->
                <!--                                    <a-->
                <!--                                        class="button is-fullwidth is-info is-active"-->
                <!--                                        @click="showReport(1)"-->
                <!--                                    >Group Activities</a>-->
                <!--                                </div>&nbsp;-->
                <!--                                <div>-->
                <!--                                    <a-->
                <!--                                        class="button is-fullwidth is-info is-active"-->
                <!--                                        @click="showReport(2)"-->
                <!--                                    >Client Meetings</a>-->
                <!--                                </div>&nbsp;-->
                <!--                                <div>-->
                <!--                                    <a-->
                <!--                                        class="button is-fullwidth is-info is-active"-->
                <!--                                        @click="showReport(3)"-->
                <!--                                    >Demographics</a>-->
                <!--                                </div>&nbsp;-->
              </div>

              <div class="panel-block" v-show="tabToShow == 0">
                <div class="section">
                  <div class="container">
                    <!--                                        <p>Start Date: </p>-->
                    <!--                                        <p>-->
                    <!--                                            <select v-model="startMonthMonthly" name="startMonthMonthly" id="startMonthMonthly">-->
                    <!--                                                <option value="" selected disabled>Month</option>-->
                    <!--                                                <option v-for="month in months" :value="month.value"> {{ month.text }}</option>-->
                    <!--                                            </select>-->
                    <!--                                            <select v-model="startYearMonthly" name="startYearMonthly" id="startYearMonthly">-->
                    <!--                                                <option value="" selected disabled>Year</option>-->
                    <!--                                                <option v-for="year in years" :value="year"> {{ year }}</option>-->
                    <!--                                            </select>-->
                    <!--                                        </p>-->
                    <!--                                        <p>End Date: </p>-->
                    <!--                                        <p>-->
                    <!--                                            <select v-model="endMonthMonthly" name="endMonthMonthly" id="endMonthMonthly">-->
                    <!--                                                <option value="" selected disabled>Month</option>-->
                    <!--                                                <option v-for="month in months" :value="month.value"> {{ month.text }}</option>-->
                    <!--                                            </select>-->
                    <!--                                            <select v-model="endYearMonthly" name="startYearMonthly" id="endYearMonthly">-->
                    <!--                                                <option value="" selected disabled>Year</option>-->
                    <!--                                                <option v-for="year in years" :value="year"> {{ year }}</option>-->
                    <!--                                            </select>-->
                    <!--                                        </p>-->
                    <div class="md-layout-item md-small-size-100 md-size-50">
                      <label for="startDate">Start Date</label>
                      <datepicker
                        :bootstrap-styling="true"
                        v-model="start"
                        :format="dateFormater"
                        id="startDate"
                        @input=""
                      ></datepicker>

                      <!--                                            <span class="help is-danger" v-if="form.errors.has('start')"-->
                      <!--                                                  v-text="form.errors.get('start')"></span>-->
                    </div>
                    <div class="md-layout-item md-small-size-100 md-size-50">
                      <label for="endDate">End Date</label>
                      <datepicker
                        :bootstrap-styling="true"
                        v-model="end"
                        :format="dateFormater"
                        id="endDate"
                        @input=""
                      ></datepicker>

                      <!--                                            <span class="help is-danger" v-if="form.errors.has('end')"-->
                      <!--                                                  v-text="form.errors.get('end')"></span>-->
                    </div>
                    <p>
                      <button
                        @click="generateMonthlyReport"
                        class="button is-success"
                      >
                        Generate Report
                      </button>
                    </p>
                  </div>
                </div>
              </div>

              <div class="panel-block" v-show="tabToShow == 1">
                <h1>Group Activities</h1>
              </div>
              <div class="panel-block" v-show="tabToShow == 2">
                <h1>Client Meetings</h1>
              </div>
              <div
                class="panel-block"
                v-show="tabToShow == 1 || tabToShow == 2"
              >
                <form
                  method="post"
                  @submit.prevent="onSubmit('generateReport')"
                  id="generateReport"
                  @input="valErrors[$event.target.name] = null"
                >
                  <table class="table">
                    <tr>
                      <th>Year</th>
                      <td>
                        <input name="year" v-model="year" type="text" />
                        <span
                          class="help is-danger"
                          v-for="(error, index) in valErrors.year"
                          :key="index"
                          >{{ error }}</span
                        >
                      </td>
                    </tr>
                    <tr v-show="tabToShow == 1">
                      <th>Group Activity Type</th>
                      <td>
                        <select name="type" v-model="type">
                          <option
                            v-for="(type, index) in groupActivityTypes.items"
                            :key="index"
                            :value="type.item"
                            :selected="index === 0"
                          >
                            {{ type.item }}
                          </option>
                        </select>
                        <span
                          class="help is-danger"
                          v-for="(error, index) in valErrors.type"
                          :key="index"
                          >{{ error }}</span
                        >
                      </td>
                    </tr>
                  </table>

                  <button
                    class="button is-success"
                    style="justify-content: space-between"
                    type="submit"
                    form="generateReport"
                  >
                    Generate Report
                  </button>
                </form>
              </div>

              <div class="panel-block" v-show="tabToShow == 3">
                <h1>Demographics</h1>
              </div>

              <div class="panel-block" v-show="tabToShow == 4">
                <form
                  method="post"
                  @submit.prevent="onSubmit('generateReport')"
                  id="generateReport"
                  @input="valErrors[$event.target.name] = null"
                >
                  <table class="table">
                    <tr>
                      <md-datepicker v-model="start_date" md-immediately>
                        <label>Start</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.start_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                    <tr>
                      <md-datepicker v-model="end_date" md-immediately>
                        <label>End</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.end_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                  </table>

                  <button
                    class="button is-success"
                    style="justify-content: space-between"
                    type="submit"
                    form="generateReport"
                  >
                    Generate Report
                  </button>
                </form>
              </div>

              <div class="panel-block" v-show="tabToShow == 5">
                <h1>Feedbacks</h1>
              </div>
              <div class="panel-block" v-show="tabToShow == 5">
                <form
                  method="post"
                  @submit.prevent="onSubmit('generateReport')"
                  id="generateReport"
                  @input="valErrors[$event.target.name] = null"
                >
                  <table class="table">
                    <tr>
                      <md-datepicker v-model="start_date" md-immediately>
                        <label>Start</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.start_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                    <tr>
                      <md-datepicker v-model="end_date" md-immediately>
                        <label>End</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.end_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                  </table>

                  <button
                    class="button is-success"
                    style="justify-content: space-between"
                    v-on:click="getMeetings(postUrl)"
                  >
                    View
                  </button>
                  <a
                    @click="downloadPdf('meetingsTable')"
                    class="button is-info is-active"
                  >
                    <span> Print Report </span>
                    <span class="icon is-small"> </span>
                  </a>
                </form>
              </div>
              <div
                class="panel-block"
                v-show="tabToShow == 5"
                id="meetingsTable"
              >
                <table class="table" style="table-layout: fixed; width: 100%">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Date</th>
                      <th scope="col">Client</th>
                      <th scope="col">Facilitator</th>
                      <th scope="col">Score</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="row in meetings" :key="row.id">
                      <td>{{ row.id }}</td>
                      <td>{{ row.date }}</td>
                      <td>
                        <router-link :to="/clients/ + row.client.id">
                          {{
                            row.client.user.firstName +
                            " " +
                            row.client.user.lastName
                          }}</router-link
                        >
                      </td>
                      <td>
                        {{
                          row.staff.user.firstName +
                          " " +
                          row.staff.user.lastName
                        }}
                      </td>
                      <td>
                        <button
                          @click="openModal('feedbackModal', row)"
                          class="button is-success"
                        >
                          <span><i class="far fa-solid fa-star"></i></span>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- ...........outcomes tab........... -->
              <div class="panel-block" v-show="tabToShow == 6">
                <h1>Outcomes</h1>
              </div>
              <div class="panel-block" v-show="tabToShow == 6">
                <form>
                  <table class="table">
                    <tr>
                      <label>Select Outcomes</label>
                      <select v-model="category" class="select md-menu md-select">
                        <option value="All">All</option>
                        <option
                          v-for="row in categories"
                          :key="row.id"
                          :value="row.id"
                        >
                          {{ row.name }}
                        </option>
                      </select>
                    </tr>
                    <tr>
                      <md-datepicker v-model="start_date" md-immediately>
                        <label>Start</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.start_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                    <tr>
                      <md-datepicker v-model="end_date" md-immediately>
                        <label>End</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.end_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                  </table>

                  <button
                    class="button is-success"
                    style="justify-content: space-between"
                    v-on:click="getOutcomes()"
                  >
                    View
                  </button>
                  <a
                    @click="downloadPdf('nocreport')"
                    class="button is-info is-active"
                  >
                    <span> Print Report </span>
                    <span class="icon is-small"> </span>
                  </a>
                </form>
              </div>
              <div
                class="panel-block"
                v-show="tabToShow == 6"
                id="nocreport"
              >
                <table class="table" style="table-layout: fixed; width: 100%">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Outcomes</th>
                      <th scope="col">Notes</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="row in outcomes" :key="row.id">
                      <td>{{ row.id }}</td>
                      <td>{{ row.outcome }}</td>
                      <td>{{ row.notes }}</td>
                      <td>{{ row.status }}</td>
                      <!-- <td>
                           <router-link :to="/clients/ + row.client.id">
                            {{
                              row.client.user.firstName +
                              " " +
                              row.client.user.lastName
                            }}</router-link
                          > 
                        </td>
                         <td>
                           {{
                              row.staff.user.firstName +
                              " " +
                              row.staff.user.lastName
                            }}
                            </td> -->

                      <!-- <button
                            @click="openModal('feedbackModal', row)"
                            class="button is-success"
                          >
                            <span><i class="far fa-solid fa-star"></i></span>
                          </button>
                        </td> -->
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- .............outcomes tab end......... -->
              <!-- ...........Noc Reports tab........... -->
              <div class="panel-block" v-show="tabToShow == 7">
                <h1>Noc Reports</h1>
              </div>
              <div class="panel-block" v-show="tabToShow == 7">
                <form>
                  <table class="table">
                    <tr>
                      <label>Select NOC</label>
                      <select v-model="cur_noc" class="select md-menu md-select">
                        <option
                          v-for="row in current_noc"
                          :key="row.id"
                          :value="row.id"
                        >
                          {{ row.code }}-{{ row.title }}
                        </option>
                      </select>
                    </tr>
                    <tr>
                      <md-datepicker v-model="start_date" md-immediately>
                        <label>Start</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.start_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                    <tr>
                      <md-datepicker v-model="end_date" md-immediately>
                        <label>End</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.end_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                  </table>

                  <button
                    class="button is-success"
                    style="justify-content: space-between"
                    v-on:click="getNocReport()"
                  >
                    View
                  </button>
                  <a
                    @click="downloadPdf('outcomestable')"
                    class="button is-info is-active"
                  >
                    <span> Print Report </span>
                    <span class="icon is-small"> </span>
                  </a>
                   <button
                          @click="openModal('CilentMailModal')"
                          class="button is-success"
                          v-if="this.$store.state.mail.length == 0"
                        >
                        Create Mail
                        </button>
                         <button
                          @click="openModal('EditMailModal')"
                          class="button is-success"
                           v-if="this.$store.state.mail!=''"
                        >
                        Send Mail
                        </button>
                       
                </form>
              </div>
              <div
                class="panel-block"
                v-show="tabToShow == 7"
                id="outcomestable"
              >
                <table class="table" style="table-layout: fixed; width: 100%">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Cilent</th>
                      <th scope="col">Facilitator</th>
                      <th>E-mail</th>
                      <!-- <th scope="col">Status</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    {{
                      bulkmailid
                    }}
                    <tr v-for="row in users" :key="row.id">
                      <td>{{ row.id }}</td>
                      <td>
                        
                        {{
                          row.client.user.firstName +
                          " " +
                          row.client.user.lastName
                        }}
                      </td>
                      <td>
                        {{
                          row.staff.user.firstName +
                          " " +
                          row.staff.user.lastName
                        }}
                      </td>
                      <td>
                        <input
                          type="checkbox"
                          v-model="bulkmailid"
                          :value="row.client_id"
                        />
                      </td>
                      <!-- <td>{{ row.status }}</td> -->
                      <!-- <td>
                           <router-link :to="/clients/ + row.client.id">
                            {{
                              row.client.user.firstName +
                              " " +
                              row.client.user.lastName
                            }}</router-link
                          > 
                        </td>
                         <td>
                           {{
                              row.staff.user.firstName +
                              " " +
                              row.staff.user.lastName
                            }}
                            </td> -->

                      <!-- <button
                            @click="openModal('feedbackModal', row)"
                            class="button is-success"
                          >
                            <span><i class="far fa-solid fa-star"></i></span>
                          </button>
                        </td> -->
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- .............Noc Report tab end......... -->
              <!-- ...........Yearly Reports tab........... -->
              <div class="panel-block" v-show="tabToShow == 8">
                <h1>Yearly Reports</h1>
              </div>
              <div class="panel-block" v-show="tabToShow == 8">
                <form>
                  <table class="table">
                    <tr>
                      <label>Select Year</label>
                      {{ year }}
                      <select v-model="year" class="select md-menu md-select">
                        <option
                          v-for="(row, index) in years"
                          :key="row.id"
                          :value="row"
                        >
                          {{ row }}
                        </option>
                      </select>
                    </tr>
                    <!-- <tr>
                      <md-datepicker v-model="start_date" md-immediately>
                        <label>Start</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.start_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr>
                    <tr>
                      <md-datepicker v-model="end_date" md-immediately>
                        <label>End</label>
                      </md-datepicker>
                      <span
                        class="help is-danger"
                        v-for="(error, index) in valErrors.end_date"
                        :key="index"
                        >{{ error }}</span
                      >
                    </tr> -->
                  </table>

                  <button
                    class="button is-success"
                    style="justify-content: space-between"
                    v-on:click="getYearly()"
                  >
                    View
                  </button>
                  <a
                    @click="downloadPdf('outcomestable')"
                    class="button is-info is-active"
                  >
                    <span> Print Report </span>
                    <span class="icon is-small"> </span>
                  </a>
                   <button
                          @click="openModal('CilentMailModal')"
                          class="button is-success"
                          v-if="this.$store.state.mail.length == 0"
                        >
                        Create Mail
                        </button>
                         <button
                          @click="openModal('EditMailModal')"
                          class="button is-success"
                           v-if="this.$store.state.mail!=''"
                        >
                        Send Mail
                        </button>
                       
                </form>
              </div>
              <div class="panel-block paginate" align="right">
                <pagination
                  :firstPage="pagination.first_page_url"
                  :previousPage="pagination.prev_page_url"
                  :currentPage="pagination.current_page"
                  :nextPage="pagination.next_page_url"
                  :lastPage="pagination.last_page"
                  :totalPages="pagination.total"
                  :maxVisibleButtons="5"
                  :url="postUrl"
                  @pageChanged="onPageChange($event)"
                >
                </pagination>
              </div>
            </nav>
          </md-card-content>
        </md-card>
      </div>
    </div>
  </div>
</template>

<script>
import Form from "../Forms.js";
import AddTemplateModal from "./Modals/Addtemplatemodal.vue";
import Pagination from "./pagination.vue";
import AQISReportsVue from "./AQISReports.vue";
import moment from "moment";
import Datepicker from "@hokify/vuejs-datepicker";
import FeedbackModal from "./Modals/FeedbackModal.vue";
import CilentMail from "./Modals/CilentMail.vue";
import EditMail from "./Modals/EditMail.vue";
var pdfMake = require("pdfmake/build/pdfmake.js");
var pdfFonts = require("pdfmake/build/vfs_fonts.js");
pdfMake.vfs = pdfFonts.pdfMake.vfs;
import htmlToPdfmake from "html-to-pdfmake";
import axios from "axios";

let convert = require("xml-js");

export default {
  created() {
    this.generateYears();
    this.monthYear = moment().format("MMMM YYYY");
  },

  components: {
    Datepicker,
    FeedbackModal,
    Pagination,
    CilentMail,
    EditMail,
  },

  data() {
    return {
      loading: false,
      showfeedbackModal: false,
      showCilentMailModal:false,
      showEditMailModal:false,
      tabToShow: -1,
      client: {},
      categories: [],
      pagination: {},
      meeting: {},
      meetings: [],
      outcomes: [],
      users: [],
      current_noc: [],
      bulkmailid: [],
      year:'',
      postUrl: "/api/report/getFeedbacks",
      months: [
        { text: "Jan", value: 1 },
        { text: "Feb", value: 2 },
        { text: "Mar", value: 3 },
        { text: "Apr", value: 4 },
        { text: "May", value: 5 },
        { text: "June", value: 6 },
        { text: "July", value: 7 },
        { text: "Aug", value: 8 },
        { text: "Sept", value: 9 },
        { text: "Oct", value: 10 },
        { text: "Nov", value: 11 },
        { text: "Dec", value: 12 },
      ],
      years: [],
      start: "",
      end: "",
      startMonthMonthly: "",
      startYearMonthly: "",
      endMonthMonthly: "",
      endYearMonthly: "",
      clientStatus: "all",
      type: "",
      year: new Date().getFullYear(),
      start_date: "",
      end_date: "",
      category: "",
      cur_noc: "",
      valErrors: [],
      tableData: {
        forums: -1,
        forumsPart: 0,
        initialIrcc: 0,
        month: 0,
        repeatIrcc: 0,
        workshop: 0,
        workshopPart: 0,
      },
      monthYear: "",
    };
  },
  created() {
    this.getMeetings(this.postUrl);
    this.getcategories();
    this.currentnoc();
    this.generateYears();
  },
  computed: {
    programNames() {
      return this.getDropdownValues("Program Name");
    },
    groupActivityTypes() {
      return this.getDropdownValues("Group Activity Type");
    },
  },

  methods: {
    openModal(name, item) {
      switch (name) {
        case "feedbackModal":
          this.meeting = item;
          this.client = item.client;
          this.showfeedbackModal = true;
          break;
        case "CilentMailModal":
          this.showCilentMailModal = true;
          break;
        case "EditMailModal":
          this.showEditMailModal = true;
          break;
          
      }
    },
    closeModal(name) {
      switch (name) {
        case "feedbackModal":
          this.showfeedbackModal = false;
          break;
        case "CilentMailModal":
          this.showCilentMailModal = false;
          break;
        case "EditMailModal":
          this.showEditMailModal = false;
          break;  
      }
    },
    dateFormater(date) {
      return moment(date).format("YYYY-MM-DD");
    },
    generateYears() {
      var date = new Date();
      var thisYear = date.getFullYear();
      this.years = [];
      var i;
      for (i = 2010; i <= thisYear; i++) {
        this.years.push(i);
      }
      console.log(this.years);
    },
    getDropdownValues(name) {
      let dropdown = { items: null };
      try {
        dropdown = this.$store.state.dropdowns.find(function (el) {
          return el.name === name;
        });
      } catch (error) {}

      return dropdown;
    },
    showReport(tab) {
      this.tabToShow = tab;
    },
    getMeetings(url) {
      console.log();
      let fd = new FormData();
      fd.append("startDate", this.start_date);
      fd.append("endDate", this.end_date);
      console.log(fd);
      axios.post(url, fd).then((response) => {
        this.meetings = response.data.data;
        this.pagination = response.data;
        this.loading = false;
        console.log(response.data.data);
      });
    },
    downloadPdf(value) {
      switch (value) {
        case "nocreport":
          var head = "Reports of Noc Report";
          var headerText = htmlToPdfmake(head);
          var docDefinition = {
            pageSize: "A4",
            pageMargins: [10, 60, 10, 40],
            header: {
              columns: [
                {
                  text: headerText,
                  alignment: "center",
                  margin: [12, 20, 30, 20],
                },
              ],
            },

            content: htmlToPdfmake(
              document.getElementById("nocreport").innerHTML
            ),
            footer: {
              columns: [{ text: "Footer Text", alignment: "center" }],
            },
          };

          pdfMake.createPdf(docDefinition).open();
          break;
        case "meetingsTable":
          var head = "Reports of Meetings";
          var headerText = htmlToPdfmake(head);
          var docDefinition = {
            pageSize: "A4",
            pageMargins: [10, 60, 10, 40],
            header: {
              columns: [
                {
                  text: headerText,
                  alignment: "center",
                  margin: [12, 20, 30, 20],
                },
              ],
            },

            content: htmlToPdfmake(
              document.getElementById("meetingsTable").innerHTML
            ),
            footer: {
              columns: [{ text: "Footer Text", alignment: "center" }],
            },
          };

          pdfMake.createPdf(docDefinition).open();
          break;
        case "outcomestable":
          var head = "Reports of Outcomes";
          var headerText = htmlToPdfmake(head);
          var docDefinition = {
            pageSize: "A4",
            pageMargins: [10, 60, 10, 40],
            header: {
              columns: [
                {
                  text: headerText,
                  alignment: "center",
                  margin: [12, 20, 30, 20],
                },
              ],
            },

            content: htmlToPdfmake(
              document.getElementById("outcomestable").innerHTML
            ),
            footer: {
              columns: [{ text: "Footer Text", alignment: "center" }],
            },
          };

          pdfMake.createPdf(docDefinition).open();
          break;
      }
    },
    getcategories() {
      axios
        .get("/api/getcategories")
        .then((res) => {
          console.log(res.data);
          this.categories = res.data;
        })
        .catch((error) => {});
    },
    getOutcomes() {
      let fd = new FormData();
      fd.append("category", this.category);
      fd.append("startDate", this.start_date);
      fd.append("endDate", this.end_date);
      console.log(fd);
      axios.post("/api/client/outcomesreport", fd).then((response) => {
        this.outcomes = response.data.data;
        this.pagination = response.data;
        this.loading = false;
        console.log(response.data);
      });
    },
    currentnoc() {
      axios
        .get("/api/current_noc")
        .then((res) => {
          console.log(res.data);
          this.current_noc = res.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getNocReport() {
      let fd = new FormData();
      fd.append("current_noc", this.cur_noc);
      fd.append("startDate", this.start_date);
      fd.append("endDate", this.end_date);
      axios.post("/api/nocReport", fd).then((response) => {
        this.users = response.data.data;
        this.pagination = response.data;
        this.loading = false;
        console.log(response.data.data);
      });
    },
     editMail()
    {
    console.log(this.$store.state.mail);
    },
    getYearly()
    { 
      axios.post('api/yearlyReport',{year:this.year}).then(res=>{
        console.log(res)
      }).catch(err=>{
        console.log(err)
      });
      console.log('Yearly Report Api Called');
    },
    onPageChange(value) {
      this.getMeetings(value);
    },
    generateMonthlyReport() {
      let routeData = this.$router.resolve({
        name: "AQIS Monthly Report",
        query: {
          start: this.dateFormater(this.start),
          end: this.dateFormater(this.end),
        },
      });
      window.open(routeData.href, "_blank");
    },

    onSubmit(report) {
      this.valErrors = [];
      let reportType = null;
      let reportName = null;
      switch (this.tabToShow) {
        case 1:
          reportType = "getGroupActivityReport";
          reportName = "AQIS Report";
          this.$store.commit("SET_REPORT_REQUEST", {
            year: this.year,
            type: this.type,
          });
          break;
        case 2:
          reportType = "getClientReport";
          reportName = "AQIS Report";
          this.$store.commit("SET_REPORT_REQUEST", {
            year: this.year,
            type: this.type,
          });
          break;
        case 3:
          reportType = "getDemographicReport";
          reportName = "Demographic Reports";
          this.$store.commit("SET_REPORT_REQUEST", {
            start_date: this.start_date
              ? moment(this.start_date).format("YYYY-MM-DD")
              : "",
            end_date: this.end_date
              ? moment(this.end_date).format("YYYY-MM-DD")
              : "",
          });
          break;
      }

      this.$store
        .dispatch(reportType)
        .then((response) => {
          this.$router.push({
            name: reportName,
            params: {
              responseData: response,
            },
          });
          // window.open("/#/reports/AQISReport", '_blank');
        })
        .catch((error) => {
          if (error.response.status == 422) {
            this.valErrors = error.response.data.errors;
          }
        });
    },

    getMonthly() {
      let date = new Date();
      let firstOfMonth = new Date(date.getFullYear(), date.getMonth(), 1)
        .toISOString()
        .slice(0, 10);
      this.$store
        .dispatch("getMonthlyActivity", firstOfMonth)
        .then((response) => {
          this.tableData = response;
        });
    },

    getClientReport($year) {},
  },
};
</script>
