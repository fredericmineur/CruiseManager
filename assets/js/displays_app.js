import '../css/displays_app.css';
import $ from 'jquery';
import 'datatables';
import 'datatables.net-fixedheader';
import 'leaflet';
import './components/router';

import {fillTableAllTrips, fillTableTripInvestigators, fillTableStations} from '../js/components/dataTablesForTrip';
import fillTableCruises from '../js/components/dataTablesForCruises';
import fillTableCampaigns from '../js/components/dataTablesForCampaigns';
import fillTablePeriods from '../js/components/dataTablesForPeriods';
import fillTableInvestigators from '../js/components/dataTablesForInvestigators';
import tableSingleCampaign from '../js/components/dataTablesForCampaign_single';


global.fillTableAllTrips = fillTableAllTrips;
global.fillTableTripInvestigators = fillTableTripInvestigators;
global.fillTableStations = fillTableStations;
global.fillTableCruises =  fillTableCruises;
global.fillTableCampaigns = fillTableCampaigns;
global.fillTablePeriods = fillTablePeriods;
global.fillTableInvestigators = fillTableInvestigators;
global.tableSingleCampaign = tableSingleCampaign;
