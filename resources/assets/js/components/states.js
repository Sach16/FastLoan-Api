class States {

    states() {
        $.get('/admin/v1/api/states?selected='+$('#selected-state').val(), function (data) {
            $('[data-states]').append(data)
            $('[data-states]').chosen()
        })
    }
    
    
    autocomplete() {
        this.states()
    }
}

export default States