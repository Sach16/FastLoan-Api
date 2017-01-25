class Forms {
    /**
     * Auto submit form on changes
     */
    autoSubmit() {
        $('[data-autoSubmit]').on('change', function() {
            $(this).closest('form').submit()
        })
    }
}

export default Forms