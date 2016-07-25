$(document).ready(function() {
    $(function() {
        setModalContent('Initializing System', '<div class="text-center"><span class="fa fa-spinner fa-4x fa-pulse"></span><h4 class="no-margin gap-top">Please Wait...</h4></div>', '');
        openModal('static');

        $.ajax({
            url: '/data/initialize',
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: 'json',
            success: function(response) {
                setModalContent('Initializing System', response['message'], '');

                setTimeout(function() {
                    closeModal();
                }, 2000);
            }
        });

        return false;
    });
});