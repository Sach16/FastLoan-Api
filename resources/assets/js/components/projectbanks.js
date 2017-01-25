/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


class ProjectBanks {


    constructor() {
        this.bindEvents()
        this.autocomplete()
    }


    autocomplete() {
        $.get('/admin/v1/api/members-project-approval?project_id='+$('#inputProjectId').val(), function (data) {
            $('[data-members-project-approval]').html(data)
            $('[data-members-project-approval]').chosen("destroy").chosen()
        })
    }

    memberBanks(e) {
        $.get('/admin/v1/api/members-bank?user_id='+$('#agentId').val(), function (data) {
            if(Object.keys(data).length === 0){
              $('#inputMemberBankDisplay').val('No Bank assigned for the selected member.');
            }else{
              $('#inputMemberBankDisplay').val(data.name);
              $('#inputMemberBank').val(data.id)
            }
        })
    }

    bankMembers(){
        $.get('/admin/v1/api/bank-members?bank_id='+$('#bankId').val(), function (data) {
            $('[data-bank-members]').html(data)
            $('[data-bank-members]').chosen("destroy").chosen()
        })
    }

    bindEvents() {
        $('#agentId ').on('change',this.memberBanks);
        $('#bankId ').on('change',this.bankMembers);
    }

}


export default ProjectBanks
