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
        }
    });
}

function highlightTripStationNoStation(contextElement){
    $('.block-tripstation').each(function () {
        const idTripStationBlock = $(this).attr('id').replace('block_', '');
        console.log($(this));
        if($('#' + idTripStationBlock + '_stationnr').val()) {
            $('input#' + idTripStationBlock + '_code').css('background-color', '#f3a29a');
            $('input#' + idTripStationBlock + '_deflatitude').css('background-color', '#f3a29a');
            $('input#' + idTripStationBlock + '_deflongitude').css('background-color', '#f3a29a');
            // let
            // $(this).children().append('');
        }
    });
}

function addAutocompleteForInvestigator (indexTInvestigator) {


    var options = {
        url: function (phrase) {
            return "/investigators/getInvestigatorNames/"+phrase
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
    }
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


    }
    return initObjectTI;
})();


