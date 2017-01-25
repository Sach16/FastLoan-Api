class Navigator {

    /**
     * Browse back one
     */
    back() {
        $('[data-back]').click(function(e) {
            e.preventDefault()
            window.history.back()
        })
    }
}

export default Navigator