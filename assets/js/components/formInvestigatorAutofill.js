let investigatorAutofill = (function () {
    let initObjectInvAutofill = {}

    initObjectInvAutofill.init = function (imisJson, mode) {

        $('#investigators_fullName').select2({
                placeholder: 'Select a IMIS person',
                allowClear: true,
                minimumInputLength:3,
                ajax: {
                    url: function (params) {
                        return Routing.generate("searchimispersons") + '/' + params.term;
                    },
                    processData: false,
                    dataType: 'json',
                }
            }
        );

        if (mode == 'edit' && imisJson) {
            var data = {
                id: 1,
                text:  imisJson["personrec"]["PersName"]
            };

            var newOption = new Option(data.text, data.id, false, true);
            $('#investigators_fullName').append(newOption);
        }

        $('#investigators_fullName').on('select2:select', function (e) {
            $('#investigators_imisnr').val(e.params.data.PersID);
            $('#investigators_surname').val(e.params.data.Surname);
            $('#investigators_firstname').val(e.params.data.Firstname);
        });

        $('#investigators_fullName').on('select2:clear', function (e) {
            $('#investigators_imisnr').val(null);
        });
    }

    return initObjectInvAutofill;



})();

export default investigatorAutofill;