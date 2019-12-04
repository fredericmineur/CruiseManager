$('#add-campaign').click(function(){
    // const index = $('#cruise_campaign div.form-group').length;
    const index = +$('#widgets-counter-campaigns').val();
    const tmpl = $('#cruise_campaign').data('prototype').replace(/__name__/g, index);
    $('#cruise_campaign').append(tmpl);
    console.log(index);

    $('#widgets-counter-campaigns').val(index+1);
    handleDeleteButtonsCampaigns();
})

function handleDeleteButtonsCampaigns(){
    $('button#remove-campaign[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        console.log(target);
        console.log(this.dataset);
        $(target).remove();
    })
}

function updateCounterCampaigns(){
    const count = $('#cruise_campaign div.form-group').length;
    $('#widgets-counter-campaigns').val(count);

}

handleDeleteButtonsCampaigns();
updateCounterCampaigns();