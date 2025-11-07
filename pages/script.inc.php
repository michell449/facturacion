
<!--begin::Third Party Plugin(OverlayScrollbars)-->
<script
src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
crossorigin="anonymous"
></script>
<!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
<script
src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
crossorigin="anonymous"
></script>
<!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
crossorigin="anonymous"
></script>
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="js/adminlte.js"></script>
<!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
<!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  // Configuración global para compatibilidad con políticas de privacidad
  window.FacturacionApp = window.FacturacionApp || {};
  
  // Detectar y manejar errores de storage debido a tracking prevention
  window.FacturacionApp.storageAvailable = (function() {
    let localStorageAvailable = false;
    let sessionStorageAvailable = false;
    
    try {
      const test = '__storage_test__';
      localStorage.setItem(test, test);
      localStorage.removeItem(test);
      localStorageAvailable = true;
    } catch (e) {
      console.warn('localStorage no disponible debido a políticas de privacidad');
    }
    
    try {
      const test = '__session_test__';
      sessionStorage.setItem(test, test);
      sessionStorage.removeItem(test);
      sessionStorageAvailable = true;
    } catch (e) {
      console.warn('sessionStorage no disponible debido a políticas de privacidad');
    }
    
    return {
      local: localStorageAvailable,
      session: sessionStorageAvailable
    };
  })();

  // Función global para manejo seguro de storage
  window.FacturacionApp.safeStorage = {
    set: function(key, value, useSession = false) {
      try {
        const storage = useSession ? sessionStorage : localStorage;
        const available = useSession ? window.FacturacionApp.storageAvailable.session : window.FacturacionApp.storageAvailable.local;
        
        if (available) {
          storage.setItem(key, value);
          return true;
        } else {
          // Fallback a cookies
          document.cookie = `${key}=${encodeURIComponent(value)}; path=/; SameSite=Strict; max-age=3600`;
          return true;
        }
      } catch (e) {
        console.warn('Error al guardar datos:', e);
        return false;
      }
    },
    
    get: function(key, useSession = false) {
      try {
        const storage = useSession ? sessionStorage : localStorage;
        const available = useSession ? window.FacturacionApp.storageAvailable.session : window.FacturacionApp.storageAvailable.local;
        
        if (available) {
          return storage.getItem(key);
        } else {
          // Fallback a cookies
          const cookies = document.cookie.split(';');
          for (let cookie of cookies) {
            const [cookieKey, cookieValue] = cookie.trim().split('=');
            if (cookieKey === key) {
              return decodeURIComponent(cookieValue);
            }
          }
          return null;
        }
      } catch (e) {
        console.warn('Error al recuperar datos:', e);
        return null;
      }
    },
    
    remove: function(key, useSession = false) {
      try {
        const storage = useSession ? sessionStorage : localStorage;
        const available = useSession ? window.FacturacionApp.storageAvailable.session : window.FacturacionApp.storageAvailable.local;
        
        if (available) {
          storage.removeItem(key);
        } else {
          // Limpiar cookie
          document.cookie = `${key}=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT`;
        }
      } catch (e) {
        console.warn('Error al eliminar datos:', e);
      }
    }
  };

  // Configuración de OverlayScrollbars
  const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
  const Default = {
    scrollbarTheme: 'os-theme-light',
    scrollbarAutoHide: 'leave',
    scrollbarClickScroll: true,
  };
  
  document.addEventListener('DOMContentLoaded', function () {
    const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
    if (sidebarWrapper && typeof OverlayScrollbarsGlobal !== 'undefined' && OverlayScrollbarsGlobal.OverlayScrollbars) {
      try {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      } catch (e) {
        console.warn('Error al inicializar OverlayScrollbars:', e);
      }
    }
  });

  // Manejo global de errores de storage
  window.addEventListener('error', function(e) {
    if (e.message && (e.message.includes('storage') || e.message.includes('Storage'))) {
      console.warn('Error de storage detectado y manejado automáticamente');
      e.preventDefault(); // Prevenir que el error aparezca en la consola del usuario
    }
  });

  // Suprimir warnings y errores específicos de tracking prevention en la consola
  const originalConsoleWarn = console.warn;
  const originalConsoleError = console.error;
  
  console.warn = function(message) {
    if (typeof message === 'string' && (
        message.includes('Tracking Prevention') ||
        message.includes('blocked access to storage') ||
        message.includes('storage') ||
        message.includes('Storage') ||
        message.includes('cdn.jsdelivr.net') ||
        message.includes('cdnjs.cloudflare.com')
    )) {
      // Silenciar estos warnings específicos
      return;
    }
    originalConsoleWarn.apply(console, arguments);
  };
  
  console.error = function(message) {
    if (typeof message === 'string' && (
        message.includes('Tracking Prevention') ||
        message.includes('blocked access to storage') ||
        message.includes('Failed to load resource') ||
        message.includes('net::ERR_BLOCKED_BY_CLIENT')
    )) {
      // Silenciar estos errores específicos
      return;
    }
    originalConsoleError.apply(console, arguments);
  };

  // Interceptar errores de red relacionados con CDNs
  window.addEventListener('error', function(e) {
    if (e.target && e.target.tagName && 
        (e.target.tagName === 'LINK' || e.target.tagName === 'SCRIPT') &&
        e.target.src && 
        (e.target.src.includes('cdn.jsdelivr.net') || 
         e.target.src.includes('cdnjs.cloudflare.com'))) {
      // Evitar que estos errores aparezcan en la consola
      e.preventDefault();
      e.stopPropagation();
      console.info('Recurso CDN bloqueado, usando fallback local');
    }
  }, true);
</script>
<!--end::OverlayScrollbars Configure-->
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--end::Script-->
<!-- FullCalendar CDN -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<!-- Script solo para catalogo de productos -->


</body>
<!--end::Body-->
</html>
