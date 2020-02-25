function updateCounter(){
    const countTripinvestigators = $('.block-tripinvestigator').length;
    const countTripstations = $('.block-tripstation').length;
    window.counter.countTripInvestigators = countTripinvestigators;
    window.counter.countTripStations = countTripstations;
}

function handleDeleteTripInvestigatorButtons(contextElement){
    $('button#remove-trip-investigator[data-action="delete"]').click(function(){
        const target=this.dataset.target;
        $(target).parent('fieldset').remove();
    })
}

function handleDeleteTripStationButtons(contextElement){
    $('button#remove-trip-station[data-action="delete"]').click(function(){
        const target=this.dataset.target;
        $(target).parent('fieldset').remove();
    })
}

function highlightTripInvestigatorNoInvestigator(contextElement){
    $('.block-tripinvestigator').each(function(){
        const idTripInvestigatorBlock = $(this).attr('id').replace('block_', '');
        // console.log(idTripInvestigatorBlock);
        // console.log($('#'+ idTripInvestigatorBlock + '_investigatornr').val());
        if($('#'+ idTripInvestigatorBlock + '_investigatornr').val()) {
            $('input#' + idTripInvestigatorBlock + '_surname').css('background-color', '#ecf0f1');
            $('input#' + idTripInvestigatorBlock + '_firstname').css('background-color', '#ecf0f1');
            $('input#' + idTripInvestigatorBlock + '_fullname').css('background-color', '#ecf0f1');
        } else {
            const surname = $('input#' + idTripInvestigatorBlock + '_surname').val();
            const firstname = $('input#' + idTripInvestigatorBlock + '_firstname').val();

            const buttonHtml = '<div class="col-2"><a href="' + Routing.generate("create_investigator", {surname: surname, firstname: firstname})
                +'" class="btn btn-primary create-investigator" id="' + idTripInvestigatorBlock + '"  target="_blank">Create investigator</a></div>';
            $(this).children().append(buttonHtml);
        }
    });
}

function highlightTripStationNoStation(contextElement){
    $('.block-tripstation').each(function () {
        const idTripStationBlock = $(this).attr('id').replace('block_', '');
        // console.log($(this));
        if($('#' + idTripStationBlock + '_stationnr').val()) {
            $('input#' + idTripStationBlock + '_code').css('background-color', '#95a5a6');
            $('input#' + idTripStationBlock + '_deflatitude').css('background-color', '#95a5a6');
            $('input#' + idTripStationBlock + '_deflongitude').css('background-color', '#95a5a6');

        } else {
            const code = $('input#' + idTripStationBlock + '_code').val();
            const lat = $('input#' + idTripStationBlock + '_deflatitude').val();
            const long = $('input#' + idTripStationBlock + '_deflongitude').val();
            const buttonHtml = '<div class="col-2"><a href="'+ Routing.generate("create_station", {lat: lat, long: long, code: code})
                +'" class="btn btn-primary"  target="_blank">Create Station</a></div>';
            $(this).children().append(buttonHtml);
        }
    });
}

function addAutocompleteForInvestigator (indexTInvestigator) {

    var tripinvestigatorFullNameID ='trip_tripinvestigators_'+ indexTInvestigator+'_fullname';
    var tripinvestigatorSurnameID='trip_tripinvestigators_' + indexTInvestigator+'_surname';
    var tripinvestigatorFirstnameID='trip_tripinvestigators_' + indexTInvestigator+'_firstname';
    var tripinvestigatornrID='trip_tripinvestigators_'+ indexTInvestigator+'_investigatornr';

    //locate the create investigator button for the row
   var buttonCreate = $('#block_trip_tripinvestigators_' + indexTInvestigator).find('a');


    var options = {
        url: function (phrase) {
            // return Routing.generate("get_investigator_names", {searchParameter : phrase});
            // return "/investigators/getInvestigatorNames/"+phrase;
            return Routing.generate("get_investigator_names") + '/' + phrase;
        },
        getValue: function (element) {
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
            data.phrase = $("#" + tripinvestigatorFullNameID).val();
            return data;
        },
        list: {
            onChooseEvent: function() {
                $("#" +tripinvestigatornrID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().investigatorid);
                $("#" +tripinvestigatorSurnameID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().surname);
                $("#" +tripinvestigatorFirstnameID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().firstname);
                //If there is a create button, it will be deleted as the tripinvestigator is now fetched from the investigator table.
                if(buttonCreate.length>0){
                    $(buttonCreate).remove();
                }

            }
        },
        requestDelay: 400
    };

    $("#"+tripinvestigatorFullNameID).easyAutocomplete(options);
}

function addAutoCompleteForInvestigatorCampaign (indexTInvestigator) {
    var tripinvestigatorCampaignID = 'trip_tripinvestigators_' + indexTInvestigator + '_campaign';
    var tripinvestigatorCampaignNrID = 'trip_tripinvestigators_' + indexTInvestigator + '_campaignnr';

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
                console.log(valueCampaignNr);
                $('#' + tripinvestigatorCampaignNrID).val(valueCampaignNr);
            }
            //NB it the campaign name input is made empty, campaignnr is made null in the controller

        },
        requestDelay: 400
    };
    $('#'+ tripinvestigatorCampaignID).easyAutocomplete(options);
}


function addAutocompleteForStation (indexTStations)
{
    var tripstationCodeID = 'trip_tripstations_' + indexTStations + '_code';
    var tripstationLatitudeID = 'trip_tripstations_' + indexTStations + '_deflatitude';
    var tripstationLongitudeID = 'trip_tripstations_' + indexTStations + '_deflongitude';
    var tripstationStationnrID = 'trip_tripstations_' + indexTStations + '_stationnr';

    var options = {
        url: function (phrase){
            // return "/api/getStations/" + phrase;
            return Routing.generate("get_stations") + '/' + phrase;
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
        preparePostData: function (data) {
            data.phrase = $('#' + tripstationCodeID).val();
            return data;
        },
        list: {
            onChooseEvent: function () {
                var latForSelectedItemValue = $('#' + tripstationCodeID).getSelectedItemData().Lat;
                $('#' + tripstationLatitudeID).val(latForSelectedItemValue);
                var longForSelectedItemValue = $('#' + tripstationCodeID).getSelectedItemData().Long;
                $('#' + tripstationLongitudeID).val(longForSelectedItemValue);
                var stationIdForSelectedItemValue = $('#' + tripstationCodeID).getSelectedItemData().id;
                $('#' + tripstationStationnrID).val(stationIdForSelectedItemValue);
            }
        },
        requestDelay: 400
    };
    $('#' + tripstationCodeID).easyAutocomplete(options);
}



let addTripInvestigatorsStationsToTrip = (function () {
    let initObjectTI = {};
    initObjectTI.init = function () {
        counter = {countTripInvestigators: 0, countTripStations : 0};
        $('#add-tripinvestigator').click(function(){
            const index = counter.countTripInvestigators;
            const tmpl = $('#trip_tripinvestigators').data('prototype').replace(/__name__/g, index);
            const elementTripinvestigator = $(tmpl);
            $('#trip_tripinvestigators').append(elementTripinvestigator);
            window.counter.countTripInvestigators = index + 1;
            handleDeleteTripInvestigatorButtons(elementTripinvestigator);
            addAutocompleteForInvestigator(index);
            addAutoCompleteForInvestigatorCampaign(index);


        });
        $('#add-tripstation').click(function(){
            const index = counter.countTripStations;
            const tmpl = $('#trip_tripstations').data('prototype').replace(/__name__/g, index);
            const elementTripstation = $(tmpl);
            $('#trip_tripstations').append(elementTripstation);
            window.counter.countTripStations = index + 1;
            handleDeleteTripStationButtons(elementTripstation);
            addAutocompleteForStation(index);
        });
        updateCounter();
        handleDeleteTripInvestigatorButtons(window.document);
        handleDeleteTripStationButtons(window.document);
        highlightTripInvestigatorNoInvestigator(window.document);
        highlightTripStationNoStation(window.document);

        $('input[id$=fullname]').each(function(){
            const indexTripinvestigator = $(this).attr('id').replace('trip_tripinvestigators_', '').replace('_fullname', '');
            addAutocompleteForInvestigator(indexTripinvestigator);
            addAutoCompleteForInvestigatorCampaign(indexTripinvestigator);
        });

        $('input[id$=code]').each(function(){
            const indexTripstation = $(this).attr('id').replace('trip_tripstations_', '').replace('_code','');
            addAutocompleteForStation(indexTripstation);
        });


    }
    return initObjectTI;
})();


