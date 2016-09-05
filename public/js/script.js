function createChart(id, chartType, data, options) {
    var canvasChart = new Chart($('#' + id), {
        type: chartType,
        data: data,
        options: options
    });
}

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

function showLoadrModal() {
    $('.loadr').fadeIn(250);
}

function hideLoadrModal() {
    $('.loadr').fadeOut(250);
}

function unsetModalContent() {
    $('.modal .modal-header').html('');
    $('.modal .modal-body').html('');
    $('.modal .modal-footer').html('');
}

function showCrankLoader(id) {
    $('#' + id + '.crank-loader').fadeIn(250);
}

function hideCrankLoader(id) {
    return $('#' + id + '.crank-loader').fadeOut(250);
}

function onDataButtonClick(dataButton, func) {
    $('[data-button="' + dataButton + '"]').click(func);
}

function onDataFormSubmit(dataForm, func) {
    $('[data-form="' + dataForm + '"]').submit(func);
}

function onDataInputKeyUp(dataInput, func) {
    $('[data-input="' + dataInput + '"]').submit(func);
}

function onDynamicDataButtonClick(dataButton, func) {
    $('body').on('click', '[data-button="' + dataButton + '"]', func);
}

function onDynamicDataFormSubmit(dataForm, func) {
    $('body').on('submit', '[data-form="' + dataForm + '"]', func);
}

function validateDataForm(dataForm, validations) {
    $('[data-form="' + dataForm + '"]').bootstrapValidator({
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: validations
    });
}

/*
 * Debugging Functions
 */

function logAjaxError(arg0, arg1, ar2) {
    console.log(arg0.responseText);
}