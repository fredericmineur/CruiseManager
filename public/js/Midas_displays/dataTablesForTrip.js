let fillTableAllTrips = (function () {
    let initFillTableAlltrips = {};

    initFillTableAlltrips.init = function () {
        // console.log(Routing.generate('api_get_trips'));
        // //Add another header below the first
        $('#TableAllTrips thead tr').clone().appendTo('#TableAllTrips thead');
        //Transform the second header row to have the search input fields
        $('#TableAllTrips thead tr:odd th').each( function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });
        //Create the table
        var table =
            $('#TableAllTrips').DataTable({
                "ajax": {
                    // "url": "/api/gettrips",
                    "url": Routing.generate('api_get_trips'),
                    "dataSrc" : ""
                },
                "order": [[0, "desc"]],
                "columns" : [
                    {"data" : "startdateAndNumberOfDays"},
                    {"data" : "plancode"},
                    {"data" : "status"},
                    {"data" : "destination"},
                    {"data" : "ship"},
                ],
                "columnDefs": [

                    {
                        "targets" : 1,
                        "render": function(data, type, row) {
                            if(data !== null && data !== ''){
                                ///cruises/{cruiseId}", options={"expose"=true}, name="cruise_details"
                                var plancodeOutput = '<a href="' + Routing.generate('cruise_details', {cruiseId : row.cruiseID})
                                    + '">' + data + '</a>';
                                return  plancodeOutput;
                            } else  {return 'n/a';}
                        }
                    },
                    {
                        "targets" : 0,
                        "render" : function (data, type, row) {
                            var tripOutput = '';
                            if(data !== null && data !== '') {
                                tripOutput += data;
                            } else {tripOutput += 'n/a';}
                            tripOutput += (' <a href="' + Routing.generate('trip_details', {tripId: row.TripID})
                                + '"  target="_blank"><i class="fa fa-info-circle"></i></a>')
                            if (!row.cruiseID) {
                                tripOutput += (' <a href="' + Routing.generate('trip_remove_warning', {tripId : row.TripID}) + '"><i style="color:red"class="fa fa-trash"> </i></a>');
                            }

                            return  tripOutput;
                        }
                    },
                ]
            });
        // //Hide the generic search field
        $('#TableAllTrips_filter').hide();
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

        $('#TableAllTrips thead input').on('click', function(e){
            e.stopPropagation();
        });
    }
    return initFillTableAlltrips;
})();

//////////////////////////////////////////////////////////////////////////////////////////////////////////

let fillTableTripInvestigators = (function () {
    let initFillTableInvestigators = {};

    initFillTableInvestigators.init = function () {

        $('#TableTripsInvestigators thead tr').clone().appendTo('#TableTripsInvestigators thead');
        $('#TableTripsInvestigators thead tr:odd th').each( function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });
        var table =
            $('#TableTripsInvestigators').DataTable({
                "ajax": {
                    "url": Routing.generate('api_get_trips_diff_investigators'),
                    "dataSrc" : ""
                },
                "order": [[0, "desc"]],
                "columns" : [
                    {"data" : "StartDate"},
                    {"data" : "plancode"},
                    {"data" : "NbTripInvestigators"},
                    {"data" : "NonRegist"},
                    {"data" : "TripID"}
                ],
                "columnDefs": [
                    {
                        "targets": 1,
                        "render": function(data, type, row) {
                            if(data !== null && data !== ''){
                                var plancodeOutput = '<a href="' + Routing.generate('cruise_details', {cruiseId : row.CruiseId})
                                    + '">' + data + '</a>';
                                return  plancodeOutput;
                            } else {return '';}
                        }
                    },
                    {
                        "targets" : 4,
                        "render": function(data, type, row) {
                            if(data !== null && data !== ''){
                                var tripidoutput = '<a href="' + Routing.generate('trip_edit', {tripId: data})
                                    + '"  target="_blank"><i class="fa fa-edit fa-lg"></i></a>';
                                return tripidoutput;

                            } else {return '';}

                        }
                    }
                ]
            });
        $('#TableTripsInvestigators_filter').hide();
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
        $('#TableTripsInvestigators thead input').on('click', function(e){
            e.stopPropagation();
        });

    }
    return initFillTableInvestigators;
})();

///////////////////////////////////////////////////////////////////////////////

let fillTableTripStations = (function () {
    let initFillTableStations = {};

    initFillTableStations.init = function () {

        $('#TableTripsStations thead tr').clone().appendTo('#TableTripsStations thead');
        $('#TableTripsStations thead tr:odd th').each( function () {
            $(this).html('<input type="text" placeholder="Search" />');
        });
        var table =
            $('#TableTripsStations').DataTable({
                "ajax": {
                    "url": Routing.generate('api_get_trips_diff_stations'),
                    "dataSrc" : ""
                },
                "order": [[0, "desc"]],
                "columns" : [
                    {"data" : "StartDate"},
                    {"data" : "plancode"},
                    {"data" : "NbTripStations"},
                    {"data" : "NonRegist"},
                    {"data" : "TripID"}

                ],
                "columnDefs": [
                    {
                        "targets": 1,
                        "render": function(data, type, row) {
                            if(data !== null && data !== ''){
                                var plancodeOutput = '<a href="' + Routing.generate('cruise_details', {cruiseId : row.CruiseId})
                                    + '" >' + data + '</a>';
                                return  plancodeOutput;
                            } else {return '';}
                        }
                    },
                    {
                        "targets" : 4,
                        "render": function(data, type, row) {
                            if(data !== null && data !== ''){
                                var tripidoutput = '<a href="' + Routing.generate('trip_edit', {tripId: data})
                                    + '"  target="_blank" ><i class="fa fa-edit fa-lg"></i></a>';
                                return tripidoutput;

                            } else {return '';}

                        }
                    }
                ]
            });
        $('#TableTripsStations_filter').hide();
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
        $('#TableTripsStations thead input').on('click', function(e){
            e.stopPropagation();
        });

    }
    return initFillTableStations;
})();

// export default fillTableAllTrips;
// export fillTableStations;

// export  {fillTableAllTrips, fillTableTripInvestigators, fillTableTripStations};

