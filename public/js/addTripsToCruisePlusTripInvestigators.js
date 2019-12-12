$('#add-trip').click(function(){
    //Getting the index for the div
    // const index = $('#cruise_trips div.form-group').length;
    const index = +$('#widgets-counter-trips').val(); //unary '+' to convert string to number

    //Getting the prototype (code used to generate the html template)...and adding the index...e.g. position outside the present length
    const tmpl = $('#cruise_trips').data('prototype').replace(/__name__/g, index);
    //Injection of the new code
    var element = $(tmpl)
    $('#cruise_trips').append(element);

    //Add 1 to the index (for counter)
    $('#widgets-counter-trips').val(index+1);

    handleAddButtonsTripInvestigators(element);
    handleDeleteButtonsTrips();
    handleCloneButtonsTrips();
    updateCounterTrips();




})

function  handleAddButtonsTripInvestigators(contextElement){
    $( '.add-tripinvestigator',contextElement).click(function(){
        // Getting the block number/id for the trip
        var blockTrip = this.attributes['data-block']['nodeValue'];
        blockTrip= blockTrip.replace('cruise_trips_', '');

        // Name of the class to be given to the individual counter
        const classWidgetCounter = 'widgets-counter-tripinvestigators-' + blockTrip;

        // Get the name of the relevant DOM id for the block
        const blockId = 'block_cruise_trips_' + blockTrip;
        // console.log(classWidgetCounter);

        //Get the widget counter (hidden input) that is inside the relevant block and assign it a class
        if (!$('div#' + blockId + ' input[id=widgets-counter-tripinvestigators]').hasClass(classWidgetCounter)){
            $('div#' + blockId + ' input[id=widgets-counter-tripinvestigators]').addClass(classWidgetCounter);
        }

        //Get the value of the counter
        const index = +$('.widgets-counter-tripinvestigators-'+blockTrip).val();
        // id for the division containing the prototype
        const idForPrototype = '#cruise_trips_' + blockTrip + '_tripinvestigators';
        const tmpl = $(idForPrototype).data('prototype').replace(/__name__/g, index);
        var element = $(tmpl);

        $(idForPrototype).append(element);
        $('.widgets-counter-tripinvestigators-'+ blockTrip).val(index + 1);
        // console.log(tmpl);


        updateCounterTripinvestigators(blockId, blockTrip);
        handleDeleteButtonsTripinvestigators(element, blockId, blockTrip);

    })
}

function handleDeleteButtonsTripinvestigators(contextElement, blockId, blockTrip){


    $('button#remove-trip-investigator[data-action="delete"]', contextElement).click(function(){
        // console.log('delete !!!!');
        const target = this.dataset.target;
        // console.log('target '+ target);
        // console.log(this.dataset);
        $(target).remove();
        // console.log('delete!!!!');
    })



}

function updateCounterTripinvestigators(blockId, blockTrip) {

    //blockId = 'block_cruise_trips_' + blockTrip; e.g., block_cruise_trips_1
    //blockTrip is the just the digit: e.g., 1
    const count = $('fieldset div[id^='+ blockId + '_tripinvestigators_]').length;
    // console.log(blockId + ' count= ' + count);

    $('.widgets-counter-tripinvestigators-'+ blockTrip).val(count);

}




// function handleCloneButtonsTrips(){
//     $('button#clone-trip[data-action="clone"]').click(function(){
//         const target = this.dataset.target;
//         // const targetPrototype = $(target).data('prototype');
//         // const tmpl =
//         const cont = (target+'_tripinvestigators').replace(/block_/, '');
//         console.log(cont)
//         const tmpl = $(cont).data('prototype');
//         console.log(tmpl);
//         // console.log(target);
//         // console.log(tmpl);
//         // console.log(target);
//         // $(target).clone().appendTo("#cruise_trips");
//     //     const clonedTrip = $(target).clone();
//     //     console.log(clonedTrip);
//     })
// }


function handleCloneButtonsTrips(){
    $('button#clone-trip[data-action="clone"]').click(function(){
        // const index ='67';
        const index = +$('#widgets-counter-trips').val();
        const target = this.dataset.target;
        // console.log('clone' + index)

        const clonedTrip = $(target).clone();

        // console.log(clonedTrip['0'].innerHTML.replace(/_trips_[0-9]/g, '_trips_' + index));
        // console.log(clonedTrip);

        /*
        NB Somehow unable to modify properties of the object clonedTrip (declared as "let clonedTrip").
        Therefore, just extracting the innerHTML code, modify it (also removing the <a> tag linking to trip_edit (as the trip as not been submitted yet),
        and appending it to the form
         */

        let clonedTripInnerHtml = clonedTrip['0'].innerHTML.replace(/_trips_\d+/g, '_trips_' + index);
        //
        clonedTripInnerHtml = clonedTripInnerHtml.replace(/\[trips\]\[\d+\]/g, '[trips][' + index + ']');
        clonedTripInnerHtml = "<fieldset class='form-group'>"
            + "<div id='block_cruise_trips_" + index + "' class=form-group'>"
            + clonedTripInnerHtml + "</div></fieldset>";



        // // clonedTripInnerHtml = clonedTripInnerHtml.replace(/<a href="\/trips\/\d+" class="btn btn-primary">Add\/remove investigators<\/a>/, '');
        clonedTripInnerHtml = clonedTripInnerHtml.replace(/<a href="\/trips\/\d+.*\/a>/, '');
        // console.log(clonedTripInnerHtml);
        $(clonedTripInnerHtml).appendTo('div#cruise_trips');
        $('#widgets-counter-trips').val(index+1);
        // console.log(clonedTripInnerHtml);

        handleDeleteButtonsTrips();
        updateCounterTrips();
    })
}

function moveTripDownButton(){

}


function handleDeleteButtonsTrips(){
    $('button#remove-trip[data-action="delete"]').click(function(){
        const target=this.dataset.target;
        //'this' is the button and 'dataset' are the attributes, target "#block_cruise_trips_0
        // remove the whole div
        // console.log(target);

        $(target).parent('fieldset').remove();
    })
}

function updateCounterTrips(){
    // const count = $('#cruise_trips div.form-group').length;
    const count = $('input[id$=startdate]').length;
    $('#widgets-counter-trips').val(count);


}


handleDeleteButtonsTrips();
handleAddButtonsTripInvestigators(window.document);
updateCounterTrips();