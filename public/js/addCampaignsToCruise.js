
function handleDeleteButtonsCampaigns(contextElement){
    $('button#remove-campaign[data-action="delete"]', contextElement).click(function(){
        const target = this.dataset.target;
        console.log(target);
        $(target).parent('fieldset').remove();
    })
}

function updateCounterCampaigns(){
    const count = $('#cruise_campaign div.form-group').length;
    // $('#widgets-counter-campaigns').val(count);
    window.counterCampaigns.count = count;
}

//block campaignname e.g. cruise_campaig_11
function addAutocompleteCampaignName (blocKCampaign) {
    $('#' + blocKCampaign).select2();
}

let addCampaigns = (function () {

    let initObjectCA = {};

    initObjectCA.init = function () {

        counterCampaigns = {count : 0};

        $('#add-campaign').click(function(){

            const index = window.counterCampaigns.count;

            const tmpl = $('#cruise_campaign').data('prototype').replace(/__name__/g, index);
            const campaignElement = $(tmpl);
            $('#cruise_campaign').append(campaignElement);

            window.counterCampaigns.count = index+1;
            const blockID = 'cruise_campaign_' + index;
            handleDeleteButtonsCampaigns(campaignElement);
            addAutocompleteCampaignName(blockID);
            // addAutocompleteCampaignImis(blockID);
        });

        handleDeleteButtonsCampaigns(window.document);

        $("#cruise_campaign").find("select").each(function(){
            addAutocompleteCampaignName($(this).attr('id'));
        });

        updateCounterCampaigns();
    };

    return initObjectCA;

})();


