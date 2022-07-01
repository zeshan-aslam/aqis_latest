import { getField } from 'vuex-map-fields';

export default {

    getField,

    loggedIn(state) {
        return state.user.token !== null
    },
    getLanguages(state) {
        return state.languages
    },
    getCurrentNoc(state) {
        return state.current_noc
    },
    getDocsData(state) {
        return state.docs
    },
    getApptemplateData(state) {
        return state.aptemplates
    },

    getPreviousNoc(state) {
        return state.previous_noc
    },

    getCities(state) {
        return state.cities
    },

    filterClients (state) {
    	state.clients.filter(client => client.first_name);
    	return state.clients;
    },

    facilitators (state) {
        return state.staffList.filter(el => {
            return el.position === "AQIS Facilitator"
        })
    }

}
