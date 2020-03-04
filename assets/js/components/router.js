const routes = require('../../../public/js/fos_js_routes.json');

import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources';
Routing.setRoutingData(routes);
window.Routing = Routing;
export default Routing;


// https://stackoverflow.com/questions/54053137/webpack-fosjsroutingbundle-integration-with-symfony-flex-and-angular

// https://symfonycasts.com/screencast/javascript-webpack/fosjsroutingbundle