import '../css/forms_app.css';

import $ from 'jquery';

import 'easy-autocomplete';
import 'bootstrap-datepicker';
import 'select2';
import addTripsAndInvestigators from '../js/components/addTripsToCruise.js';
global.addTripsAndInvestigators =  addTripsAndInvestigators;
import addCampaigns from "../js/components/addCampaignsToCruise.js";
global.addCampaigns = addCampaigns;
import addTripInvestigatorsStationsToTrip from '../js/components/addTripinvestigatorsStationToTrip';
global.addTripInvestigatorsStationsToTrip = addTripInvestigatorsStationsToTrip;
import './components/router';
