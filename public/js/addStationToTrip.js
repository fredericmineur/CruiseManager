$('#add-station').click(function () {
    // console.log('add station');
    const index = +$('#widgets-counter-stations').val();
    const tmpl = $('#trip_stations').data('prototype').replace(/__name__/g, index);
    var elementStation=$(tmpl);

    // console.log(tmpl);
    $('#trip_stations').append(elementStation);
    $('#widgets-counter-stations').val(index+1);
    deleteStations(elementStation);
});

function deleteStations(contextElement){
    $('button#remove-station').click(function (){
        // the button has as attribute data-target="#block_{{ id }}"
        const target = this.dataset.target;
        console.log(target);
        $(target).remove();
    })
}

function updateCounterStations() {
    const count = $('div.block-station').length;
    $('#widgets-counter-stations').val(count);
}

function addClassForAutocomplete(contextElement){
    $('input[name$="[firstname]"]', contextElement).addClass("autocomplete-first-name");
    $('input[name$="[surname]"]', contextElement).addClass("autocomplete-surname");
    $(".autocomplete-first-name").easyAutocomplete(optionsFirstNames);
    $(".autocomplete-surname").easyAutocomplete(optionsSurnames);
}


$(document).ready(function () {
    updateCounterStations();
    deleteStations(window.document);
    addClassForAutocomplete(window.document);

});