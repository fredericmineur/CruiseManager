$('#add-trip').click(function(){
    //Getting the index for the div
    // const index = $('#cruise_trips div.form-group').length;
    const index = +$('#widgets-counter-trips').val(); //unary '+' to convert string to number
    console.log('add' + index);
    //Getting the prototype (code used to generate the html template)...and adding the index...e.g. position outside the present length
    const tmpl = $('#cruise_trips').data('prototype').replace(/__name__/g, index);
    //Injection of the new code
    $('#cruise_trips').append(tmpl);

    //Add 1 to the index (for counter)
    $('#widgets-counter-trips').val(index+1);

    handleDeleteButtonsTrips();
    handleCloneButtonsTrips();
    updateCounterTrips();


})

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
        console.log('clone' + index)

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

handleCloneButtonsTrips();
updateCounterTrips();