class Projects {

    /**
     * Auto complete the team drop downs
     */
    autocomplete() {

        $("#inputStartDate").on("dp.change", function (e) {
            $('#inputEndDate').data("DateTimePicker").minDate(e.date);
        });
        $("#inputEndDate").on("dp.change", function (e) {
            $('#inputStartDate').data("DateTimePicker").maxDate(e.date);
        });

    }


    /**
     * Auto complete owners drop down
     */
    owners() {
        $.get('/admin/v1/api/members', function (data) {
            $('[data-owners]').html(data)
            $('[data-owners]').chosen()
        })
    }

        /**
     * Auto complete owners drop down
     */
    DSAowners() {
        $.get('/admin/v1/api/dsa-owner/members', function (data) {
            $('[data-dsa-owners]').html(data)
            $('[data-dsa-owners]').chosen()
        })
    }

    /**
     * Auto complete owners drop down
     */
    builders() {
        $.get('/admin/v1/api/builders?selected='+$('#selected-builders').val(), function (data) {
            $('[data-builders]').html(data)
            $('[data-builders]').chosen()
            
            if($('#selected-builders').val()) {
                $('#inputBuilder').trigger("change");
            }
        })
        
        var _this = this;
        $('#inputBuilder').on('change', function(event, params) {
             _this.projects(event.target.value);
             _this.Payoutprojects(event.target.value);
        });
        
    }
    
    
    projects(builder_id) {
        $.get('/admin/v1/api/projects?builder_id=' + builder_id+'&selected='+$('#selected-projects').val(), function(data) {
            $('#inputProject').html(data)
            $('#inputProject').chosen("destroy").chosen() 
        })
    }

        Payoutprojects(builder_id) {
        $.get('/admin/v1/api/payout-projects?builder_id=' + builder_id, function(data) {
            $('[data-payout-projects]').html(data)
            $('[data-payout-projects]').chosen("destroy").chosen() 
        })
    }
}

export default Projects