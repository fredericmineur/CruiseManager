function cloneTrip(contextElement){
    $('button.clone-trip[data-action="clone"]', contextElement).click(function(){
        const index = counter.countTrip;

        //gets the target of the click on button event
        const target = this.dataset.target;
        //Select every input inside the target...loop through them ....copy the value of the input, and set it as value into the innerHTML  (attr() is the HTML attribute)
        $("input", target).each(function (idx, inputElement) {
             var ie = $(inputElement);
             ie.attr("value", ie.val());
        })
        const clonedTrip = $(target).parent('fieldset').clone();
        //  NB Somehow unable to modify properties of the object clonedTrip (declared as "let clonedTrip").
        //  Therefore, just extracting the innerHTML code, modify it (also removing the <a> tag linking to trip_edit (as the trip as not been submitted yet),
        //  and appending it to the form

        //update the attributes id (e.g. cruise_trips_0_tripinvestigators_0_surname to cruise_trips_3_tripinvestigators_0_surname)
        let clonedTripHTML = clonedTrip['0'].innerHTML.replace(/_trips_\d+/g, '_trips_' + index);
        // console.log(clonedTrip['0'].innerHTML);

        //update the attributes name (e.g. name="cruise[trips][0][tripinvestigators][0][surname]" to name="cruise[trips][3][tripinvestigators][0][surname]"
        clonedTripHTML = clonedTripHTML.replace(/\[trips\]\[\d+\]/g, '[trips][' + index + ']');

        clonedTripHTML = "<fieldset class='form-group'>" + clonedTripHTML + "</fieldset>";

        //REMOVE THE CLONE BUTTON (for simplification no event handler for that functionality)...
        // clonedTripHTML = clonedTripHTML.replace(/<button type="button" class="btn btn-primary clone-trip" data-action="clone" data-id="cruise_trips_\d+" data-target="#block_cruise_trips_\d+"><i class="fa fa-clone fa-2x"><\/i><\/button>/,'');

        // clonedTripHTML = clonedTripHTML.replace(/<button type="button" class="btn btn-primary clone-trip" data-action="clone" data-id="cruise_trips_\d+" data-target="#block_cruise_trips_\d+">[\s\n]*<i class="fa fa-clone fa-2x"><\/i>[\s\n]*<\/button>/,'');


        const clonedTripElement = $(clonedTripHTML);

        $('div#cruise_trips').append(clonedTripElement);

        //Initialize the new tripinvestigators counter for the cloned trip
        // with the number of tripinvestigators in the cloned trip
        const countTripInvestigator = $('[id^=block_cruise_trips_'+index+'_tripinvestigators]').length;

        window.counter['block_cruise_trips_' + index]=countTripInvestigator;

        //Updating the number of trips
        window.counter.countTrip = index +1;

        /// !!!!! context ELEMENT?????
        addTripInvestigatorsHandler(clonedTripElement);
        deleteTripinvestigators(clonedTripElement);

        addTripStationHandler(clonedTripElement);
        deleteTripStations(clonedTripElement);

        cloneTrip(clonedTripElement);
        deleteTrip(clonedTripElement);

        changeAttributesForCardCollapse(clonedTripElement);

        displayCounterValues(clonedTripElement);


        //add date picker to the newly cloned trip
        $('.js-datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });


    })
}

function deleteTrip(contextElement){
    // 'button#remove-trip[data-action="delete"]'  'button[data-id="'+idBlock+'"][data-action="delete"]'
    $('button.remove-trip[data-action="delete"]', contextElement).click(function(){

        // as we have created in Twig a 'data-target' attribute with the id selector of the block
        const target=this.dataset.target; //eg. '#block_cruise_trips_2'
        const blockTrip = target.replace('#', '');
        //delete the key/pair of the corresponding trip in the counter
        delete window.counter[blockTrip];

        //remove the whole trip section in the DOM
        $(target).parent('fieldset').remove();


    })
}

function updateCounterTrips(){
    //counting the number of trip sections and allocate it to the counter
    const count = $('.block-trip').length;
    window.counter.countTrip = count;
    // console.log(counter);

    //Initializing or updating the number of tripinvestigators for each trip (looping through 'block-trip'S
    $('.block-trip').each(function(){
        // console.log (this);
        let idBlockTrip = this['id'];
        window.counter[idBlockTrip] = {};
        // console.log('idBlockTrip = ' + idBlockTrip);
        // idBlockTrip = idBlockTrip.replace('block_', '');
        const countTripInvestigatorForObj = $('.block-tripinvestigator[id^='+idBlockTrip+']').length;
        window.counter[idBlockTrip]['investigators']=countTripInvestigatorForObj;
        const countTripStationForOBJ = $('.block-tripstation[id^='+ idBlockTrip + ']').length;
        window.counter[idBlockTrip]['stations'] = countTripStationForOBJ;
    })
    // console.log(counter);
}

function addTripStationHandler(contextElement){
    $('.add-tripstation', contextElement).click(function(e){

        // const idBlockTrip = $(e.target).parents()[3]['id'];// e .g block_cruise_trips_0
        const idBlockTrip = $(e.target).parents()[3]['id'];// e .g block_cruise_trips_0
        const idTrip = (idBlockTrip).replace('block_cruise_trips_', ''); //e.g. 0
        const indexTStations = window.counter[idBlockTrip]['stations'];

        const tmpl = $('div[id=cruise_trips_' + idTrip + '_tripstations]').data('prototype').replace(/_tripstations_(__name__|\d+)/g, '_tripstations_'+ indexTStations);
        const tmpl2 = tmpl.replace(/\[tripstations\]\[(__name__|\d+)\]/g, '[tripstations]['+indexTStations+']');

        const elementTripStation = $(tmpl2);

        $('div[id=cruise_trips_' + idTrip + '_tripstations]').append(elementTripStation);

        window.counter[idBlockTrip]['stations']=indexTStations + 1;

        // console.log(counter);

        deleteTripStations(elementTripStation);
        addAutocompleteForStation(idTrip, indexTStations, 'new');
        displayCounterValues(contextElement);

        warningEmptyStations (contextElement);


    });
}


function deleteTripStations(contextElement) {
    $('button#remove-trip-station[data-action="delete"]', contextElement).click(function(){
        const target = this.dataset.target;
        $(target).parent('fieldset').remove();
        displayCounterValues(contextElement);
    })
}

function  addTripInvestigatorsHandler(contextElement){
    //Put the event as argument, as it allow us to get info on the target button
    $( '.add-tripinvestigator',contextElement).click(function(e){


        //console.log(contextElement);
        //e.target.parentElement.parentElement is actually the block of html code on which is located the clicked button
        // (all cases, whatever contextElement put as parameter, corresponding to a trip).
        // e.g. <div id="block_cruise_trips_0" class="form-group">....</div>

        const idBlockTrip = $(e.target).parents()[3]['id'];// e .g block_cruise_trips_0

        const idTrip = (idBlockTrip).replace('block_cruise_trips_', ''); //e.g. 0
        const indexTInvestigators = window.counter[idBlockTrip]['investigators'];
        // console.log('index');console.log(idBlockTrip);console.log(window.counter);console.log(indexInvestigators);console.log(idTrip);

        //Replace the generic with adequates labels for the tripinvestigator in the prototype
        //Also, the tripinvestigator prototype that is produced with new trips has already some (wrong) tripinvestigator index by default (instead of '__name__')
        const tmpl = $('div[id=cruise_trips_' + idTrip + '_tripinvestigators]').data('prototype').replace(/_tripinvestigators_(__name__|\d+)/g, '_tripinvestigators_'+ indexTInvestigators);
        const tmpl2 = tmpl.replace(/\[tripinvestigators\]\[(__name__|\d+)\]/g, '[tripinvestigators]['+indexTInvestigators+']');

        const elementTripinvestigator = $(tmpl2);
        //console.log('elementTripinvestigator');
        //console.log(tmpl);

        //append the modified prototype to the section (ad hoc trip section)
        $('div[id=cruise_trips_' + idTrip + '_tripinvestigators]').append(elementTripinvestigator);

        //update the counter of investigators for the trip
        window.counter[idBlockTrip]['investigators']=indexTInvestigators + 1;

        deleteTripinvestigators(elementTripinvestigator);
        addAutocompleteForInvestigator(idTrip, indexTInvestigators, 'new');
        addAutoCompleteForInvestigatorCampaign(idTrip, indexTInvestigators);

        displayCounterValues(contextElement);
        warningEmptyTripinvestigators (contextElement);

        // console.log(counter);

    })
}

function deleteTripinvestigators(contextElement){
    $('button#remove-trip-investigator[data-action="delete"]', contextElement).click(function(){
        const target = this.dataset.target;
        $(target).parent('fieldset').remove();

        displayCounterValues(contextElement);
    })

}

// see http://easyautocomplete.com/example/ajax-post

function addAutocompleteForStation(indexTrip, indexTStations, categTStation){
    var tripstationID = 'cruise_trips_'+ indexTrip + '_tripstations_' + indexTStations;
    var tripstationCodeID = tripstationID + '_code';
    var tripstationLatitudeID = tripstationID + '_deflatitude';
    var tripstationLongitudeID = tripstationID + '_deflongitude';
    var tripstationStationnrID = tripstationID + '_stationnr';

    var options = {
        url: function(phrase){
            return "/api/getStations/" + phrase;
        },
        getValue: function (element) {
            return element.stationCode;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.phrase = $("#"+tripstationCodeID).val();
            return data;
        },
        //https://stackoverflow.com/questions/35502054/jquery-easyautocomplete-not-working-properly
        //http://easyautocomplete.com/example/select-event
        list: {
            onChooseEvent: function() {
                if (categTStation == 'new') {
                    var latForSelectedItemValue = $('#' + tripstationCodeID).getSelectedItemData().Lat;
                    $('#' + tripstationLatitudeID).val(latForSelectedItemValue);
                    var longForSelectedItemValue = $('#' + tripstationCodeID).getSelectedItemData().Long;
                    $('#' + tripstationLongitudeID).val(longForSelectedItemValue);
                }
                var stationIdForSelectedItemValue = $('#' + tripstationCodeID).getSelectedItemData().id;
                $('#' + tripstationStationnrID).val(stationIdForSelectedItemValue);
            }
        },

        requestDelay: 400

    };

    $('#' + tripstationCodeID).easyAutocomplete(options);

}



function addAutocompleteForInvestigator(indexTrip, indexTInvestigators, categTInvestigator){

    var tripinvestigatorFullNameID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_fullname';
    var tripinvestigatorSurnameID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_surname';
    var tripinvestigatorFirstnameID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_firstname';
    var tripinvestigatornrID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_investigatornr';

    var options = {
        url: function(phrase) {
            return "/investigators/getInvestigatorNames/"+phrase
        },

        getValue: function(element) {
            return element.fullname;
        },

        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.phrase = $("#"+tripinvestigatorFullNameID).val();
            return data;
        },

        list: {
            onChooseEvent: function() {

                $("#" +tripinvestigatornrID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().investigatorid);
                if (categTInvestigator == 'new'){
                    $("#" +tripinvestigatorSurnameID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().surname);
                    $("#" +tripinvestigatorFirstnameID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().firstname);
                }

            }
        },

        requestDelay: 400
    };
    $("#"+tripinvestigatorFullNameID).easyAutocomplete(options);
}

function addAutoCompleteForInvestigatorCampaign (indexTrip, indexTInvestigator) {
    var tripinvestigatorCampaignID = 'cruise_trips_'+ indexTrip + '_tripinvestigators_' + indexTInvestigator + '_campaign';
    var tripinvestigatorCampaignNrID = 'cruise_trips_'+ indexTrip + '_tripinvestigators_' + indexTInvestigator + '_campaignnr';

    var options = {
        url: function (phrase) {
            //return "/api/campaignsNames/" + phrase;
            // console.log(Routing.generate("api_campaign_names_search", {'search': phrase}));
            return Routing.generate("api_campaign_names_search") + '/' + phrase;
        },
        getValue: function (element) {
            return element.name;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },
        preparePostData: function (data) {
            data.phrase = $('#' + tripinvestigatorCampaignID).val();
            return data;
        },
        list: {
            onChooseEvent: function () {
                var valueCampaignNr = $('#' + tripinvestigatorCampaignID).getSelectedItemData().campaignid;
                $('#' + tripinvestigatorCampaignNrID).val(valueCampaignNr);
            }
            //NB it the campaign name input is made empty, campaignnr is made null in the controller

        },
        requestDelay: 400
    };
    $('#'+ tripinvestigatorCampaignID).easyAutocomplete(options);
}

function changeAttributesForCardCollapse(contextElement){
    $('.block-trip', contextElement).each(function(){
        // console.log('block');
        const idBlockTrip = $(this).attr('id');
        $(this).find('#heading-investigator').attr('href', '#collapse-investigator-' + idBlockTrip);
        $(this).find('#collapse-investigator').attr('id', 'collapse-investigator-' + idBlockTrip);
        $(this).find('#heading-station').attr('href', '#collapse-station-' + idBlockTrip);
        $(this).find('#collapse-station').attr('id', 'collapse-station-' + idBlockTrip);

        // console.log($(this).find('#collapse-investigator'));
    });


    // $("#collapse-investigator").attr("id", "collapse-inv");
}

// ??///  TO REDO
// http://devmidas/cruises/1976/edit

function displayCounterValues(contextElement) {
    $("span.counter").each(function(){
        //select the bootstrap card to which correspond the counter
    var card = $(this).parents()[3];
    var count =0;
    if($(card).find('.block-tripinvestigator').length >0) {
        count = $(card).find('.block-tripinvestigator').length;
    }
    if($(card).find('.block-tripstation').length >0) {
        count = $(card).find('.block-tripstation').length;
    }

    $(this).text(count);

    })

        //https://www.w3schools.com/jquery/html_text.asp
}


function addAutoCompleteDestinations(indexTrip) {
    var options = {
        url: "/api/list_trip_destinations",
        getValue: "destinationlist",
        list: {
            maxNumberOfElements: 10,
            match: {
                enabled: true
            }
        }
    }

    $('#cruise_trips_' + indexTrip + '_destinationarea').easyAutocomplete(options);
}


function removeDeleteTripButtons(contextElement, allTripsRemoveDeleteTripFunctionality){

    // console.log(allTripsRemoveDeleteTripFunctionality.length);
    if (allTripsRemoveDeleteTripFunctionality){



    allTripsRemoveDeleteTripFunctionality.forEach(function (trip, index) {
        // 'trip' has the following format: Object { 3675: true }
        var tripId = Object.keys(trip)[0];
        var removeTripBoolean  = Object.values(trip)[0];

        if(removeTripBoolean){
            var elementTrip =  $('input[id$=_tripid][value=' + tripId + ']').parents()[2];
            var rowWithdeleteButton = $(elementTrip).children()[0];
            var deleteButtonColumn = $(rowWithdeleteButton).children()[3];
            var deleteButton = $(deleteButtonColumn).children()[0];
            $(deleteButton).remove();

        }
    });
    }
}

//SYMFONY doesn't deal well with subcollections (i.e. cruise->trip-> tripinvestigators)
// For instance, when a new trip is created and some trip investigators are added and left empty,
// constraints usually applied to trip investigators are not applied
// and NULL trip investigators are submitted with the cruise (-> symfony error)
// In the case of an existing trip, NULL trip investigators are automatically discarded at submission.

function warningEmptyTripinvestigators (contextElement){
    $('button[type="submit"]').click(function(eventClick){
        if($('input[id$=_surname]').length >0 &&  $.trim($('input[id$=_surname]').val())==''  ) {
            alert('Empty trip investigator(s) !!! \n Please WAIT after closing this warning! \n And press your browser back button if\n ' +
                 '"Expected argument of type..." appears');
        }
    });
}

function warningEmptyStations (contextElement) {
    $('button[type="submit"]').click(function(eventClick){
        if($('input[id$=_code]').length >0 &&  $.trim($('input[id$=_code]').val())==''  ) {
            alert('Empty trip station(s) !!! \n Please WAIT after closing this warning! \n And press your browser back button if\n ' +
                '"Expected argument of type..." appears');
        }
    });
}




// Function to initiate the whole module
let addTripsAndInvestigators = (function () {

    let initObjectTI = {};

    initObjectTI.init = function (allTripsRemoveDeleteTripFunctionality, mode){


        window.counter = {countTrip: 0};

        $('#add-trip').click(function(e){
            //getting the number of trips from the counter
            const index = counter.countTrip;

            //Getting the prototype (code used to generate the html template)...and adding the index...e.g. position outside the present length
            const tmpl = $('#cruise_trips').data('prototype').replace(/__name__/g, index);
            //Injection of the new code
            var elementTrip = $(tmpl);
            $('#cruise_trips').append(elementTrip);

            addAutoCompleteDestinations(index);

            //Starting the tripinvestigators and tripstations counter for the trip
            counter['block_cruise_trips_'+ index] = {};
            counter['block_cruise_trips_'+ index] ['investigators']= 0;
            counter['block_cruise_trips_'+ index] ['stations']= 0;


            //Add 1 to the index (for counter)
            counter.countTrip = index + 1;

            //bind the event handler to the 'remove-trip', 'clone trip' , and 'add investigator' buttons that have been created
            deleteTrip(elementTrip);
            cloneTrip(elementTrip);
            addTripInvestigatorsHandler(elementTrip);
            $('.js-datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });
            //check the events linked ot the button
            // console.log($._data($('[data-action="delete"]')[0], 'events'));

            addTripStationHandler(elementTrip);
            deleteTripStations(elementTrip);

            changeAttributesForCardCollapse(elementTrip);

            displayCounterValues(elementTrip);

            // warningEmptyTripinvestigators(elementTrip);

            // console.log(counter);

        });

        $('.js-datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });


        updateCounterTrips();
        deleteTrip(window.document);


        if(mode = 'edit') {
            removeDeleteTripButtons(window.document, allTripsRemoveDeleteTripFunctionality);
        }


        cloneTrip(window.document);
        addTripInvestigatorsHandler(window.document);
        deleteTripinvestigators(window.document);

        addTripStationHandler(window.document);
        deleteTripStations(window.document);

        changeAttributesForCardCollapse(window.document);

        displayCounterValues(window.document);

        // warningEmptyTripinvestigators (window.document)



        // NB The counter is in the following structure : { countTrip: 3, block_cruise_trips_0: {...}, block_cruise_trips_1: {....}, block_cruise_trips_2: {....} }
        for (var property in counter) {
            if (property.substring(0, 19)==='block_cruise_trips_'){
                var indexTrip = property.substring(19);
                addAutoCompleteDestinations(indexTrip);

                var numberOfinvestigators = counter[property]['investigators'];
                for (var i = 0; i < numberOfinvestigators; i++) {
                    addAutocompleteForInvestigator(indexTrip, i, 'old');
                    addAutoCompleteForInvestigatorCampaign(indexTrip, i);
                }

                var numberOfStations = counter[property]['stations'];
                for (var i = 0; i < numberOfStations; i++) {
                    addAutocompleteForStation(indexTrip, i, 'old')
                }
            }
        }



    }

    return initObjectTI;

})();

// export default  addTripsAndInvestigators;



