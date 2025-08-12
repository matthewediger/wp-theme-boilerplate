// Declare the global variable
declare const homeSpecOfColoradoTwentyTwentyFiveAdmin: {
  ajax_url: string;
  _ajax_nonce: string;
};

document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('run_safelist_export');
  if (!btn || typeof homeSpecOfColoradoTwentyTwentyFiveAdmin === 'undefined') return;

  const handleSafelistExportClick = (e: Event) => {
    e.preventDefault();
    btn.setAttribute('disabled', 'true');
    const originalText = btn.textContent;
    btn.textContent = 'Exporting...';

    fetch(homeSpecOfColoradoTwentyTwentyFiveAdmin.ajax_url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
      },
      body: new URLSearchParams({
        action: 'homespecofcolorado_twentytwentyfive_export_wp_classes',
        _ajax_nonce: homeSpecOfColoradoTwentyTwentyFiveAdmin._ajax_nonce
      })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(data.data.message);
        } else {
          alert('Export failed: ' + (data.data && data.data.message ? data.data.message : 'Unknown error'));
        }
      })
      .catch((err) => {
        alert('Export failed: Network error');
      })
      .finally(() => {
        btn.removeAttribute('disabled');
        btn.textContent = originalText;
      });
  }

  btn.addEventListener('click', handleSafelistExportClick);
});