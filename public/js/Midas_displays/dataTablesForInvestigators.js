let fillTableInvestigators = (function () {
    let initObjectTableInvestigators = {}

    initObjectTableInvestigators.init = function () {
        var table =
            $('#myDataTable').DataTable({
                dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "ajax": {
                    "url": "api/getinvestigators",
                    "dataSrc" : ""
                },

                "order": [[0, "desc"]],
                "columns" : [
                    {"data" : "fullname_comma"},
                    {"data" : "Passengertype"},

                    {"data" : "investigatorid"},
                    {"data" : "imisnr"},

                    {"data" : "inTripInvestigators"},
                    {"data" : "PI"}

                ],
                "columnDefs": [


                    {"targets": 3, "visible": false},
                    {"targets": 4, "visible": false},
                    {"targets": 5, "visible": false},

                    {
                        "targets" : 0,
                        "render": function(data, type, row) {
                            var investigatorOutput = data
                                + '&nbsp <a href="'
                                + Routing.generate('investigator_details', {investigatorId: row.investigatorid}) + '"><i class="fa fa-info-circle"></i></a>'
                                + '&nbsp <a href="' + Routing.generate('edit_investigator', {investigatorId: row.investigatorid})
                                + '"><i class="fa fa-edit"></i></a>';
                            if(row.inTripInvestigators ==null && row.PI ==null) {
                                investigatorOutput += ('&nbsp <a href="' + Routing.generate('remove_investigator_warning', {investigatorId: row.investigatorid})
                                    + '"><i style="color:red" class="fa fa-trash"></i></a>');
                            }
                            if(row.imisnr !==null && row.imisnr !== ''){
                                investigatorOutput += ('&nbsp&nbsp<a href="http://www.vliz.be/en/imis?module=person&persid='
                                    + row.imisnr  + '" target= "blank" title="OpenImisLink"><i class = "fa fa-link"></i></a>');
                            }

                            ;
                            return investigatorOutput;
                        }
                    },
                    {
                        "targets": 2,
                        "render": function(data, type, row) {
                            var activity ='';
                            if(row.PI !== null) {
                                activity += '(PI) ';
                            }
                            if(row.inTripInvestigators !== null) {
                                activity += '(Trip)';
                            }
                            return activity;
                        }
                    },


                ]
            });

    };

    return initObjectTableInvestigators;



})();

// export default fillTableInvestigators;