class Dates {
    /**
     * Date picker
     */
    picker() {
        $('[data-date]').datetimepicker({
            format: 'DD-MM-YYYY',
            minDate:new Date().setHours(0,0,0,0),
        })
    }

    /**
     * Month Selector
     */
    months() {
        $('[data-monthSelector]').datetimepicker({
            format: 'MM-YYYY'
        })
    }

    /*
    * Time picker
     */
    time(){
        $('[data-timeSelector]').datetimepicker({
            format: 'hh:mm A'
        })
    }

    /**
     * Date Time picker
     */
    datetime() {
        $('[data-date-time]').datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            minDate:new Date(),
        })
    }

    /**
     * Date picker
     */
    dobDate() {
        $('[data-dob-date]').datetimepicker({
            format: 'DD-MM-YYYY',
            maxDate:new Date().setHours(0,0,0,0),
        })
    }
}

export default Dates