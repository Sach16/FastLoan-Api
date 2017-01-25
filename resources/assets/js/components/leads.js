class Leads {



    constructor() {

    }


    /**
     * Auto complete assigned to drop down
     */
    assignedTo() {
        $.get('/admin/v1/api/members?selected='+$('#selected-member').val(), function (data) {
            $('[data-assigned-to],[data-members]').html(data)
            $('[data-assigned-to],[data-members]').chosen("destroy").chosen()
        })
    }



    sources() {
        $.get('/admin/v1/api/sources?selected='+$('#selected-source').val(), function (data) {
            $('[data-sources]').html(data)
            $('[data-sources]').chosen()
        })
    }



    types() {
        $.get('/admin/v1/api/loantypes?selected='+$('#selected-loan-types').val(), function (data) {
            $('[data-loan-types]').html(data)
            $('[data-loan-types]').chosen("destroy").chosen()
        })
    }



    referrals() {

        $.get('/admin/v1/api/referrals?selected='+$('#selected-referrals').val(), function (data) {
            $('[data-referrals]').html(data)
            $('[data-referrals]').chosen("destroy").chosen()
        })
    }

    PayoutReferrals() {

        $.get('/admin/v1/api/payout-referral', function (data) {
            $('[data-payout-referrals]').html(data)
            $('[data-payout-referrals]').chosen("destroy").chosen()
        })
    }

    bindEvents() {

       //Enable project and builder while editing

        $('#inputLoanType').on('change',function(event){
            if($(this).find(":selected").data('key') == 'HL') {
                 $('#divPropertyVerified').show();
                $('#inputPropertyVerified').removeAttr("disabled")                
                $('#inputPropertyVerified').chosen("destroy").chosen()

            } else {
                $('#divPropertyVerified').hide();
                $('#divBuilder').hide();
                $('#divProjects').hide();
                $('#inputBuilder').val("-1").attr("disabled","disabled")
                $('#inputBuilder').chosen("destroy").chosen()

                $('#inputProject').val("-1").attr("disabled","disabled")
                $('#inputProject').chosen("destroy").chosen()
            }
        })

        //Property Verified for loan
        $('#inputPropertyVerified').on('change',function(event){
            if($(this).find(":selected").val() == '1') {
                 $('#inputBuilder').removeAttr("disabled")
                 $('#divBuilder').show();
                 $('#divProjects').show();
                 $('#inputBuilder').chosen("destroy").chosen()
                 $('#inputProject').chosen("destroy").chosen()

            } else {
                $('#divBuilder').hide();
                $('#divProjects').hide();
                $('#inputBuilder').val("-1").attr("disabled","disabled")
                $('#inputBuilder').chosen("destroy").chosen()

                $('#inputProject').val("-1").attr("disabled","disabled")
                $('#inputProject').chosen("destroy").chosen()
            }
        })

        $('#inputBuilder').on('change',function(){
            if($('#inputLoanType').find(":selected").data('key') == 'HL') {
                $('#divProjects').show();
                $('#inputProject').removeAttr("disabled")
                $('#inputProject').chosen("destroy").chosen()
            }
        })

        $('#inputSourceId').on('change',function(){
            if($(this).find(":selected").data('key') == 'REFERRAL') {
                $('[data-referrals]').removeAttr("disabled")
            } else {
                $('[data-referrals]').val("-1");
                $('[data-referrals]').attr("disabled","disabled")
            }
            $('#inputReferral').chosen("destroy").chosen()
        });


       $('[data-col-search]').on('keypress',function(e){
           if(e.which == 13){
               $('#table-search-form').submit();
           }
       });
    }


   autocomplete() {
        this.assignedTo()
        this.sources()
        this.types()
        this.referrals()
        this.bindEvents()
        this.PayoutReferrals()
   }

}

export default Leads