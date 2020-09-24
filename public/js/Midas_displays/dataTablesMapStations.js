let fillTableMapStations = (function () {
    let initObjectTableMapStation = {};

    initObjectTableMapStation.init = function () {
        var hovered_id, layer, stations;

        var tableStations = $('#myDataTable').DataTable({
            fixedHeader: {
                header: true,
                // footer: true
            },

            "ajax": {
                "url": "/api/getStationsGeoJSON",
                dataSrc:'features'

            },
            "columns": [
                {"data" : "properties.code"},
                {"data" : "properties.latitude_rounded"},
                {"data" : "properties.longitude_rounded"},
                {"data" : "properties.stationId"},
                {"data" : "properties.idInTripStation"}
            ],
            "columnDefs" :[
                {
                    "targets" : 0,
                    "render" : function (data, type, row) {
                        var stationOutput = '<a href="' + Routing.generate ('display_station', {stationId: row.properties.stationId} ) + '" data-value="'+ data +'">' + data + '</a>';
                        stationOutput += ('&nbsp <a href="' + Routing.generate('edit_station', {stationId: row.properties.stationId})  +
                            '"><i class="fa fa-edit"></i></a>');
                        if(! row.properties.idInTripStation) {
                            stationOutput += ('&nbsp <a href="' + Routing.generate('remove_station_warning', {stationId: row.properties.stationId})
                                + '"><i style="color:red" class="fa fa-trash"></i></a>');
                        }
                        return stationOutput;
                    },
                    "createdCell" : function (cell, cellData, rowData, rowIndex, colIndex) {
                        $(cell).on('mouseenter', function(e){

                            hovered_id = $(e.target.firstChild).data('value');
                            // console.log(hovered_id);
                            layer = stations.getLayer(hovered_id);
                            // console.log(layer);
                            layer.setStyle({
                                color: 'red',
                                radius: 7,
                                fillColor: 'red',
                                fillOpacity: 1
                            });
                        }).on('mouseout', function(e) {
                            // stations.resetStyle(layer);
                            layer.setStyle({
                                radius: 1,
                                color: '#FFA500',
                                fillColor: '#FFA500',
                                fillOpacity: 0.2
                            });
                        });
                    },
                    "width" : "70px"
                },
                {"targets" : 3, "visible" : false},
                {"targets" : 4, "visible" : false},
                {"targets": 1, "width" : "50px"}, {"targets": 2, "width" : "50px"}
            ]
        });
        // new $.fn.dataTable.FixedHeader( tableStations );



        var mapStations = L.map('map-stations').setView([51.4, 3], 8);

        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoiZm1pbmV1ciIsImEiOiJjamwzdDhocW0xN2RnM3ZxeWR5dzVmOHF4In0.6WX6hN8ge0BOad0MRZyEtw'
        }).addTo(mapStations);


        $.ajax({
            dataType: "json",
            // url: "/api/getStationsGeoJSON",
            url: Routing.generate('get_stations_GeoJSON'),
            success: function(data) {
                stations = L.geoJson(data, {
                    pointToLayer: pointToLayerNormalStyle,
                    onEachFeature: onEachFeature
                });

                stations.addTo(mapStations);
            }
        })

        function onEachFeature (feature, layer) {
            let code;
            code = feature.properties.code;
            layer.bindPopup(code);
            layer._leaflet_id = code;

        }

        function pointToLayerNormalStyle(feature, latlng) {
            return new L.CircleMarker(latlng, {
                radius: 0.5,
                color: '#FFA500',
                fillColor: '#FFA500',
                fillOpacity: 0.2
            });
        }

        function pointToLayerHighlightStyle(feature, lat, lng) {

        }

        function highlightFeature(e){
            var layer = e.target;

        }
    }

    return initObjectTableMapStation;



})();

// export default fillTableMapStations;