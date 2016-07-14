import Clipboard from 'clipboard';
import popupEditPage from './popup-edit-page';

// Welcome to vanilla paradise!

// Set up global clippboard button
new Clipboard('.btn-clipboard');

// Inline popup launcher buttom
$(document).on('click', '.inline-popup-launcher', function(e) {
  e.preventDefault();
  tripopupper.run($(this).data('config'));
});

// Popup edit page...
Array.from(document.getElementsByClassName('edit-popup-page')).forEach(popupEditPage);
