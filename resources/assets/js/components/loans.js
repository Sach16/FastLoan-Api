class Loans {

    /**
     * Auto complete the loans drop downs
     */
    autocomplete() {
        this.loans()
        this.loanStatuses()
        this.bindEvents()
    }


    loans() {
        $.get('/admin/v1/api/loans', function(data) {
            $('#inputLoan').html(data)
            $('#inputLoan').chosen()
            $('#inputLoan').val(localStorage.getItem(window.location.pathname+'loan_id'));
            $('#inputLoan').trigger('chosen:updated');
        })

        $('[data-chosen]').chosen()
    }



    loanStatuses() {

        $.get('/admin/v1/api/loan-statuses?selected='+$('#selected-loan-status').val(), function(data) {
            $('[data-loan-statuses]').html(data)
            $('[data-loan-statuses]').chosen()
            $('#inputLoanStatusId ').on('change',this.bindEvents);
        })

    }

    bindEvents(){

        var check_in = ['FIRST_DISB','PART_DISB','FINAL_DISB'];

        $('#inputLoanStatusId').on('change',function(){
            if($('#inputLoanStatusId').find(":selected").data('key') == 'SANCTION') {
                $('#inputApprovedAmount').removeAttr("disabled")
                $('#inputEmiStartDate').removeAttr("disabled")
                $('#inputEmi').removeAttr("disabled")
            }else{
                $('#inputApprovedAmount').attr("disabled","disabled")
                $('#inputEmiStartDate').attr("disabled","disabled")
                $('#inputEmi').attr("disabled","disabled")
            }
            
            if(check_in.indexOf( $('#inputLoanStatusId').find(":selected").data('key') ) >= 0 ) {
                $('#Divdisbursementamount').show();
            }else{
                $('#Divdisbursementamount').hide();
            }

        });
    }

}

export default Loans