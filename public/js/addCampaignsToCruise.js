
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

//block campaign e.g. cruise_campaign_11
function addAutocompleteCampaignName (blocKCampaign) {
    const campaignNameID = blocKCampaign + '_campaign';
    const campaignImisID = blocKCampaign + '_imisprojectnr';
    const campaignIdID = blocKCampaign + '_campaignid';

    var options = {
        url: function(phrase){
            return "/api/campaignsNames/" + phrase;
        },
        getValue: function (element) {
            return element.name;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },
        preparePostData: function(data) {
            data.phrase = $("#"+ campaignNameID).val();
            return data;
        },
        list: {
            onChooseEvent: function () {
                console.log('selecting');
                var imisForSelected = $('#' + campaignNameID).getSelectedItemData().imis;
                $('#' + campaignImisID).val(imisForSelected);
                var idForSelected = $('#' + campaignNameID).getSelectedItemData().campaignid;
                $('#' + campaignIdID).val(idForSelected);
            }
        },

        requestDelay: 400
    };
    $('#' + campaignNameID).easyAutocomplete(options);
}

function addAutocompleteCampaignImis (blockCampaign) {
    const campaignNameID = blockCampaign + '_campaign';
    const campaignImisID = blockCampaign + '_imisprojectnr';
    const campaignIdID = blockCampaign + '_campaignid';

    var options = {
        url: function(phrase){
            return "/api/campaignsImis/" + phrase;
        },
        getValue: function (element) {
            return element.imis;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },
        preparePostData: function(data) {
            data.phrase = $("#"+ campaignImisID).val();
            return data;
        },
        list: {
            onChooseEvent: function () {
                console.log('selecting');
                var nameForSelected = $('#' + campaignImisID).getSelectedItemData().name;
                $('#' + campaignNameID).val(nameForSelected);
                var idForSelected = $('#' + campaignImisID).getSelectedItemData().campaignid;
                $('#' + campaignIdID).val(idForSelected);
            }
        },

        requestDelay: 400
    };
    $('#' + campaignImisID).easyAutocomplete(options);
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
            addAutocompleteCampaignImis(blockID);
        });


        handleDeleteButtonsCampaigns(window.document);
        $('.block_cruise_campaign').each(function(){
            // console.log($(this).attr('id'));
            const blockID = $(this).attr('id');
            addAutocompleteCampaignName(blockID);
            addAutocompleteCampaignImis(blockID);
        });
        updateCounterCampaigns();


    }

    return initObjectCA;






})();


