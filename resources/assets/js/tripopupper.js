import React from 'react';
import ReactDOM from 'react-dom';
import Clipboard from 'clipboard';
import laravelConfig from './laravel-config';
import popupEditPage from './popup-edit-page';
import popupsCompositionPage from './popups-composition-page';
import NewPopupForm from './components/NewPopupForm';

// Welcome to vanilla paradise!

// Add csrf token to jquery ajax calls...
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': laravelConfig.csrf_token,
  }
});

// Set up global clippboard buttos
new Clipboard('.btn-clipboard');

// Inline popup launcher buttom
$(document).on('click', '.inline-popup-launcher', function(e) {
  e.preventDefault();
  tripopupper.run($(this).data('config'));
});

// Confirm form action with a modal
const confirmModal = $(`<div id="form-confirm-modal" class="modal"></div>`).appendTo($('body'));

$(document).on('click', '.ask-confirm', function (e) {
  e.preventDefault();

  const $form = $(this).closest('form');
  const title = $(this).attr('data-confirm-title');
  const body = $(this).data('confirm-body');
  const btnTitle = $(this).data('confirm-btn-title');
  const btnClass = $(this).data('confirm-btn-class');

  confirmModal.html(`
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">${title}</h4>
        </div>
        <div class="modal-body">
          <p>${body}</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
          <button type="button" class="btn btn-confirm ${btnClass}">${btnTitle}</button>
        </div>
      </div>
    </div>
  `);
  confirmModal.find('.btn-confirm').on('click', () => {
    $form.submit();
  })
  $('#form-confirm-modal').modal();
});

// Popup edit page...
Array.from(document.getElementsByClassName('edit-popup-page'))
  .forEach(popupEditPage);

// Popups composition page...
Array.from(document.getElementsByClassName('popups-composition-page')).forEach(popupsCompositionPage);

// New popup form component...
Array.from(document.getElementsByClassName('new-popup-form-container')).forEach(element => {
  const action = element.getAttribute('data-action');
  const name = element.getAttribute('data-name');
  const nameError = element.getAttribute('data-name-error');
  ReactDOM.render(<NewPopupForm action={action} name={name} nameError={nameError} />, element);
});
