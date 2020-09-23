let tableSingleCampaign = (function () {
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
                // scrollY:        true,
                // scrollX:        true
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

export default tableSingleCampaign;