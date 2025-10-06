// Sidebar collapse (desktop)
document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('app');
  const btn = document.getElementById('btnSidebarToggle');
  if(btn && root){
    btn.addEventListener('click', () => root.classList.toggle('sidebar-collapsed'));
  }

  // Cerrar offcanvas al seleccionar un enlace
  document.querySelectorAll('#offcanvasSidebar .menu-link').forEach(a => {
    a.addEventListener('click', () => {
      const el = document.getElementById('offcanvasSidebar');
      const oc = bootstrap.Offcanvas.getInstance(el) || new bootstrap.Offcanvas(el);
      oc.hide();
    });
  });

  // Tooltips (si los usas)
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
});
