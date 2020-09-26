let fillTablePeriods = (function () {

    let initObjectTPeriods = {};

    initObjectTPeriods.init = function () {

        //Add another header below the first
        $('#myDataTable thead tr').clone().appendTo('#myDataTable thead');

        // Transform the second header row to have the search input fields
        $('#myDataTable thead tr:odd th').each( function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });

        var table =
            $('#myDataTable').DataTable({


                "ajax": {
                    // "url": "/api/getperiods",
                    "url":Routing.generate('get_periods'),
                    "dataSrc": ""
                },
                "order": [[0, "desc"]],
                "columns": [
                    {"data": "startdate"},
                    {"data": "enddate"},
                    {"data": "short"},
                    {"data": "colorcode"}
                ],
                //.getScript("//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js");
                "columnDefs": [
                    {
                        "targets": 0,
                        "render": function(data, type, row){
                            if(data !== null & data !=='') {
                                var output = data.substring(0,10)
                                    + '&nbsp;<a href="'
                                    + Routing.generate('edit_period', {'periodId': row.id})
                                    + '"><span style="font-size: 23px; "><i class="fa fa-edit"></i></span></a>'


                                    + '&nbsp;<a href="'
                                    + Routing.generate('remove_period_warning', {'periodId': row.id})
                                    + '"><span style="font-size: 20px; "><i style="color:red" class="fa fa-trash"></i></span></a>'

                                return output;
                            } else {return '';}
                        }
                    },
                    {
                        "targets": 1,
                        "render": function(data){
                            if(data !== null & data !=='') {
                                return data.substring(0,10);
                            } else {return '';}
                        }
                    },
                    {
                        "targets": 3,
                        "render": function (data, type, row){
                            if(data !== null && data !== ''){
                                return data + '&nbsp;&nbsp;<mark style="background-color: '+ data +';"> '
                                    + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp' + '</mark>';
                            }else {return '';}
                        }
                    }

                ],
            });

        $('#myDataTable_filter').hide();

        table.columns().every(function(){
            var that = this;
            $('input', this.header() ).on( 'keyup change clear', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            });
        });


        $('#myDataTable thead input').on('click', function(e){
            e.stopPropagation();
        })


    }

    return initObjectTPeriods;

})();

// export default fillTablePeriods;