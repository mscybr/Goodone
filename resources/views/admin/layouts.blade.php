<!DOCTYPE html>

<!-- =============================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
===============================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=============================
 -->
<!-- beautify ignore:start -->
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Admin</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href='{{asset("assets2/img/favicon/favicon.ico")}}' />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.min.css">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href='{{asset("assets2/vendor/fonts/boxicons.css")}}' />

    <!-- Core CSS -->
    <link rel="stylesheet" href='{{asset("assets2/vendor/css/core.css")}}' class="template-customizer-core-css" />
    <link rel="stylesheet" href='{{asset("assets2/vendor/css/theme-default.css")}}' class="template-customizer-theme-css" />
    <link rel="stylesheet" href='{{asset("assets2/css/demo.css")}}' />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href='{{asset("assets2/vendor/libs/perfect-scrollbar/perfect-scrollbar.css")}}' />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src='{{asset("assets2/vendor/js/helpers.js")}}'></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src='{{asset("assets2/js/config.js")}}'></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="{{ route("admin_home") }}" class="app-brand-link">
              <span class="app-brand-text demo menu-text fw-bolder ms-2">Dashboard</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item">
              <a href="{{route("admin_get_app_settings")}}" class="menu-link">
                <div data-i18n="Analytics">Settings</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_get_orders")}}" class="menu-link">
                <div data-i18n="Analytics">Orders</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_get_service_providers")}}" class="menu-link">
                <div data-i18n="Analytics">Service Providers</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_get_users")}}" class="menu-link">
                <div data-i18n="Analytics">Customers</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_get_services")}}" class="menu-link">
                <div data-i18n="Analytics">Services</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_withdraw_requests")}}" class="menu-link">
                <div data-i18n="Analytics">Withdraw Requests</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_create_region_tax")}}" class="menu-link">
                <div data-i18n="Analytics">Regional Taxes</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_get_default_images")}}" class="menu-link">
                <div data-i18n="Analytics">Default Images</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_create_coupon")}}" class="menu-link">
                <div data-i18n="Analytics">Coupons</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_create_category")}}" class="menu-link">
                <div data-i18n="Analytics">Category</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="{{route("admin_create_subcategory")}}" class="menu-link">
                <div data-i18n="Analytics">Subcategory</div>
              </a>
            </li>
           
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              {{-- <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                </div>
              </div> --}}
              <!-- /Search -->

             
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            @yield('content')
          
            <!-- / Content -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src='{{asset("assets2/vendor/libs/jquery/jquery.js")}}'></script>
    <script src='{{asset("assets2/vendor/libs/popper/popper.js")}}'></script>
    <script src='{{asset("assets2/vendor/js/bootstrap.js")}}'></script>
    <script src='{{asset("assets2/vendor/libs/perfect-scrollbar/perfect-scrollbar.js")}}'></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.min.js"></script>

    <script src='{{asset("assets2/vendor/js/menu.js")}}'></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src='{{asset("assets2/js/main.js")}}'></script>
    <script>
      new DataTable('table');
    </script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>