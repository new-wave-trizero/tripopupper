import 'json-editor';
import { isEmpty } from 'lodash';

// Inline popup launcher buttom
$(document).on('click', '.inline-popup-launcher', function(e) {
  e.preventDefault();
  console.log($(this).data('config'));
  tripopupper.run($(this).data('config'));
});

// Popup edit...
Array.from(document.getElementsByClassName('edit-popup')).forEach(element => {
  // Start the vanilla odissea

  // Set up json editor...

  const editorContainer = document.getElementById('popup-config-editor');
  const json = $(editorContainer).data('json');
  const startval = isEmpty(json) ? null : json;

  const editor = new JSONEditor(editorContainer, {
    theme: 'bootstrap3',
    disable_collapse: true,
    disable_edit_json: true,
    disable_properties: true,
    startval,
    schema: {
      title: 'Popup Config',
      type: 'object',
      properties: {
        title: {
          type: 'string',
          title: 'Titolo',
        },
        imageUrl: {
          type: 'string',
          title: 'URL Immagine',
        },
      }
    }
  });

  // Hook form...

  const $form = $('#popup-form');
  const $input = $('<input />', {
    name: 'config',
    type: 'hidden'
  }).appendTo($form);

  const fillInputWithJson = () =>
    $input.val(JSON.stringify(editor.getValue()));

  editor.on('change', fillInputWithJson);

  $form.on('submit', e => {
    const errors = editor.validate();
    const isValid = errors.length === 0;

    // Ensure last json content, when submit with enter
    if (isValid) {
      fillInputWithJson();
    }

    return isValid;
  });

  // Popup launcher...
  $('#popup-launcher').on('click', () => {
    tripopupper.run(editor.getValue());
  });
});
