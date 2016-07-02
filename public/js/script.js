function openModal(backdrop) {
    if(backdrop == undefined) {
        backdrop = true;
    }

    $('.modal').modal({
        show: true,
        backdrop: backdrop
    });
}

function closeModal() {
    $('.modal').modal('hide');
}

function setModalContent(header, body, footer) {
    $('.modal .modal-header').html('<h3 class="no-margin">' + header + '</h3>');
    $('.modal .modal-body').html(body);
    $('.modal .modal-footer').html(footer);
}

function setModalLoader() {
    $('.modal .modal-header').html('');
    $('.modal .modal-body').html('<div class="text-center"><span class="fa fa-spinner fa-4x fa-pulse"></span><h4 class="no-margin gap-top">Please Wait...</h4></div>');
    $('.modal .modal-footer').html('');
}

function unsetModalContent() {
    $('.modal .modal-header').html('');
    $('.modal .modal-body').html('');
    $('.modal .modal-footer').html('');
}

function onDataButtonClick(dataButton, func) {
    $('[data-button="' + dataButton + '"]').click(func);
}

function onDataFormSubmit(dataForm, func) {
    $('[data-form="' + dataForm + '"]').submit(func);
}

function onDynamicDataButtonClick(dataButton, func) {
    $('body').on('click', '[data-button="' + dataButton + '"]', func);
}