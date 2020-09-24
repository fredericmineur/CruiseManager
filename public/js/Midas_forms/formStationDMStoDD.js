let stationDMStoDD = (function () {
    let initObjectDMStoDD = {}

    initObjectDMStoDD.init = function () {

        function fromDMStoDecimal (degree, minute, second){
            degree = parseFloat(degree); degree = (isNaN(degree)) ? 0 : degree;
            minute = parseFloat(minute); minute = (isNaN(minute)) ? 0 : minute;
            second = parseFloat(second); second = (isNaN(second)) ? 0 : second;
            var conversionToDecimal = Math.abs(degree) + minute/60 + second/3600;
            conversionToDecimal = (degree < 0) ? -conversionToDecimal : conversionToDecimal;
            return conversionToDecimal;
        };

        $('button[name$="convert"][value$="latitude"]').on("click", function(){
            var latDeg = $('input#latDeg').val();
            var latMin = $('input#latMin').val();
            var latSec = $('input#latSec').val();
            $('input#station_latitude').val(fromDMStoDecimal(latDeg, latMin, latSec));
        });
        $('button[name$="convert"][value$="longitude"]').on("click", function(){
            var longDeg = $('input#longDeg').val();
            var longMin = $('input#longMin').val();
            var longSec = $('input#longSec').val();
            $('input#station_longitude').val(fromDMStoDecimal(longDeg, longMin, longSec));
        });


    }

    return initObjectDMStoDD;

})();

// export default stationDMStoDD;