<template>
    <div class="md-layout">
        <div class="md-progress-spinner-centre">
            <md-progress-spinner class="md-progress-spinner-centre" md-mode="indeterminate" v-if="loading">
            </md-progress-spinner>
        </div>
         <FeedbackModal
      :client_id="client.id"
       :meeting="meetingEdit"
      @close="closeModal('feedbackModal')"
      v-if="showfeedbackModal"
    ></FeedbackModal>
        <AddClientModal :client_id="client.id" :editing="true" @close="closeModal('clientModal')"
                        v-if="showClientModal"></AddClientModal>
        <AddEducationModal :client_id="clientID" :educationEdit="educationEdit" @close="closeModal('educationModal')"
                           v-if="showEducationModal"></AddEducationModal>
        <AddTrainingModal :client_id="clientID" :trainingEdit="trainingEdit" @close="closeModal('trainingModal')"
                          v-if="showTrainingModal"></AddTrainingModal>
        <AddEmploymentModal :client_id="clientID" :employmentEdit="employmentEdit" @close="closeModal('employmentModal')"
                            v-if="showEmploymentModal"></AddEmploymentModal>
        <AddMeetingModal :client_id="clientID" :meetingEdit="meetingEdit" @close="closeModal('meetingModal')"
                         v-if="showMeetingModal"></AddMeetingModal>
        <AddWorkshopModal :client_id="clientID" :workshopEdit="workshopEdit" @close="closeModal('workshopModal')"
                          v-if="showWorkshopModal"></AddWorkshopModal>
        <AddOutcomeModal :client_id="clientID" :outcomeEdit="outcomeEdit" @close="closeModal('addOutcomeForm')"
                          v-if="addOutcomeForm"></AddOutcomeModal>
        <meeting-resource-modal :meeting="meetingEdit" :client_id="client.id" @close="closeModal('MeetingResourceModal')"
                                v-if="showMeetingResourceModal"></meeting-resource-modal>
        <workshop-resource-modal :workshop="workshopEdit" @close="closeModal('WorkshopResourceModal')"
                                 v-if="showWorkshopResourceModal"></workshop-resource-modal>

        <div class="md-layout-item md-size-100">
            <md-card>
                <md-card-header data-background-color="green">
                    <h3 class="title" v-if="client.user">{{client.user.firstName + " " + client.user.lastName}}'s
                        Profile</h3>
                </md-card-header>
                <md-card-content>
                    <div>
                        <md-button class="md-success"  @click="showReport(0)">Profile</md-button>
                        <md-button class="md-success" @click="showReport(1)">Documents</md-button>
                    </div>
                <div v-show="tabToShow == 0">
                    <nav class="panel">
                        <div class="panel-block">
                            <p>
                                <a @click="openModal('clientModal')" class="button is-info is-active"
                                   v-if="$root.canWrite('clients')">
                                    <span> Edit Client </span>
                                    <span class="icon is-small">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                </a>
                                <a @click="deleteClient" class="button is-danger is-active"
                                   v-if="$root.canDelete('clients')">
                                    <span> Delete Client </span>
                                    <span class="icon is-small">
                                        <i class="fas fa-times"></i>
                                    </span>
                                </a>
                            </p>
                        </div>

                        <!-- HEAD -->

                        <table class="table is-fullwidth is-bordered">
                            <tr>
                                <th width="30%">
                                    WC ID
                                </th>
                                <td>
                                    {{ client.wc_id }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    First Name
                                </th>
                                <td v-if="client.user">
                                    {{ client.user.firstName }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Last Name
                                </th>
                                <td v-if="client.user">
                                    {{ client.user.lastName }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Username
                                </th>
                                <td v-if="client.user">
                                    {{ client.user.username }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    E-mail
                                </th>
                                <td v-if="client.user">
                                    {{ client.user.email }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Immigration Status
                                </th>
                                <td>
                                    {{ client.immigrationStatus }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Date of Birth
                                </th>
                                <td>
                                    {{ client.dob }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Date of Arrival
                                </th>
                                <td>
                                    {{ client.doa }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Gender
                                </th>
                                <td>
                                    {{ client.gender }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Street address
                                </th>
                                <td>
                                    {{ client.streetAddress }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    City
                                </th>
                                <td>
                                    {{ client.city }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Province
                                </th>
                                <td>
                                    {{ client.province }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Postal Code
                                </th>
                                <td>
                                    {{ client.postalCode }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Primary Phone
                                </th>
                                <td v-if="client">
                                    {{ phoneFormater.FormatPhone(client.phonePrimary) }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Secondary Phone
                                </th>
                                <td>
                                    {{ phoneFormater.FormatPhone(client.phoneSecondary) }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Mother Tongue
                                </th>
                                <td>
                                    {{ client.motherTongue }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Country of Origin
                                </th>
                                <td>
                                    {{ client.countryOrigin }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    English Proficiency
                                </th>
                                <td>
                                    {{ client.engProficiency }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Interpreter Needed?
                                </th>
                                <td>
                                    <div v-if="client.interpreterNeeded==0">No</div>
                                    <div v-else>Yes</div>
                                </td>
                            </tr>

                            <tr v-if="client.interpreterLanguage">
                                <th width="30%">
                                    Interpreter Language
                                </th>
                                <td>
                                    {{ client.interpreterLanguage }}
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Childcare Needed?
                                </th>
                                <td>
                                    <div v-if="client.childcareNeeded==0">No</div>
                                    <div v-else>Yes</div>
                                </td>
                            </tr>

                            <tr>
                                <th width="30%">
                                    Notes
                                </th>
                                <td>
                                    {{ client.notes }}
                                </td>
                            </tr>

                        </table>

                        <!-- master -->
                    </nav>
                    <nav class="panel">
                        <div class="panel-block">
                            <h1 class="control has-icons-left"><b>Client Credential History</b></h1>
                        </div>
                        <div class="panel-block">
                            <h3 class="control has-icons-left"><b>Education</b></h3>
                            <a @click="openModal('educationModal')" class="button is-primary is-active"
                               v-show="$root.canWrite('clients')">
                                <span>Add Education</span>
                                <span class="icon is-small">
                                    <i class="fas fa-plus-square"></i>
                                </span>
                            </a><br>
                        </div>
                        <div class="panel-block">
                            <table class="table">
                                <tr>
                                    <th style="max-width: 150px;">Education Level</th>
                                    <th>Major</th>
                                    <th>Graduation Year</th>
                                    <th>Country</th>
                                    <th style="text-align:center;">Highest Education</th>
                                    <th></th>
                                </tr>
                                <tr :key="education.id" v-for="(education, index) in educations">
                                    <td>
                                        {{ education.education_level }}
                                    </td>
                                    <td>
                                        {{ education.major }}
                                    </td>
                                    <td>
                                        {{ education.graduation_date }}
                                    </td>
                                    <td>
                                        {{ education.education_country }}
                                    </td>
                                    <td style="text-align:center;">
                                        <input type="radio"
                                               name="highestEducation"
                                               id="highestEducation"
                                               @change="postHighestEducation(education.id)"
                                               :checked="client.highestEducation_id===education.id"
                                        >
                                    </td>
                                    <td>
                                        <div align="right">
                                            <button @click="editModal('educationModal', education)" class="button is-success"
                                                    v-show="$root.canWrite('clients')">
                                                <span><i class="fas fa-pencil-alt"></i></span>
                                            </button>
                                            <!-- <button class="button is-danger"
                                                @click="deleteEducation(index, education.id)">
                                                <span><i class="far fa-trash-alt"></i></span>
                                            </button> -->
                                            <button @click="deleteEducation(index, education.id)" class="button is-danger"
                                                    v-show="$root.canDelete('clients')">
                                                <span><i class="far fa-trash-alt"></i></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-block">
                            <h3 class="control has-icons-left"><b>Training/ Certification / Professional Affiliation</b>
                            </h3>
                            <a @click="openModal('trainingModal')" class="button is-primary is-active"
                               v-show="$root.canWrite('clients')">
                                <span>Add Training</span>
                                <span class="icon is-small">
                                    <i class="fas fa-plus-square"></i>
                                </span>
                            </a><br>
                        </div>
                        <div class="panel-block">
                            <table class="table">
                                <tr>
                                    <th style="max-width: 150px;">Training/ Certification / Professional Affiliation
                                    </th>
                                    <th>Association</th>
                                    <th>Year</th>
                                    <th>Country</th>
                                    <th></th>
                                </tr>

                                <tr :key="training.id" v-for="(training, index) in trainings">
                                    <td>
                                        {{ training.subject }}
                                    </td>
                                    <td>
                                        {{ training.association }}
                                    </td>
                                    <td>
                                        {{ training.training_year }}
                                    </td>
                                    <td>
                                        {{ training.country }}
                                    </td>
                                    <td>
                                        <div align="right">
                                            <button @click="editModal('trainingModal', training)" class="button is-success"
                                                    v-show="$root.canWrite('clients')">
                                                <span><i class="fas fa-pencil-alt"></i></span>
                                            </button>
                                            <button @click="deleteTraining(index, training.id)" class="button is-danger"
                                                    v-show="$root.canDelete('clients')">
                                                <span><i class="far fa-trash-alt"></i></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-block">
                            <h3 class="control has-icons-left"><b>Employment</b></h3>
                            <a @click="openModal('employmentModal')" class="button is-primary is-active"
                               v-show="$root.canWrite('clients')">
                                <span>Add Employment</span>
                                <span class="icon is-small">
                                    <i class="fas fa-plus-square"></i>
                                </span>
                            </a><br>
                        </div>
                        <div class="panel-block">
                            <table class="table is-fullwidth">
                                <tr>
                                    <th width="35%">NOC</th>
                                    <th>Job Title</th>
                                    <th style="text-align:center">Years of Experience</th>
                                    <th>Country</th>
                                    <th></th>
                                </tr>
                                <tr :key="employment.id" v-for="(employment, index) in employments">
                                    <td v-if="employment.noc">
                                        {{ employment.noc.code + " - [" + employment.noc.title + "]" }}
                                    </td>
                                    <td>
                                        {{ employment.job_title }}
                                    </td>
                                    <td style="text-align:center">
                                        {{ employment.experience_years }}
                                    </td>
                                    <td>
                                        {{ employment.country }}
                                    </td>
                                    <td>
                                        <div align="right">
                                            <button @click="editModal('employmentModal', employment)" class="button is-success"
                                                    v-show="$root.canWrite('clients')">
                                                <span><i class="fas fa-pencil-alt"></i></span>
                                            </button>
                                            <button @click="deleteEmployment(index, employment.id)" class="button is-danger"
                                                    v-show="$root.canDelete('clients')">
                                                <span><i class="far fa-trash-alt"></i></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </nav>
                    <nav class="panel">
                        <div class="panel-block">
                            <h1 class="control has-icons-left"><b>Client Meetings / Activities</b></h1>
                            <a @click="openModal('meetingModal')" class="button is-primary is-active"
                               v-show="$root.canWrite('clients')">
                                <span>Add Meeting</span>
                                <span class="icon is-small">
                                    <i class="fas fa-plus-square"></i>
                                </span>
                            </a> &nbsp;
<!--                            <a @click="openModal('workshopModal')" class="button is-primary is-active"-->
<!--                               v-show="$root.canWrite('clients')">-->
<!--                                <span>Add Group Activity</span>-->
<!--                                <span class="icon is-small">-->
<!--                                    <i class="fas fa-plus-square"></i>-->
<!--                                </span>-->
<!--                            </a>-->
                            <br>
                        </div>
                        <div class="panel-block">
                            <h3 class="control has-icons-left"><b>Meetings</b></h3>
                        </div>
                        <div class="panel-block">
                            <table>
                                <tr>
                                    <th>Meeting Type</th>
                                    <th>Meeting Date</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Funder</th>
                                    <th>Facilitator</th>
                                    <th></th>
                                </tr>
                                <tr :key="meeting.id" v-for="(meeting, index) in meetings">
                                    <td>
                                        {{ meeting.type }}
                                    </td>
                                    <td>
                                        {{ meeting.date }}
                                    </td>
                                    <td>
                                        {{ meeting.status }}
                                    </td>
                                    <td>
                                        {{ meeting.location }}
                                    </td>
                                    <td>
                                        {{ meeting.funder }}
                                    </td>
                                    <td v-if="meeting.staff">
                                        {{ meeting.staff.user.firstName + " " + meeting.staff.user.lastName}}
                                    </td>
                                    <td>
                                        <div align="right">
                                            <button @click="editModal('MeetingResourceModal', meeting)" class="button is-success"
                                                    v-show="$root.canWrite('clients')">
                                                <span><i class="fas fa-hands-helping"></i></span>
                                            </button>
                                           <button
                                                @click="editModal('feedbackModal', meeting)"
                                                class="button is-success"
                                                v-show="$root.canDelete('clients')"
                                                >
                                                <span><i class="far fa-solid fa-star"></i></span>
                                            </button>
                                            <button @click="editModal('meetingModal', meeting)" class="button is-success"
                                                    v-show="$root.canWrite('clients')">
                                                <span><i class="fas fa-pencil-alt"></i></span>
                                            </button>
                                            <button @click="deleteMeeting(index, meeting.id)" class="button is-danger"
                                                    v-show="$root.canDelete('clients')">
                                                <span><i class="far fa-trash-alt"></i></span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="panel-block">
                            <h3 class="control has-icons-left"><b>Group Activities</b></h3>
                        </div>
                        <div class="panel-block">
                            <table>
                                <tr>
                                    <th width="25%">Activity Type</th>
                                    <th>Start date and time</th>
                                    <th>Description</th>
                                    <th>Location</th>
                                    <th>Funder</th>
<!--                                    <th>Facilitator</th>-->
<!--                                    <th></th>-->
                                </tr>
                                <tr :key="activity.id" v-for="(activity) in client.group_activities">
                                    <td>
                                        {{ activity.type }}
                                    </td>
                                    <td>
                                        {{ activity.events[0].start }}
                                    </td>
                                    <td>
                                        {{ activity.description }}
                                    </td>
                                    <td>
                                        {{ activity.location }}
                                    </td>
                                    <td>
                                        {{ activity.funder }}
                                    </td>

<!--                                    <td>-->
<!--                                        {{ workshop.staff.user.firstName + " " + workshop.staff.user.lastName }}-->
<!--                                    </td>-->
<!--                                    <td>-->
<!--                                        <div align="right">-->
<!--                                            <button @click="editModal('WorkshopResourceModal', workshop)" class="button is-success"-->
<!--                                                    v-show="$root.canWrite('clients')">-->
<!--                                                <span><i class="fas fa-hands-helping"></i></span>-->
<!--                                            </button>-->
<!--                                            <button @click="editModal('workshopModal', workshop)" class="button is-success"-->
<!--                                                    v-show="$root.canWrite('clients')">-->
<!--                                                <span><i class="fas fa-pencil-alt"></i></span>-->
<!--                                            </button>-->
<!--                                            <button @click="deleteWorkshop(index, workshop.id)" class="button is-danger"-->
<!--                                                    v-show="$root.canDelete('clients')">-->
<!--                                                <span><i class="far fa-trash-alt"></i></span>-->
<!--                                            </button>-->
<!--                                        </div>-->
<!--                                    </td>-->
                                </tr>
                            </table>
                        </div>
                    </nav>
                    <!-- OUTCOMES PANEL -->
                    <nav class="panel">
                        <div class="panel-block">
                            <h1 class="control has-icons-left"><b>Client Outcomes</b></h1>
                             <!-- <a class="button is-primary is-active">
                                <span>Add </span>
                                <span class="icon is-small">
                                    <i class="fas fa-plus-square"></i>
                                </span>
                            </a> -->
                             <a @click="openModal('addOutcomeForm')" class="button is-primary is-active"
                               v-show="$root.canWrite('clients')">
                                <span>Add OutCome</span>
                                <span class="icon is-small">
                                    <i class="fas fa-plus-square"></i>
                                </span>
                            </a>
                        </div>
                        <!-- <div class="panel-block"> -->
                        <div class="columns">
                            <div class="column is-full">
                                <div :key="index" v-for="(category, index) in categories">
                                    <div @click="collapse($event,category.id)" class="header-collapsible"> {{ category.name }}</div>
                                    <div class="content-collapsible" >
                                        <div
                                        :key="id"  v-for="(data, id) in selectedCategoryOutcomes"
                                        class="outcome md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-50"
                                        >

                                        <time-line plain type="simple" style="list-style: none;" v-if="data.category_id === selectedCategoryOutcome" >
                                            <time-line-item inverted badge-type="danger" v-show="data.status =='cancelled'  && data.category_id == selectedCategoryOutcome" badge-icon="card_travel">
                                            <span slot="header" class="badge badge-danger">{{data.id}}</span>
                                            <p slot="content">
                                                {{data.outcome.outcome}}
                                            </p>

                                            <h6 slot="footer">
                                                <i class="ti-time"></i>
                                            </h6>
                                            </time-line-item>

                                            <time-line-item inverted v-show="data.status =='completed' && data.category_id == selectedCategoryOutcome" :badge-type=" 'success'" badge-icon="extension">
                                            <span slot="header" class="badge badge-success">{{data.id}}</span>
                                            <p slot="content">
                                                     {{data.outcome.outcome}}
                                            </p>
                                            </time-line-item>

                                            <time-line-item inverted badge-type="info"  v-show="data.status =='inprogress' && data.category_id == selectedCategoryOutcome"
                                          badge-icon="fingerprint">
                                            <span slot="header" class="badge badge-info">{{data.id}}</span>
                                            <div slot="content">
                                                      {{data.outcome.outcome}}
                                                <hr />
                                            </div>
                                            </time-line-item>
                                        </time-line>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- </div> -->
                    </nav>
                </div>
                <div v-if="tabToShow == 1">
                <nav class="panel">
                    <div class="panel-block">
                        <form
                        method="post"
                        enctype="multipart/form-data"
                        @submit.prevent="saveDocs()"
                        id="addDocsForm"
                        novalidate="true"
                        >
                        <div class="md-layout-item md-medium-size-100 md-size-100">
                            <label for="doc_name">Document Name </label>
                            <md-field>
                            <div class="md-field md-theme-default md-has-value">
                                <select
                                class="select md-menu md-select"
                                v-model="doc_name"
                                name="doc_name"
                                id="doc_name"
                                >

                                <option
                                    v-for="(doc, index) in DocumentNames.items"
                                    :key="index"
                                    :value="doc.item"
                                >
                                    {{ doc.item }}
                                </option>
                                </select>
                            </div>
                            </md-field>
                        </div>

                        <span class="help is-danger" v-if="errors.doc_name">
                            {{ errors.doc_name[0] }}</span
                        >

                        <div class="md-layout-item md-medium-size-100 md-size-100">
                            <label>Upload File </label>
                            <md-field>
                            <md-input
                                name="doc_file"
                                accept=".pdf,.doc,.csv,.jpg"
                                v-model="doc_file"
                                v-on:change="onFileChange"
                                type="file"
                            ></md-input>
                            </md-field>
                            <span class="help is-danger" v-if="errors.doc_file">
                            {{ errors.doc_file[0] }}</span
                            >
                        </div>
                        <div class="md-layout-item md-medium-size-100 md-size-100">
                            <button
                            class="button is-success"
                            type="submit"
                            form="addDocsForm"
                            :disabled="saveDisabled"
                            >
                            <span> Add </span>
                            <span class="icon is-small">
                                <i class="far fa-save"></i>
                            </span>
                            </button>
                        </div>
                        </form>
                    </div>
                </nav>
                <nav class="panel">
                <!-- <div class="container is-fluid"> -->

                <table
                    class="table is-bordered is-striped is-hoverable is-fullwidth"
                >
                    <tr>
                    <th>Document Name</th>
                    <th>Uploaded Date</th>
                    </tr>

                    <tr v-for="doc in getDocsData" :key="doc.id">
                    <td>
                        <a
                        v-bind:href="
                            'http://127.0.0.1:8000/uploads/' + doc.doc_file
                        "
                        >{{ doc.doc_name }}
                        </a>
                    </td>
                    <td>
                        {{ doc.created_at }}
                    </td>
                    </tr>
                </table>
                </nav>

                </div>
                </md-card-content>
            </md-card>
        </div>
    </div>
</template>

<script>
let currentElement;

    import AddClientModal from './Modals/AddClientModal.vue'
    import AddEducationModal from './Modals/Addeducationmodal.vue'
    import AddEmploymentModal from './Modals/Addemploymentmodal.vue'
    import AddMeetingModal from './Modals/Addmeetingmodal.vue'
    import AddTrainingModal from './Modals/Addtrainingmodal.vue'
    import AddWorkshopModal from './Modals/Addworkshopmodal.vue'
    import AddOutcomeModal from './Modals/AddOutcomeModal.vue'
    import MeetingResourceModal from './Modals/MeetingResourceModal.vue'
    import WorkshopResourceModal from './Modals/WorkshopResourceModal.vue'
    import PhoneFormater from '../phoneNumberFormat'
    import TimeLine from '../components/Timeline/TimeLine.vue';
    import TimeLineItem from '../components/Timeline/TimeLineItem.vue';
    import  Badge  from '../components/Badge.vue';
    import FeedbackModal from "./Modals/FeedbackModal.vue";
    //   import { mapActions } from 'vuex'
    import Swal from "sweetalert2"
import { mapGetters } from 'vuex';

    export default {
        components: {
            AddClientModal,
            AddEducationModal,
            AddEmploymentModal,
            AddMeetingModal,
            AddTrainingModal,
            MeetingResourceModal,
            AddOutcomeModal,
            WorkshopResourceModal,
            FeedbackModal,
            TimeLine, TimeLineItem, Badge
        },
        created() {
            this.fetchData(this.clientID);
        },
        data() {
            return {
                tabToShow: 0,
                showModal: false,
                showfeedbackModal: false,
                showClientModal: false,
                showEducationModal: false,
                showTrainingModal: false,
                showEmploymentModal: false,
                showMeetingModal: false,
                showWorkshopModal: false,
                showMeetingResourceModal: false,
                showWorkshopResourceModal: false,
                educationEdit: {},
                trainingEdit: {},
                employmentEdit: {},
                meetingEdit: {},
                workshopEdit: {},
                clientID: this.$route.params.id,
                loading: false,
                outcomeSelected: [],
                outcomeDate: '',
                phoneFormater: new PhoneFormater(),
                groupActivities: '',
                outcomeEdit: '',
                addOutcomeForm: false,
                selectedCategoryOutcome: null,
                selectedCategoryOutcomes: [],
                doc_file: "",
                docs: "",
                doc_name: "",
                showfeedbackModal: false,

            }
        },
        computed: {
            ...mapGetters(["getDocsData"]),
            client() {
                return this.$store.state.clientModule.client
            },
            education() {
                return this.$store.state.clientModule.education
            },
            training() {
                return this.$store.state.clientModule.training
            },
            employment() {
                return this.$store.state.clientModule.employment
            },
            meeting() {
                return this.$store.state.clientModule.meeting
            },

            //SHOW MODAL MUTATIONS
            showSearchModal() {
                return this.$store.state.clientModule.showSearchModal
            },
            showSaveModal() {
                return this.$store.state.clientModule.showSaveModal
            },
            showClientapModal() {
                return this.$store.state.clientModule.showClientapModal
            },
            addOutcomeForm() {
                return this.$store.state.clientModule.addOutcomeForm
            },

            // ROOT STATES
            educations() {
                return this.$store.state.educations
            },
            trainings() {
                return this.$store.state.trainings
            },
            employments() {
                return this.$store.state.employments
            },
            meetings() {
                return this.$store.state.meetings
            },
            categories() {
                return this.$store.state.categories
            },
            DocumentNames() {
               let documentNames = this.$store.state.dropdowns.find(function (el) {
                    return el.name === "Clients Documents"
                })
                return documentNames
            }
        },
        beforeMount(){
            this.getDocs()
        },
        methods: {
             showReport(tab) {
                this.tabToShow = tab;

            },
              saveDocs() {
                let fd = new FormData();
                let vm = this;
                fd.append("doc_file", this.doc_file);
                fd.append("doc_name", this.doc_name);
                fd.append("client_id", this.clientID);
                axios
                    .post("/api/clientDocs", fd)
                    .then((response) => {

                    console.log(response);
                    Swal.fire({
                        type: "success",
                        title: "Document Saved",
                        showConfirmButton: true,
                        confirmButtonColor: "#3C63E3",
                        confirmButtonText: "OK",
                    }).then(function (isConfirm) {
                        if (isConfirm) {


                        }
                    });
                    })
                    .catch((err) => {
                    console.log(err.response.data.errors);
                    this.errors = err.response.data.errors;
                    console.log(this.errors);
                    });
                console.log(this.errors.doc_name[0]);
            },
             onFileChange(e) {
                this.doc_file = e.target.files[0];
            },

            getDocs() {
            this.$store.commit("SET_DOCS", []);
            this.$store
                .dispatch("getClientDocs", this.clientID)
                .then((response) => {
                console.log("Data :", response);

                this.$store.commit("SET_DOCS", response);
                })
                .catch((err) => {
                console.log(err.response.data.errors);
                this.errors = err.response.data.errors;
                console.log(this.errors);
                });
            },

            fetchData(ID) {
                this.loading = true
                this.$store.commit('clientModule/SET_CLIENTID', ID)
                this.$store.dispatch('clientModule/getClient')
                    .then(response => {
                        console.log(response)
                        this.$store.state.clientModule.client.outcomes.forEach(outcome => {
                            this.outcomeSelected.push(outcome.id)
                        })
                        this.loading = false

                        console.log(response.meetings)
                        this.$store.commit("SET_EDUCATIONS", response.educations)
                        this.$store.commit("SET_TRAININGS", response.trainings)
                        this.$store.commit("SET_EMPLOYMENTS", response.employments)
                        this.$store.commit("SET_MEETINGS", response.meetings)
                    })
                    .catch(errors => {
                        console.log(errors)
                    })

                // this.$store.dispatch('getEducations', this.clientID)
                // this.$store.dispatch('getTrainings', this.clientID)
                // this.$store.dispatch('getEmployments', this.clientID)
                // this.$store.dispatch('getMeetings', this.clientID)
            },

            getGroupActivities(id) {
                axios.get('/api/groupActivities')
            },

            postHighestEducation(highestEducation_id) {
                axios.post('/api/clients/highestEducation', {
                    client_id: this.clientID,
                    highestEducation_id: highestEducation_id
                })
            },
// OPEN MODAL SWITCH
            openModal(name) {
                switch (name) {
                    case 'clientModal':
                        this.showClientModal = true
                        break;
                    case 'educationModal':
                        this.educationEdit = null
                        this.showEducationModal = true
                        break;
                    case 'trainingModal':
                        this.trainingEdit = null
                        this.showTrainingModal = true
                        break;
                    case 'employmentModal':
                        this.employmentEdit = null
                        this.showEmploymentModal = true;
                        break;
                    case 'meetingModal':
                        this.meetingEdit = null
                        this.showMeetingModal = true;
                        break;
                    case 'addOutcomeForm':
                        this.meetingEdit = null
                        this.addOutcomeForm = true;
                        break;
                    case 'searchModal':
                        this.$store.commit('clientModule/SEARCH_MODAL', 'open');
                        break;
                    case 'saveModal':
                        this.$store.commit('clientModule/SAVE_MODAL', 'open');
                        break;
                    case 'ClientapModal':
                        this.$store.commit('clientModule/Clientap_Modal', 'open');
                        this.$store.dispatch('findClientap');
                        break;
                }
            },
// CLOSE MODAL SWITCH
            closeModal(name) {
                switch (name) {
                    case "feedbackModal":
                        this.showfeedbackModal = false;
                        this.fetchData(this.clientID);
                        break;
                    case 'clientModal':
                        this.showClientModal = false
                        this.fetchData(this.clientID)
                        break;
                    case 'educationModal':
                        this.showEducationModal = false
                        this.fetchData(this.clientID)
                        break;
                    case 'trainingModal':
                        this.showTrainingModal = false
                        this.fetchData(this.clientID)
                        break;
                    case 'employmentModal':
                        this.showEmploymentModal = false
                        this.fetchData(this.clientID)
                        break;
                    case 'meetingModal':
                        this.showMeetingModal = false
                        this.fetchData(this.clientID)
                        break;
                    case 'addOutcomeForm':
                        this.addOutcomeForm = false
                        this.fetchData(this.clientID)
                        break;
                    case 'searchModal':
                        this.$store.commit('clientModule/SEARCH_MODAL', 'close');
                        break;
                    case 'saveModal':
                        this.$store.commit('clientModule/SAVE_MODAL', 'close');
                        break;
                    case 'ClientapModal':
                        this.$store.commit('clientModule/Clientap_Modal', 'close');
                        break;
                    case 'MeetingResourceModal':
                        this.showMeetingResourceModal = false;
                        break;
                    case 'WorkshopResourceModal':
                        this.showWorkshopResourceModal = false;
                        break;
                }
            },
// EDIT MODAL SWITCH
            editModal(name, item) {
                switch (name) {
                    case "feedbackModal":
                        this.meetingEdit = item;
                        this.showfeedbackModal = true;
                        break;
                    case 'educationModal':
                        this.educationEdit = item
                        this.showEducationModal = true
                        break;
                    case 'trainingModal':
                        this.trainingEdit = item
                        this.showTrainingModal = true
                        break;
                    case 'employmentModal':
                        this.employmentEdit = item
                        this.showEmploymentModal = true
                        break;
                    case 'meetingModal':
                        this.meetingEdit = item
                        this.showMeetingModal = true
                        break;
                    case 'MeetingResourceModal':
                        this.showMeetingResourceModal = true;
                        this.meetingEdit = item;
                        break;
                    case 'WorkshopResourceModal':
                        this.showWorkshopResourceModal = true;
                        this.workshopEdit = item;
                        break;
                }
            },
// DELETE METHODS
            deleteClient() {
                let payload = {
                    url: '/api/clients/',
                    id: this.$route.params.id,
                    confirmtxt: 'Client has been deleted.'
                }
                this.$store.dispatch('deleteRecord', payload)
                    .then(response => {
                        if (response) {
                            this.$router.push('/clients')
                        }
                    })
            },
            deleteEducation(index, id) {
                let payload = {
                    url: '/api/education/',
                    id: id,
                    confirmtxt: 'Education record has been deleted.'
                }
                this.$store.dispatch('deleteRecord', payload)
                    .then(response => {
                        if (response) {
                            this.$store.state.educations.splice(index, 1)
                        }
                    })

            },
            deleteTraining(index, id) {
                let payload = {
                    url: '/api/training/',
                    id: id,
                    confirmtxt: 'Training record has been deleted.'
                }
                this.$store.dispatch('deleteRecord', payload)
                    .then(response => {
                        if (response) {
                            this.$store.state.trainings.splice(index, 1)
                        }
                    })

            },
            deleteEmployment(index, id) {
                let payload = {
                    url: '/api/employment/',
                    id: id,
                    confirmtxt: 'Employment record has been deleted.'
                }
                this.$store.dispatch('deleteRecord', payload)
                    .then(response => {
                        if (response) {
                            this.$store.state.employments.splice(index, 1)
                        }
                    })

            },
            deleteMeeting(index, id) {
                let payload = {
                    url: '/api/meeting/',
                    id: id,
                    confirmtxt: 'Meeting record has been deleted.'
                }
                this.$store.dispatch('deleteRecord', payload)
                    .then(response => {
                        if (response) {
                            this.$store.state.meetings.splice(index, 1)
                        }
                    })

            },
            deleteWorkshop(index, id) {
                let payload = {
                    url: '/api/workshop/',
                    id: id,
                    confirmtxt: 'Workshop record has been deleted.'
                }
                this.$store.dispatch('deleteRecord', payload)
                    .then(response => {
                        if (response) {
                            this.$store.state.workshops.splice(index, 1)
                        }
                    })

            },

           async collapse(e,id) {
                currentElement = e.target

                let content = currentElement.nextElementSibling
                this.selectedCategoryOutcomes = []
                await this.getClientOutcome(id)
                if (content) {
                    currentElement.classList.toggle("active")
                    if (content.style.display === "block") {
                        content.style.display = "none";
                    } else {
                        content.style.display = "block";
                    }
                }
            },
            getClientOutcome(id){

                this.selectedCategoryOutcome = id
                axios.get('/api/client-outcomes/'+this.clientID+'/'+
                    id
                )
                .then(response => {
                    this.selectedCategoryOutcomes = response.data
                    if(response.data.length==0){
                        currentElement.classList.remove("active");
                        let content = currentElement.nextElementSibling
                        if (content) {
                            if (content.style.display === "block") {
                                content.style.display = "none";
                            }
                        }
                    }
                })
            },

            updateOutcomes(id) {
                axios.post('/api/clients/outcomes', {
                    client_id: this.clientID,
                    outcomeSelected: id,
                })
                    .then(response => {

                    })
            },

            updateOutcomeDate() {
                axios.post('/api/clients/outcome/date',)
            }
        },

    }

</script>

<style scoped>
    .header-collapsible {
        background-color: hsl(0, 0%, 92%);
        color: hsl(0, 0%, 21%);
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        margin: 1px;
        margin-bottom: 0%;
        font-size: 15px;
        border-radius: 10px;
    }

    .active, .header-collapsible:hover {
        background-color: hsl(204, 86%, 53%);
        color: #fff;
    }

    .content-collapsible {
        padding: 0 18px;
        display: none;
        overflow: hidden;
        background-color: hsl(0, 0%, 98%);
        border-radius: 10px;
        margin-bottom: 3px;
    }

    .outcome:hover {
        /* background-color: hsl(171, 100%, 41%); */
        color: #fff;
    }
    .content li + li {
        margin-top: 0.25em;
        list-style: none;
    }

    .timeline-inverted{
        list-style: none;
    }
</style>
