class Teams {

    nonmembers() {
        $.get('/admin/v1/api/nonmembers?selected='+$('#selected-team-owner').val(), function (data) {
            $('[data-team-owner]').html(data)
            $('[data-team-owner]').chosen("destroy").chosen()
        })
        
        
        var _this = this;
        $('#inputOwner').on('change', function(event, params) {
            _this.members(event.target.value);
        })
        
        if($('#selected-team-owner').val()) {
            _this.members($('#selected-team-owner').val());
        }
    }
    
    
    members(owner,teamid) {
        $.get('/admin/v1/api/nonmembers?owner='+owner+'&team='+$('#selected-team').val(), function (data) {
                $('[data-nonmembers]').html(data)
                $('[data-nonmembers]').removeAttr('disabled');
                $('[data-nonmembers]').chosen("destroy").chosen()
            })
            
    }
}

export default Teams