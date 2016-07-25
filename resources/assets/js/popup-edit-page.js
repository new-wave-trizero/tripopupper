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

  // See: https://github.com/jdorn/json-editor/blob/master/src/editors/upload.js
  // Extend json-editor default upload UI behaviour...
  JSONEditor.defaults.editors.upload = JSONEditor.defaults.editors.upload.extend({
    build: function() {
      var self = this;
      this.title = this.header = this.label = this.theme.getFormInputLabel(this.getTitle());

      // Input that holds the base64 string
      this.input = this.theme.getFormInputField('hidden');
      this.container.appendChild(this.input);

      // Don't show uploader if this is readonly
      if(!this.schema.readOnly && !this.schema.readonly) {

        if(!this.jsoneditor.options.upload) throw "Upload handler required for upload editor";

        // File uploader
        this.uploader = this.theme.getFormInputField('file');

        this.uploader.addEventListener('change',function(e) {
          e.preventDefault();
          e.stopPropagation();

          if(this.files && this.files.length) {
            var fr = new FileReader();
            fr.onload = function(evt) {
              self.preview_value = evt.target.result;
              self.refreshPreview();
              self.onChange(true);
              fr = null;
            };
            fr.readAsDataURL(this.files[0]);
          }
        });
      }

      var description = this.schema.description;
      if (!description) description = '';

      this.control = this.theme.getFormControl(this.label, this.uploader||this.input);
      this.container.appendChild(this.control);

      this.preview = this.getPreview();
      this.container.appendChild(this.preview);
    },
    getPreview: function() {
      var el = document.createElement('div');
      el.className = 'upload-image-preview';
      return el;
    }
  });

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
    // TODO: Error wont work fix-it!
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
    theme: 'bootstrap3',
    disable_collapse: true,
    disable_edit_json: true,
    disable_properties: true,
    schema: {
      title: 'Configura Popup',
      type: 'object',
      properties: {
        title: {
          type: 'string',
          description: 'Titolo del popup',
          title: 'Titolo',
        },
        imageUrl: {
          type: 'string',
          title: 'Immagine',
          format: 'url',
          options: {
            upload: true
          },
          // TODO: Improve preview rendering...
          links: [
            {
              'href': '{{self}}',
              'rel': 'Vedi'
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
    tripopupper.run(editor.getValue(), $('#popup-snippet-debug-mode-toggle').is(':checked'));

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
  //$(document).on('keydown', e => {
    //const tag = e.target.tagName.toLowerCase();

    //// Press spacebar but not when edit stuff...
    //if (e.keyCode === 32 && tag !== 'input' && tag !== 'textarea' && tag !== 'checkbox') {
      //showPopup();
    //}
  //});
}

export default popupEditPage;
