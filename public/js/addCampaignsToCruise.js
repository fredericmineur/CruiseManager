
function handleDeleteButtonsCampaigns(){
    $('button#remove-campaign[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        // console.log(target);
        // console.log(this.dataset);
        $(target).remove();
    })
}

function updateCounterCampaigns(){
    const count = $('#cruise_campaign div.form-group').length;
    // $('#widgets-counter-campaigns').val(count);
    window.counterCampaigns.count = count;

}



