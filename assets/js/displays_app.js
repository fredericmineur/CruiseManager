import '../css/displays_app.css';
import $ from 'jquery';
import 'datatables';
import 'datatables.net-fixedheader';
import 'leaflet';
import './components/router';

import {fillTableAllTrips, fillTableInvestigators, fillTableStations} from '../js/components/dataTablesForTrip';
global.fillTableAllTrips = fillTableAllTrips;
global.fillTableInvestigators = fillTableInvestigators;
global.fillTableStations =fillTableStations;

