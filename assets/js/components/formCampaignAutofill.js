let campaignAutofill = (function () {
    let initObjectCampaignAutofill = {}

    initObjectCampaignAutofill.init = function (imisJson, mode) {

        $('#campaign_imistitle').select2({
                placeholder: 'Select a IMIS project',
                allowClear: true,
                minimumInputLength:3,
                ajax: {
                    url: function (params) {
                        return Routing.generate("searchImisProjects") + '/' + params.term;
                    },
                    processData: false,
                    dataType: 'json',
                }
            }
        );

        if  (mode == 'edit' && imisJson) {
            var data = {
                id: 1,
                text:  imisJson["projectrec"]["StandardTitle"]
            };

            var newOption = new Option(data.text, data.id, false, true);
            $('#campaign_imistitle').append(newOption);
        }



        $('#campaign_imistitle').on('select2:select', function (e) {
            $('#campaign_imisprojectnr').val(e.params.data.ProID);
            $('#myModal').modal('show');
            $('#label_acronym').html('<b>The acronym: </b>' +(e.params.data.Acronym || '') );
            $('#acronym').val((e.params.data.Acronym || ''));
            $('#label_shortTitle').html('<b>The first 50 characters of the tile: </b>'+ e.params.data.text.substring(0, 50) );
            $('#shortTitle').val(e.params.data.text.substring(0, 50));
        });

        $('#campaign_imistitle').on('select2:clear', function (e) {
            $('#campaign_imisprojectnr').val(null);
        });

        $("#copyValues").click(function(){
            $('#campaign_campaign').val($('input:radio[name=copyImisProj]:checked').val());
            $('#myModal').modal('hide');
        });

    }

    return initObjectCampaignAutofill;

})();

export default campaignAutofill;