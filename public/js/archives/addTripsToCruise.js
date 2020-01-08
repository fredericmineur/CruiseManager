$('#add-trip').click(function(){
    //Getting the index for the div
    // const index = $('#cruise_trips div.form-group').length;
    const index = +$('#widgets-counter-trips').val(); //unary '+' to convert string to number
    console.log('add' + index);
    //Getting the prototype (code used to generate the html template)...and adding the index...e.g. position outside the present length
    const tmpl = $('#cruise_trips').data('prototype').replace(/__name__/g, index);

    const element = $(tmpl);
    //Injection of the new code
    $('#cruise_trips').append(element);

    //Add 1 to the index (for counter)
    $('#widgets-counter-trips').val(index+1);

    handleDeleteButtonsTrips(element);
    handleCloneButtonsTrips(element);
    updateCounterTrips();


})




function handleCloneButtonsTrips(contextElement){
    $('button#clone-trip[data-action="clone"]', contextElement).click(function(){
        // const index ='67';
        const index = +$('#widgets-counter-trips').val();
        const target = this.dataset.target;
        console.log('clone' + index);

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

        //Now the whole element, as a string, has been modified, convert it in an element/object
        //Using fake tag <div/>.... see https://stackoverflow.com/questions/11047670/creating-a-jquery-object-from-a-big-html-string
        // $(clonedTripInnerHtml).remove('button[data-action="clone"]');
        console.log(typeof (clonedTripInnerHtml));

        // let clonedElement = $('<div/>').html(clonedTripInnerHtml).contents();
        // console.log(clonedElement);

        let clonedElement = $($.parseHTML(clonedTripInnerHtml));
        $(clonedElement).remove('a');


        console.log(clonedElement);



        // // clonedTripInnerHtml = clonedTripInnerHtml.replace(/<a href="\/trips\/\d+" class="btn btn-primary">Add\/remove investigators<\/a>/, '');
        // clonedTripInnerHtml = clonedTripInnerHtml.replace(/<a href="\/trips\/\d+.*\/a>/, '');
        // console.log(clonedTripInnerHtml);
        //Delete Add investigator button, delete Clone button
        clonedElement.remove('a');



        $(clonedTripInnerHtml).appendTo('div#cruise_trips');
        $('#widgets-counter-trips').val(index+1);
        // console.log(clonedTripInnerHtml);

        handleCloneButtonsTrips(clonedElement);
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
handleCloneButtonsTrips(window.document);
updateCounterTrips();