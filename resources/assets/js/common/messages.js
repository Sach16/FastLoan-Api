class Messages {

    /**
     * Fade in and out messages
     */
    flash() {
        $('[data-flash]').fadeIn(200).delay(3000).fadeToggle();
        $('[data-error-flash]').fadeIn(200).delay(2000000).fadeToggle();
    }

    /**
     * Confirm if the action should be performed
     */
    confirm() {
        $('[data-confirm]').click(function(e) {
            var button = $(this)
            var form = button.closest('form')
            var modal = $('#confirm-modal')

            button.attr('disabled', 'disabled')
            e.preventDefault()
            modal.modal().one('click', '#yes', function() {
                form.submit()
            })
            button.removeAttr('disabled')
        })
    }

    /**
     * Confirm if the action should be performed
     */
    confirm() {
        $('[data-status-confirm]').click(function(e) {
            var button = $(this)
            var form = button.closest('form')
            var modal = $('#confirm-status-modal')

            button.attr('disabled', 'disabled')
            e.preventDefault()
            modal.modal().one('click', '#yes', function() {
                form.submit()
            })
            button.removeAttr('disabled')
        })
    }

    /**
     * Submit request via ajax
     */
    uploading() {
        $('[data-uploading]').click(function(e) {
            var button = $(this)
            var form = button.closest('form')

            e.preventDefault()
            button.html('Uploading...')
            button.attr('disabled', 'disabled')

            form.submit()
        })
    }
    /**
     * popover on button click
     */
    popover(){
        $("[data-toggle=popover]").popover({
            html:true
        });
    }
}

export default Messages