class Addresses {

    /**
     * Auto complete the city drop down
     */
    city() {
        $.get('/admin/v1/api/addresses/cities?selected='+$('#selected-city').val(), function (data) {
            $('[data-city]').append(data)
            $('[data-city]').chosen()
        })
    }
}

export default Addresses