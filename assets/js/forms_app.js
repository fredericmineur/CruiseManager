import '../css/forms_app.css';

import $ from 'jquery';

import 'easy-autocomplete';
import 'bootstrap-datepicker';
import 'select2';
import addTripsAndInvestigators from '../js/components/addTripsToCruise.js';
import addCampaigns from "../js/components/addCampaignsToCruise.js";
import addTripInvestigatorsStationsToTrip from '../js/components/addTripinvestigatorsStationToTrip';
import stationDMStoDD from '../js/components/formStationDMStoDD';
import investigatorAutofill from '../js/components/formInvestigatorAutofill';
import campaignAutofill from '../js/components/formCampaignAutofill';
import './components/router';



global.addTripsAndInvestigators =  addTripsAndInvestigators;
global.addCampaigns = addCampaigns;
global.addTripInvestigatorsStationsToTrip = addTripInvestigatorsStationsToTrip;
global.stationDMStoDD = stationDMStoDD;
global.investigatorAutofill = investigatorAutofill;
global.campaignAutofill = campaignAutofill;


