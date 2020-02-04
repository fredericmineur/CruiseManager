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
            $('input#' + idTripInvestigatorBlock + '_surname').css('background-color', '#95c9ec');
            $('input#' + idTripInvestigatorBlock + '_firstname').css('background-color', '#95c9ec');
            $('input#' + idTripInvestigatorBlock + '_fullname').css('background-color', '#95c9ec');
        } else {
            const surname = $('input#' + idTripInvestigatorBlock + '_surname').val();
            const firstname = $('input#' + idTripInvestigatorBlock + '_firstname').val();
            const buttonHtml = '<div class="col-2"><a href="' + Routing.generate("create_investigator", {surname: surname, firstname: firstname})
                +'" class="btn btn-primary"  target="_blank">Create investigator</a></div>';
            $(this).children().append(buttonHtml);
        }
    });
}

function highlightTripStationNoStation(contextElement){
    $('.block-tripstation').each(function () {
        const idTripStationBlock = $(this).attr('id').replace('block_', '');
        // console.log($(this));
        if($('#' + idTripStationBlock + '_stationnr').val()) {
            $('input#' + idTripStationBlock + '_code').css('background-color', '#f3a29a');
            $('input#' + idTripStationBlock + '_deflatitude').css('background-color', '#f3a29a');
            $('input#' + idTripStationBlock + '_deflongitude').css('background-color', '#f3a29a');

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
    console.log('autocomplete');
    console.log(tripinvestigatorFullNameID);


    var options = {
        url: function (phrase) {
            return Routing.generate("get_investigator_names", {searchParameter : phrase});
            // return "/investigators/getInvestigatorNames/"+phrase;
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
            }
        },
        requestDelay: 400
    };
    console.log('start autocomplete');
    $("#"+tripinvestigatorFullNameID).easyAutocomplete(options);
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

        });
        $('#add-tripstation').click(function(){
            const index = counter.countTripStations;
            const tmpl = $('#trip_tripstations').data('prototype').replace(/__name__/g, index);
            const elementTripstation = $(tmpl);
            $('#trip_tripstations').append(elementTripstation);
            window.counter.countTripStations = index + 1;
            handleDeleteTripStationButtons(elementTripstation);
        });
        updateCounter();
        handleDeleteTripInvestigatorButtons(window.document);
        handleDeleteTripStationButtons(window.document);
        highlightTripInvestigatorNoInvestigator(window.document);
        highlightTripStationNoStation(window.document);

        $('input[id$=fullname]').each(function(){
            const indexTripinvestigator = $(this).attr('id').replace('trip_tripinvestigators_', '').replace('_fullname', '');
            // addAutocompleteForInvestigator(indexTripinvestigator);
        })


    }
    return initObjectTI;
})();


