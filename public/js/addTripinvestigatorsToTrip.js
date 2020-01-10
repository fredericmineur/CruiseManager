

$('#add-tripinvestigator').click(function(){
    const index = +$('#widgets-counter-tripinvestigator').val();
    // console.log(index);
    const tmpl = $('#trip_tripinvestigators').data('prototype').replace(/__name__/g, index);
    // console.log(tmpl);
    $('#trip_tripinvestigators').append(tmpl);

    // $("[id^=block_trip_tripinvestigators]").removeClass('col-6');
    //
    // $("[id^=block_trip_tripinvestigators]").addClass('col-6');
    $("[id$=surname]").prop('autocomplete', 'off');

    $('#widgets-counter-tripinvestigator').val(index+1);

    handleDeleteButtons();

    // easyAutocompleteTrial()

})

function updateCounter(){
    // const count = $('#trip_tripinvestigators div.form-group').length;
    const count = $('input[id$=firstname]').length;

    $('#widgets-counter-tripinvestigator').val(count);
}

function handleDeleteButtons(){
    $('button#remove-tripinvestigator[data-action="delete"]').click(function(){
        const target=this.dataset.target;
        console.log(target);
        $(target).parent('fieldset').remove();
    })
}
//
updateCounter();
// easyAutocompleteTrial();
handleDeleteButtons();