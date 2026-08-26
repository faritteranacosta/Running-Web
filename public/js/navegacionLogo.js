(function () {
  const scrollKey = `runningweb-scroll:${window.location.href}`;
  const tabKey = `runningweb-tab:${window.location.href}`;
  const navigationEntry = performance.getEntriesByType('navigation')[0];
  const wasReloaded = navigationEntry && navigationEntry.type === 'reload';

  document.querySelectorAll('.brand, .sidebar-brand').forEach(function (brand) {
    brand.addEventListener('click', function (event) {
      event.preventDefault();
      sessionStorage.setItem(scrollKey, String(window.scrollY));

      const activeTab = document.querySelector('.tab-content.active');
      if (activeTab) {
        sessionStorage.setItem(tabKey, activeTab.id);
      }

      window.location.reload();
    });
  });

  if (!wasReloaded) return;

  const savedScroll = sessionStorage.getItem(scrollKey);
  const savedTab = sessionStorage.getItem(tabKey);

  if (savedTab && typeof window.showTab === 'function') {
    window.showTab(savedTab);
  }

  if (savedScroll !== null) {
    requestAnimationFrame(function () {
      window.scrollTo(0, Number(savedScroll));
      sessionStorage.removeItem(scrollKey);
      sessionStorage.removeItem(tabKey);
    });
  }
})();
