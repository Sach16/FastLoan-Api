class Banks {

    constructor() {
        this.bindEvents()
    }

    /**
     * Auto complete the bank drop down
     */
    bank() {
        $.get('/admin/v1/api/banks', function (data) {
            $('[data-bank]').append(data)
            $('[data-bank]').chosen()
        })
    }

    /**
     * on city the bank drop down
     */

    bindEvents() {
        window.onload = function() {
            $.get('/admin/v1/api/banks?city_id='+$('#inputCity').val(), function (data) {
                $('#inputBank').html(data)
                $('#inputBank').chosen()
                $('#inputBank').val(localStorage.getItem(window.location.pathname+'bank_id'));
                $('#inputBank').trigger('chosen:updated');
            }); 

            $('#inputBuilderName').val(localStorage.getItem(window.location.pathname+'inputBuilderName'));
            $('#inputBuilderName').trigger('chosen:updated');
            $('#inputOwnerName').val(localStorage.getItem(window.location.pathname+'inputOwnerName'));
            $('#inputOwnerName').trigger('chosen:updated');
        };

        $('#inputOwnerName').on('change', (e) => {
            // Store
            localStorage.setItem(window.location.pathname+'inputOwnerName', $('#inputOwnerName').val());
        });
        
        $('#inputBuilderName').on('change', (e) => {
            // Store
            localStorage.setItem(window.location.pathname+'inputBuilderName', $('#inputBuilderName').val());
        });

        $('#inputBank').on('change', (e) => {
            // Store
            localStorage.setItem(window.location.pathname+'bank_id', $('#inputBank').val());
        });

        $('#inputLoan').on('change', (e) => {
            // Store
            localStorage.setItem(window.location.pathname+'loan_id', $('#inputLoan').val());
        });

        $('#inputCity').on('change', (e) => {
            $.get('/admin/v1/api/banks?city_id='+$('#inputCity').val(), function (data) {
                $('#inputBank').html(data)
                $('#inputBank').chosen()
                $('#inputBank').trigger('chosen:updated');
            });
        });
    }
}

export default Banks