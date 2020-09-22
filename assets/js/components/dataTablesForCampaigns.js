let fillTableCampaigns = (function () {

    let initObjectTableCampaign = {}

    initObjectTableCampaign.init = function () {


        //Add another header below the first
        $('#myDataTable thead tr').clone().appendTo('#myDataTable thead');

        //Transform the second header row to have the search input fields
        $('#myDataTable thead tr:odd th').each( function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });

        //Create the table
        var table =
            $('#myDataTable').DataTable({
                "ajax": {
                    "url": "api/getcampaignsslim",
                    "dataSrc" : ""
                },

                "order": [[1, "desc"]],
                "columns" : [
                    {"data" : "campaign"},
                    {"data" : "imisprojectnr"},

                    {"data" : "cruise",
                        "defaultContent" : ""},
                    {"data" : "campaignid"}
                ],
                "columnDefs": [
                    {
                        "targets": -1,
                        "visible": false
                    },
                    {
                        "targets": 0,
                        "render": function(data, type, row) {
                            var campaignOutput = data + '&nbsp&nbsp<a href="'
                                + Routing.generate('campaign_details', {campaignId: row.campaignid})
                                + '" title="Info campaign"><i  class="fa fa-info-circle "></i></a>'
                                + '&nbsp&nbsp<a href="'
                                + Routing.generate('campaign_edit', {campaignId: row.campaignid})
                                + '" title="Edit campaign"><i  class="fa fa-edit"></i></a>';
                            if(Array.isArray(row.cruise) && row.cruise.length){
                            } else {
                                campaignOutput +=
                                    ( '&nbsp&nbsp<a href="'
                                        + Routing.generate('remove_campaign_warning',{'campaignId': row.campaignid})
                                        + '" title="Remove campaign"><i style="color:red" class="fa fa-trash"></i></a>')
                                ;
                            }


                            return campaignOutput;
                        }
                    },
                    {"targets":2,
                        "render":function(data, type, row) {
                            var cruisesOutput = '';
                            if(Array.isArray(data) && data.length) {
                                for (var i =0; i < data.length; i++) {
                                    cruisesOutput += '<a href="' + Routing.generate('cruise_details', {cruiseId: data[i]['cruiseid']}) + '" title="Open Cruise">'
                                        // + '<i class="fa fa-map-marker" aria-hidden="true"></i>'
                                        + data[i]['plancode'] + '</a>&nbsp'

                                }
                                return cruisesOutput;
                            }

                        }

                    }

                ]
            });



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
    }



    return initObjectTableCampaign;


})();

export default fillTableCampaigns;

