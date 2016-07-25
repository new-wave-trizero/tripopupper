import Clipboard from 'clipboard';
import laravelConfig from './laravel-config';
import popupEditPage from './popup-edit-page';

// Welcome to vanilla paradise!

// Add csrf token to jquery ajax calls...
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': laravelConfig.csrf_token,
  }
});

// Set up global clippboard button
new Clipboard('.btn-clipboard');

// Inline popup launcher buttom
$(document).on('click', '.inline-popup-launcher', function(e) {
  e.preventDefault();
  tripopupper.run($(this).data('config'));
});

// Popup edit page...
Array.from(document.getElementsByClassName('edit-popup-page')).forEach(popupEditPage);
