import 'json-editor';
import { isEmpty, defaults, keys, pick } from 'lodash';
import Clipboard from 'clipboard';

// Set up clippboard
new Clipboard('.btn-clipboard');

// Inline popup launcher buttom
$(document).on('click', '.inline-popup-launcher', function(e) {
  e.preventDefault();
  tripopupper.run($(this).data('config'));
});

// Popup edit...
Array.from(document.getElementsByClassName('edit-popup')).forEach(element => {
  // Start the vanilla odissea

  // Set up json editor...

  // NOT A SINGLE FUCK WAS GIVEN IN THAT DAY!
  const defaultizeJson = (json) => {
    const defaultJson = {
      title: '',
      imageUrl: '',
      overlay: true,
    };
    return pick(defaults(json, defaultJson), keys(defaultJson));
  };
  const editorContainer = document.getElementById('popup-config-editor');
  const json = $(editorContainer).data('json');
  const startval = isEmpty(json) ? null : defaultizeJson(json);

  const editor = new JSONEditor(editorContainer, {
    theme: 'bootstrap3',
    disable_collapse: true,
    disable_edit_json: true,
    disable_properties: true,
    startval,
    schema: {
      title: 'Configura Popup',
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
        overlay: {
          type: 'boolean',
          title: 'Background Transparente',
          format: 'checkbox',
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
  editor.on('ready', () => {
    $.material.init('$popup-form');
  });

  $form.on('submit', e => {
    const errors = editor.validate();
    const isValid = errors.length === 0;

    // Ensure last json content, when submit with enter
    if (isValid) {
      fillInputWithJson();
    }

    return isValid;
  });

  // Keyboard tricks...
  $(document).on('keydown', e => {
    const tag = e.target.tagName.toLowerCase();

    // Press spacebar but not when edit stuff...
    if (e.keyCode === 32 && tag !== 'input' && tag !== 'textarea' && tag !== 'checkbox') {
      tripopupper.run(editor.getValue());
    }
  });

  // Popup launcher...
  $('#popup-launcher').on('click', () => {
    tripopupper.run(editor.getValue());
  });
});
