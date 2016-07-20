import 'json-editor';
import { isEmpty, defaults, keys, pick } from 'lodash';

// Defaultize json from laravel to json editor
const defaultizeJson = (json) => {
  const defaultJson = {
    title: '',
    imageUrl: '',
    overlay: true,
  };
  return pick(defaults(json, defaultJson), keys(defaultJson));
};

function popupEditPage(element) {

  // Set up JSON editor for popup config...

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
          format: 'url',
        },
        overlay: {
          type: 'boolean',
          title: 'Background Transparente',
          format: 'checkbox',
          default: true,
        },
      }
    }
  });

  // Show popup using current editor configuration
  const showPopup = () =>
    tripopupper.run(editor.getValue());

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
    $.material.init('#popup-form');
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

  // Toggle debug mode in snippet
  $('#popup-snippet-debug-mode-toggle').on('change', function () {
    const isInDebugMode = $(this).is(':checked');
    $('#popup-embedded-snippet').find(`[data-debug='0']`).toggle(!isInDebugMode);
    $('#popup-embedded-snippet').find(`[data-debug='1']`).toggle(isInDebugMode);
  });

  // Local popup launcher with current editor conf...
  $('#popup-launcher').on('click', () => showPopup());

  // Keyboard tricks...
  $(document).on('keydown', e => {
    const tag = e.target.tagName.toLowerCase();

    // Press spacebar but not when edit stuff...
    if (e.keyCode === 32 && tag !== 'input' && tag !== 'textarea' && tag !== 'checkbox') {
      showPopup();
    }
  });
}

export default popupEditPage;
