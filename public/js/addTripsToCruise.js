$('#add-trip').click(function(){
    //Getting the index for the div
    // const index = $('#cruise_trips div.form-group').length;
    const index = +$('#widgets-counter-trips').val(); //unary '+' to convert string to number
    console.log(index);
    //Getting the prototype (code used to generate the html template)...and adding the index...e.g. position outside the present length
    const tmpl = $('#cruise_trips').data('prototype').replace(/__name__/g, index);
    //Injection of the new code
    $('#cruise_trips').append(tmpl);

    //Add 1 to the index (for counter)
    $('#widgets-counter-trips').val(index+1);

    handleDeleteButtonsTrips();
})

function handleDeleteButtonsTrips(){
    $('button#remove-trip[data-action="delete"]').click(function(){
        const target=this.dataset.target;
        //'this' is the button and 'dataset' are the attributes, target "#block_cruise_trips_0
        // remove the whole div
        $(target).remove();
    })
}

function updateCounterTrips(){
    const count = $('#cruise_trips div.form-group').length;
    $('#widgets-counter-trips').val(count);


}

handleDeleteButtonsTrips();
updateCounterTrips();