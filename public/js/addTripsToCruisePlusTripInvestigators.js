function cloneTrip(contextElement){
    $('button.clone-trip[data-action="clone"]', contextElement).click(function(){

        const index = counter.countTrip;


        //gets the target of the click on button event
        const target = this.dataset.target;
        //Select every input inside the target...loop through them ....copy the value of the input, and set it as value into the innerHTML  (attr() is the HTML attribute)
        $("input", target).each(function (idx, inputElement) {
             var ie = $(inputElement);
             ie.attr("value", ie.val());
        })
        const clonedTrip = $(target).parent('fieldset').clone();

        //  NB Somehow unable to modify properties of the object clonedTrip (declared as "let clonedTrip").
        //  Therefore, just extracting the innerHTML code, modify it (also removing the <a> tag linking to trip_edit (as the trip as not been submitted yet),
        //  and appending it to the form

        //update the attributes id (e.g. cruise_trips_0_tripinvestigators_0_surname to cruise_trips_3_tripinvestigators_0_surname)
        let clonedTripHTML = clonedTrip['0'].innerHTML.replace(/_trips_\d+/g, '_trips_' + index);


        //update the attributes name (e.g. name="cruise[trips][0][tripinvestigators][0][surname]" to name="cruise[trips][3][tripinvestigators][0][surname]"
        clonedTripHTML = clonedTripHTML.replace(/\[trips\]\[\d+\]/g, '[trips][' + index + ']');

        clonedTripHTML = "<fieldset class='form-group'>" + clonedTripHTML + "</fieldset>";

        //REMOVE THE CLONE BUTTON (for simplification no event handler for that functionality)...
        // clonedTripHTML = clonedTripHTML.replace(/<button type="button" class="btn btn-primary clone-trip" data-action="clone" data-id="cruise_trips_\d+" data-target="#block_cruise_trips_\d+"><i class="fa fa-clone fa-2x"><\/i><\/button>/,'');

        // clonedTripHTML = clonedTripHTML.replace(/<button type="button" class="btn btn-primary clone-trip" data-action="clone" data-id="cruise_trips_\d+" data-target="#block_cruise_trips_\d+">[\s\n]*<i class="fa fa-clone fa-2x"><\/i>[\s\n]*<\/button>/,'');


        const clonedTripElement = $(clonedTripHTML);

        $('div#cruise_trips').append(clonedTripElement);

        //Initialize the new tripinvestigators counter for the cloned trip
        // with the number of tripinvestigators in the cloned trip
        const countTripInvestigator = $('[id^=block_cruise_trips_'+index+'_tripinvestigators]').length;

        window.counter['block_cruise_trips_' + index]=countTripInvestigator;

        //Updating the number of trips
        window.counter.countTrip = index +1;

        /// !!!!! context ELEMENT?????
        addTripInvestigatorsHandler(clonedTripElement);
        deleteTripinvestigators(clonedTripElement);
        cloneTrip(clonedTripElement);
        deleteTrip(clonedTripElement);
        $('.js-datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });
    })
}

function deleteTrip(contextElement){
    // 'button#remove-trip[data-action="delete"]'  'button[data-id="'+idBlock+'"][data-action="delete"]'
    $('button.remove-trip[data-action="delete"]', contextElement).click(function(){

        // as we have created in Twig a 'data-target' attribute with the id selector of the block
        const target=this.dataset.target; //eg. '#block_cruise_trips_2'
        const blockTrip = target.replace('#', '');
        //delete the key/pair of the corresponding trip in the counter
        delete window.counter[blockTrip];

        //remove the whole trip section in the DOM
        $(target).parent('fieldset').remove();


    })
}

function updateCounterTrips(){
    //counting the number of trip sections and allocate it to the counter
    const count = $('.block-trip').length;
    window.counter.countTrip = count;
    // console.log(counter);

    //Initializing or updating the number of tripinvestigators for each trip (looping through 'block-trip'S
    $('.block-trip').each(function(){
        // console.log (this);
        let idBlockTrip = this['id'];
        // idBlockTrip = idBlockTrip.replace('block_', '');
        const countTripInvestigatorForObj = $('.block-tripinvestigator[id^='+idBlockTrip+']').length;
        window.counter[idBlockTrip]=countTripInvestigatorForObj;
    })
    // console.log(counter);
}

function  addTripInvestigatorsHandler(contextElement){
    //Put the event as argument, as it allow us to get info on the target button
    $( '.add-tripinvestigator',contextElement).click(function(e){


        //console.log(contextElement);
        //e.target.parentElement.parentElement is actually the block of html code on which is located the clicked button
        // (all cases, whatever contextElement put as parameter, corresponding to a trip).
        // e.g. <div id="block_cruise_trips_0" class="form-group">....</div>


        const idBlockTrip = e.target.parentElement.parentElement.attributes['id']['nodeValue']; // e .g block_cruise_trips_0
        const idTrip = (idBlockTrip).replace('block_cruise_trips_', ''); //e.g. 0
        const indexTInvestigators = window.counter[idBlockTrip];
        // console.log('index');console.log(idBlockTrip);console.log(window.counter);console.log(indexInvestigators);console.log(idTrip);

        //Replace the generic with adequates labels for the tripinvestigator in the prototype
        //Also, the tripinvestigator prototype that is produced with new trips has already some (wrong) tripinvestigator index by default (instead of '__name__')
        const tmpl = $('div[id=cruise_trips_' + idTrip + '_tripinvestigators]').data('prototype').replace(/_tripinvestigators_(__name__|\d+)/g, '_tripinvestigators_'+ indexTInvestigators);
        const tmpl2 = tmpl.replace(/\[tripinvestigators\]\[(__name__|\d+)\]/g, '[tripinvestigators]['+indexTInvestigators+']');

        const elementTripinvestigator = $(tmpl2);
        //console.log('elementTripinvestigator');
        //console.log(tmpl);

        //append the modified prototype to the section (ad hoc trip section)
        $('div[id=cruise_trips_' + idTrip + '_tripinvestigators]').append(elementTripinvestigator);

        //update the counter of investigators for the trip
        window.counter[idBlockTrip]=indexTInvestigators + 1;

        deleteTripinvestigators(elementTripinvestigator);
        addAutocompleteForInvestigator(idTrip, indexTInvestigators);
    })
}

function deleteTripinvestigators(contextElement){
    $('button#remove-trip-investigator[data-action="delete"]', contextElement).click(function(){
        const target = this.dataset.target;
        $(target).parent('fieldset').remove();
    })
}




function addAutocompleteForInvestigator(indexTrip, indexTInvestigators){

    var tripinvestigatorFullNameID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_fullname';
    var tripinvestigatorSurnameID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_surname';
    var tripinvestigatorFirstnameID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_firstname';
    var tripinvestigatornrID='cruise_trips_'+indexTrip+'_tripinvestigators_'+ indexTInvestigators+'_investigatornr';

    var options = {
        url: function(phrase) {
            return "/investigators/getInvestigatorNames/"+phrase
        },

        getValue: function(element) {
            return element.fullname;
        },

        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: {
                dataType: "json"
            }
        },

        preparePostData: function(data) {
            data.phrase = $("#"+tripinvestigatorFullNameID).val();
            return data;
        },

        list: {
            onChooseEvent: function() {
                $("#" +tripinvestigatornrID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().investigatorid);
                $("#" +tripinvestigatorSurnameID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().surname);
                $("#" +tripinvestigatorFirstnameID).val($("#"+tripinvestigatorFullNameID).getSelectedItemData().firstname);
            }
        },

        requestDelay: 400
    };
    $("#"+tripinvestigatorFullNameID).easyAutocomplete(options);
}


