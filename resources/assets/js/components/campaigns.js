class Campaigns {

    /**
     * Auto complete the team drop downs
     */
    autocomplete() {
        $.get('/admin/v1/api/teams?selected='+$('#selected-team').val(), function(data) {
            $('#inputTeam').html(data)
            $('#inputTeam').chosen()
        })

        $('#inputTeam').on('change', function(event, params) {
            $.get('/admin/v1/api/teams/' + event.target.value, function(data) {
                $('#inputMembers').html(data)
                $('#inputMembers').chosen()
                $('#inputMembers').trigger('chosen:updated')
            })
        })

        $.get('/admin/v1/api/teams/' + $('#selected-team').val() +'?multiSelected='+$('#selected-teamMembers').val(), function(data) {
            $('#inputMembers').html(data)
            $('#inputMembers').chosen()
            $('#inputMembers').trigger('chosen:updated')
        })

// campaigns edit for date
        $("#editInputFrom").datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            minDate:new Date($('#selected-editInputFrom').val()),            
        });

// campaigns edit for date
        $("#editInputTo").datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
        });

// calendar edit for date
        $("#editCalendarDate").datetimepicker({
            format: 'DD-MM-YYYY',
        });

//task edit date 
        $("#editTaskFrom").datetimepicker({
            format: 'DD-MM-YYYY',
        });


// for date time
        $("#inputFromDateTimeCreate").datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
        });

        $("#inputToDateTimeCreate").datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            useCurrent: false,            
        });

        $("#CampaignEditInputFrom").datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
        });

        $("#CampaignEditInputTo").datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            useCurrent: false,            
        });

        if(typeof $('#selected-createInputDateTimeTo').val() !== 'undefined'){
                $('#inputToDateTimeCreate').data("DateTimePicker").minDate(new Date($('#selected-createInputDateTimeTo').val()));
        }

        if(typeof $('#selected-CampaignEditInputTo').val() !== 'undefined'){
                $('#CampaignEditInputTo').data("DateTimePicker").minDate(new Date($('#selected-CampaignEditInputTo').val()));
        }

        if(typeof $('#selected-editInputFrom').val() !== 'undefined'){
                $('#editInputTo').data("DateTimePicker").minDate(new Date($('#selected-editInputFrom').val()));
        }

        $("#inputFromDateTimeCreate").on("dp.change", function (e) {
            $('#inputToDateTimeCreate').data("DateTimePicker").minDate(e.date);
        });
        $("#inputToDateTimeCreate").on("dp.change", function (e) {
            $('#inputFromDateTimeCreate').data("DateTimePicker").maxDate(e.date);
        });

        $("#editInputFrom").on("dp.change", function (e) {
            $('#editInputTo').data("DateTimePicker").minDate(e.date);
        });
        $("#editInputTo").on("dp.change", function (e) {
            $('#editInputFrom').data("DateTimePicker").maxDate(e.date);
        });

        $("#CampaignEditInputFrom").on("dp.change", function (e) {
            $('#CampaignEditInputTo').data("DateTimePicker").minDate(e.date);
        });
        $("#CampaignEditInputTo").on("dp.change", function (e) {
            $('#CampaignEditInputFrom').data("DateTimePicker").maxDate(e.date);
        });

        $('[data-chosen]').chosen()
    }
}

export default Campaigns