import '../css/displays_app.css';
import $ from 'jquery';
import 'datatables';
import 'datatables.net-fixedheader';
import 'leaflet';
import './components/router';

import {fillTableAllTrips, fillTableInvestigators, fillTableStations} from '../js/components/dataTablesForTrip';
import fillTableCruises from '../js/components/dataTablesForCruises';
global.fillTableAllTrips = fillTableAllTrips;
global.fillTableInvestigators = fillTableInvestigators;
global.fillTableStations =fillTableStations;
global.fillTableCruises = fillTableCruises;
