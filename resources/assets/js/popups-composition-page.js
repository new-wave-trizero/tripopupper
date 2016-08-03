import Sortable from 'sortablejs';

function popupsCompositionPage(element) {
  const snippetEl = document.getElementById('popup-embedded-snippet');
  const popupsGroupBagEl = document.getElementById('popups-group-bag');
  const popupsGroupCompositionEl = document.getElementById('popups-group-composition');
  const debugModeCheckbox = document.getElementById('popup-snippet-debug-mode-toggle');
  const launchCompositionBtn = document.getElementById('launch-composition-btn');

  // Is Debug mode active?
  const isDebugMode = () => debugModeCheckbox.checked;

  // Render snippet accourding to popups and debugMode
  const renderSnippet = (popups, debugMode) => snippetEl.innerText = snippet(popups, debugMode);

  Sortable.create(popupsGroupBagEl, {
    animation: 150,
    group: 'popups',
  });

  const popupsSortable = Sortable.create(popupsGroupCompositionEl, {
    animation: 150,
    group: 'popups',
    onSort: (e) => renderSnippet(popupsSortable.toArray(), isDebugMode()),
  });

  debugModeCheckbox.addEventListener('change', () => renderSnippet(popupsSortable.toArray(), isDebugMode()));

  // The magic happens here!
  launchCompositionBtn.addEventListener('click', () => tripopupper.launch(popupsSortable.toArray(), isDebugMode()));

  renderSnippet(popupsSortable.toArray(), isDebugMode());
}

const snippet = (popups = [], debugMode = false) => (
`<script src="https://rawgit.com/new-wave-trizero/tripopupper-js/master/lib/tripopupper.js"></script>
<script>tripopupper.launch([${popups.map(popup => `'${popup}'`).join(',')}]${debugMode ? ', true' : ''})</script>`
);

export default popupsCompositionPage;
