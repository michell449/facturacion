<!doctype html>
<html lang="es">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $pageTitle; ?></title>
    <!--begin::Security and Privacy Meta Tags-->
    <meta name="referrer" content="strict-origin-when-cross-origin" />
    <meta http-equiv="X-Content-Type-Options" content="nosniff" />
    <!--end::Security and Privacy Meta Tags-->
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="<?php echo $pageTitle; ?>" />
    <meta name="author" content="" />
    <meta
      name="description"
      content=""
    />
    <meta
      name="keywords"
      content=""
    />
  
    <meta name="supported-color-schemes" content="light dark" />
    
    <!--begin::Critical CSS (inline para evitar tracking prevention)-->
    <style>
      /* Fallback de iconos usando símbolos Unicode cuando no carga Bootstrap Icons */
      .icon-fallback {
        font-family: "Segoe UI Symbol", "Segoe UI", Arial, sans-serif;
        font-style: normal;
        font-weight: normal;
      }
      
      /* Evitar FOUC (Flash of Unstyled Content) */
      .content-wrapper {
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
      }
      
      .content-wrapper.loaded {
        opacity: 1;
      }
      
      /* Estilos críticos para el layout básico */
      body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      }
      
      /* Asegurar que los contenedores principales funcionen sin CSS externo */
      .container, .container-fluid {
        max-width: 1140px;
        margin: 0 auto;
        padding: 0 15px;
      }
      
      .card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
      }
      
      .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.375rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
      }
      
      .btn-primary {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
      }
    </style>
    <!--end::Critical CSS-->

    <!--begin::Local CSS (prioridad máxima)-->
    <link rel="preload" href="css/adminlte.css" as="style" onload="this.onload=null;this.rel='stylesheet'" />
    <link rel="stylesheet" href="css/icons-fallback.css" />
    <noscript><link rel="stylesheet" href="css/adminlte.css" /></noscript>
    <!--end::Local CSS-->

    <!--begin::External CSS (carga diferida y condicional)-->
    <script>
      // Detectar si podemos cargar recursos externos sin tracking prevention
      (function() {
        var canLoadExternal = true;
        try {
          // Test básico para detectar si el navegador bloquea recursos externos
          if (navigator.userAgent.includes('Edge') && 
              (window.location.protocol === 'file:' || window.location.hostname === 'localhost')) {
            canLoadExternal = false;
          }
        } catch (e) {
          canLoadExternal = false;
        }
        
        if (canLoadExternal) {
          // Cargar CSS externos de manera diferida con fallback automático
          var cssResources = [
            {
              url: 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css',
              fallback: function() {
                // Activar fallback de iconos Unicode
                document.body.classList.add('icons-fallback-active');
                var icons = document.querySelectorAll('[class*="bi-"]:not(.bi-fallback)');
                icons.forEach(function(icon) {
                  icon.classList.add('bi-fallback');
                });
              }
            },
            {
              url: 'https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css',
              fallback: function() {
                // Aplicar fuentes del sistema como fallback
                document.body.style.fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
              }
            }
          ];
          
          cssResources.forEach(function(resource, index) {
            setTimeout(function() {
              var link = document.createElement('link');
              link.rel = 'stylesheet';
              link.href = resource.url;
              link.crossOrigin = 'anonymous';
              
              var timeout = setTimeout(function() {
                // Si no carga en 3 segundos, activar fallback
                if (resource.fallback) {
                  resource.fallback();
                }
              }, 3000);
              
              link.onload = function() {
                clearTimeout(timeout);
              };
              
              link.onerror = function() {
                clearTimeout(timeout);
                if (resource.fallback) {
                  resource.fallback();
                }
              };
              
              document.head.appendChild(link);
            }, index * 200); // Cargar con pequeños retrasos
          });
        } else {
          // Activar todos los fallbacks inmediatamente
          document.body.classList.add('icons-fallback-active');
          document.body.style.fontFamily = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
          
          var icons = document.querySelectorAll('[class*="bi-"]:not(.bi-fallback)');
          icons.forEach(function(icon) {
            icon.classList.add('bi-fallback');
          });
        }
        
        // Marcar contenido como cargado después de un breve retraso
        document.addEventListener('DOMContentLoaded', function() {
          setTimeout(function() {
            var wrapper = document.querySelector('.content-wrapper');
            if (wrapper) wrapper.classList.add('loaded');
          }, 200);
        });
      })();
    </script>
    <!--end::External CSS-->

  </head>
  <!--end::Head-->