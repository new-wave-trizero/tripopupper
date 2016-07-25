// Sadly json-editor export JSONEditor to window istead of UMD...
import 'json-editor';
import laravelConfig from './laravel-config';
import { merge } from 'lodash';

// Defaultize json from laravel to json editor
const defaultizeJson = (emptyEditorValue, json) =>
  merge(emptyEditorValue, json);

function popupEditPage(element) {

  // Set up JSON editor for popup config...
  const editorContainer = document.getElementById('popup-config-editor');
  const json = $(editorContainer).data('json');
  const popup = $(editorContainer).data('popup');

  // Upload image related to popup
  JSONEditor.defaults.options.upload = function(type, file, cbs) {
    const data = new FormData();
    data.append('image', file);

    $.ajax({
      type: 'POST',
      url: `${laravelConfig.app_url}/popup/${popup.name}/upload-image`,
      // TODO: I have no idea if this shitty code really work...
      xhr: function() {
        const myXhr = $.ajaxSettings.xhr();
        if (myXhr.upload) {
          myXhr.upload.addEventListener('progress',progress, false);
        }
        return myXhr;
      },
      processData: false,
      contentType: false,
      dataType: 'json',
      data,
    })
    .done((url) => {
      cbs.success(url);
    })
    .fail(() => cbs.failure('Errore nell\'upload del file'));

    // Notify progress to json editor
    function progress(e) {
      if (e.lengthComputable) {
        const max = e.total;
        const current = e.loaded;

        const percentage = (current * 100) / max;
        cbs.updateProgress(percentage);
      }
    }
  };

  const editor = new JSONEditor(editorContainer, {
    theme: 'html',
    disable_collapse: true,
    disable_edit_json: true,
    disable_properties: true,
    schema: {
      title: 'Configura Popup',
      type: 'object',
      properties: {
        title: {
          type: 'string',
          title: 'Titolo',
        },
        //imageUrl: {
          //type: 'string',
          //title: 'URL Immagine',
          //format: 'url',
        //},
        imageUrl: {
          type: 'string',
          title: 'URL Immagine',
          format: 'url',
          options: {
            upload: true
          },
          links: [
            {
              'href': '{{self}}',
              'rel': 'view'
            }
          ]
        },
        start: {
          type: 'string',
          format: 'date',
          title: 'Da',
        },
        end: {
          type: 'string',
          format: 'date',
          title: 'A',
        },
        delay: {
          type: 'number',
          title: 'Ritardo (secondi)',
        },
        overlay: {
          type: 'boolean',
          title: 'Background Transparente',
          format: 'checkbox',
          default: true,
        },
        experimental: {
          type: 'object',
          title: 'Funzioni Sperimentali',
          properties: {
            qandoShop: {
              type: 'string',
              title: 'Qando Shop',
            }
          }
        }
      }
    }
  });
  editor.setValue(defaultizeJson(editor.getValue(), json));

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
