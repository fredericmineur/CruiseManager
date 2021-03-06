let fillTableCruises = (function () {

    let initObjectTCruise = {};

    initObjectTCruise.init = function () {


            //Add another header below the first
            $('#myDataTable thead tr').clone().appendTo('#myDataTable thead');

            //Transform the second header row to have the search input fields
            $('#myDataTable thead tr:odd th').each( function () {
                $(this).html('<input type="text" placeholder="Search" />');
            });

            //Create the table
            //https://stackoverflow.com/questions/39407881/pagination-at-top-and-bottom-with-datatables
            var table =
                $('#myDataTable').DataTable( {
                    // fixedHeader: {
                    //     header: true,
                    //     footer: true
                    // },
                    "pageLength": 50,
                    dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-5'i><'col-sm-7'p>>",

                    "ajax": {
                        // "url": "api/getcruises",
                        "url": Routing.generate('get_cruises'),
                        "dataSrc" : "data"
                    },
                    "order": [[ 0, "desc" ]],
                    "columns": [
                        { "data": "plancode" },
                        { "data": "campaigns",
                            "name": "campaigns",
                            "defaultContent": ""
                        },
                        { "data": "startdate" },
                        { "data": "enddate" },
                        { "data": "PrincipalInvestigator" },
                        { "data": "numberOfTrips" },
                        { "data": "CruiseID" },
                        { "data" : "PrincipalInvestigatorID"}
                    ],
                    "columnDefs": [
                        {
                            "targets": 1,
                            "render": function (data, type, row) {
                                var campaignOutput = '';
                                var campaignRouteName =  'campaign_details' ;
                                if (Array.isArray(data) && data.length) {

                                    for (var i = 0; i < data.length; i++) {
                                        campaignOutput +=  '<a href="' + Routing.generate(campaignRouteName, {campaignId: data[i]['campaignid']}) + '" title="Open Campaign">'
                                            // + '<span style="font-size: 20px;"><i class="fa fa-info-circle" aria-hidden="true"></i></span>&nbsp;'
                                            + '&#8226&nbsp' + data[i]['campaign']
                                            +  '</a><br/>';
                                    }
                                    return campaignOutput ;
                                }
                            }
                        },
                        {
                            "targets": 0,
                            "render": function (data, type, row) {
                                var cruiseOutput = data + '&nbsp<a href="' + Routing.generate('cruise_details', {cruiseId: row.CruiseID}) + '" title="Info">'
                                    +'<span style="font-size: 20px;"><i class="fa fa-info-circle" ></i></span></a>&nbsp'
                                    +'&nbsp <a href="'
                                    + Routing.generate('cruise_edit', {cruiseId: row.CruiseID})
                                    + '" title="Edit">'
                                    +'<span style="font-size: 23px; "><i class = "fa fa-edit"></i></span></a>';
                                // +'&nbsp <a href="'
                                // + Routing.generate('cruise_edit', {cruiseId: row.CruiseID}) + Routing.generate('cruise_edit', {cruiseId: row.CruiseID});


                                if(row.numberOfTrips == 0) {
                                    cruiseOutput += ('&nbsp;&nbsp;&nbsp;<a href=" ' + Routing.generate('cruise_remove_warning', {cruiseId: row.CruiseID})
                                        + '"><span style="font-size: 20px; "><i style="color:red" class="fa fa-trash"></i></span></a>');
                                }

                                // + '<a href="' + Routing.generate('')
                                return cruiseOutput;
                            }
                        },
                        {
                            "targets": 4,
                            "render": function (data, type, row) {
                                var investigatorOutput;
                                if(data==null){
                                    investigatorOutput ='';
                                } else {
                                    investigatorOutput = '<a href="' + Routing.generate('investigator_details', {investigatorId: row.PrincipalInvestigatorID})
                                        + '">' + data + '</a>';
                                }
                                return investigatorOutput;
                            }
                        },
                        {
                            "targets": -2,
                            "visible": false
                        },
                        {
                            "targets": -1,
                            "visible": false
                        }
                    ],
                } );

            //Hide the generic search field
            $('#myDataTable_filter').hide();


            // Apply the search on every column
            table.columns().every( function () {
                var that = this;

                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );



            //prevent that clicking on the search field sort the column
            //https://stackoverflow.com/questions/31677257/datatables-only-allow-sorting-with-buttons

            $('#myDataTable thead input').on('click', function(e){
                e.stopPropagation();
            });
            // table.fixedHeader.enable(  );


    }

    return initObjectTCruise;


})();

// export default fillTableCruises;













