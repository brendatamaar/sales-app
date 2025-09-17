<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>New Apps</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

    {{-- Vendor CSS --}}
    <link href="{{ asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- Legacy/custom stacks yang sudah ada --}}
    @stack('plugin-styles')
    @stack('style')
    @stack('page-styles')

    <style>
        /* ------------------ Modal ------------------ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
        }

        .modal-container {
            width: min(960px, 96vw);
            max-height: 90vh;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
        }

        .modal-header {
            padding: 16px 20px;
            background: #0d6efd;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 18px;
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .close-btn {
            border: none;
            background: transparent;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }

        .modal-body {
            padding: 16px 20px;
            overflow-y: auto;
            flex: 1;
        }

        /* ------------------ Form ------------------ */
        .form-section {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 12px;
        }

        .form-section h3 {
            font-size: 14px;
            margin: 0 0 10px;
            display: flex;
            gap: 8px;
            align-items: center;
            color: #0d6efd;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 12px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 8px 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
        }

        .deal-id-wrap {
            display: flex;
            gap: 8px;
        }

        .generate-btn {
            padding: 8px 10px;
            border: 1px solid #198754;
            background: #198754;
            color: #fff;
            border-radius: 6px;
            cursor: pointer;
        }

        .add-item-btn,
        .remove-item-btn {
            padding: 8px 10px;
            border-radius: 6px;
            cursor: pointer;
            color: #fff;
        }

        .add-item-btn {
            background: #0d6efd;
            border: 1px solid #0d6efd;
        }

        .remove-item-btn {
            background: #dc3545;
            border: 1px solid #dc3545;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-primary {
            background: #0d6efd;
            color: #fff;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ------------------ Page Header ------------------ */
        .page-header-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-bottom: 18px;
        }

        .page-header-title {
            font-weight: 600;
            font-size: 26px;
            display: flex;
            align-items: center;
        }

        .page-header-title i {
            margin: 0 8px;
            color: #6b7280;
        }

        .page-header-badge {
            background: #e8f1ff;
            color: #246bfd;
            border-radius: 16px;
            padding: 4px 10px;
            font-size: 12px;
            margin-left: 8px;
        }

        .page-header-currency {
            position: absolute;
            right: 0;
            top: 2px;
            color: #6b7280;
            font-size: 14px;
        }

        .breadcrumb-lite {
            color: #6b7280;
            font-size: 14px;
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .breadcrumb-lite span {
            margin: 0 4px;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        /* ------------------ Kanban ------------------ */
        .kanban-card a.stretched-link {
            pointer-events: none;
        }

        .kanban-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
        }

        .kanban-col {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            min-height: 200px;
            transition: background 0.2s ease;
        }

        .kanban-head {
            font-weight: bold;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .kanban-sub {
            padding: 5px 10px;
            color: #666;
            font-size: 12px;
        }

        .kanban-body {
            padding: 10px;
        }

        .input-group .btn-outline-primary {
            border-left: 0;
        }

        #customerSelect {
            border-right: 0;
        }

        /* Drag Visuals */
        .kanban-card.sortable-chosen {
            opacity: 0.7;
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .kanban-card.sortable-ghost {
            opacity: 0.4;
            background: #f8f9fa;
            border: 2px dashed #6c757d;
        }

        .sortable-fallback {
            background: #e9ecef;
            border: 2px dashed #0d6efd;
            min-height: 50px;
            margin: 4px 0;
            border-radius: 6px;
        }

        .kanban-col.sortable-over {
            background: rgba(13, 110, 253, 0.05);
        }


        /* ===== CUSTOM STYLES ===== */
        .kanban-col {
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .kanban-col.drag-active {
            transform: scale(0.98);
        }

        .kanban-col.drag-over {
            background: #e3f2fd;
            border-color: #2196f3;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, .2);
        }

        .kanban-card {
            cursor: grab;
            transition: all .2s ease;
            border: 1px solid #e0e0e0;
        }

        .kanban-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
            transform: translateY(-1px);
        }

        .kanban-card:active {
            cursor: grabbing;
        }

        .sortable-ghost {
            opacity: .5;
            background: #e3f2fd;
        }

        .sortable-chosen {
            transform: rotate(5deg);
        }

        .sortable-drag {
            transform: rotate(10deg);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .3);
        }

        .form-section {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background: #f8f9fa;
        }

        .form-section legend {
            background: #fff;
            padding: 0 .5rem;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 1rem;
            color: #495057;
        }

        .stage-conditional {
            display: none;
        }

        .item-row {
            background: #fff;
            transition: all .2s ease;
        }

        .item-row:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .deal-content:hover {
            background-color: rgba(0, 123, 255, 0.05);
            border-radius: 0.25rem;
        }

        .kanban-card .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .kanban-sub {
            border-bottom: 1px solid #dee2e6;
        }

        .kanban-sub small {
            color: #28a745;
            font-size: 0.85rem;
        }

        #loadingSpinner {
            backdrop-filter: blur(2px);
            background: rgba(255, 255, 255, .8);
            padding: 2rem;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .kanban-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .action-bar {
                flex-direction: column;
                gap: 1rem;
            }

            .action-left,
            .action-right {
                width: 100%;
                justify-content: space-between;
            }

            .search-box {
                flex: 1;
            }
        }

        .kanban-card:focus-within {
            outline: 2px solid #2196f3;
            outline-offset: 2px;
        }

        .btn:focus,
        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 .2rem rgba(33, 150, 243, .25);
        }

        .fade-in {
            animation: fadeIn .3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn .3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }

            to {
                transform: translateX(0);
            }
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .kanban-grid {
                display: none !important;
                /* Force list view on mobile */
            }

            .action-bar {
                flex-direction: column;
                gap: 1rem;
                padding: 12px;
            }

            .action-left,
            .action-right {
                width: 100%;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 8px;
            }

            .search-box {
                flex: 1;
                min-width: 200px;
            }

            .btn-group[role="group"] {
                display: none !important;
                /* Hide view toggle on mobile */
            }

            .page-header-wrap {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .page-header-currency {
                position: static;
                margin-top: 4px;
            }

            .table-responsive {
                font-size: 14px;
            }

            .table td,
            .table th {
                padding: 8px 4px;
                vertical-align: middle;
            }

            /* Hide less important columns on very small screens */
            @media (max-width: 480px) {

                .table td:nth-child(4),
                /* Store column */
                .table th:nth-child(4) {
                    display: none;
                }
            }
        }

        /* List View Styles */
        .list-view {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .list-view .table {
            margin-bottom: 0;
        }

        .list-view .table th {
            background: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
        }

        .list-view .table td {
            vertical-align: middle;
        }

        .list-view .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* View Toggle Button Improvements */
        .btn-group .btn {
            border-color: #dee2e6;
        }

        .btn-group .btn.active {
            background: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }

        /* Responsive Table */
        .table-responsive {
            border-radius: 8px;
        }

        @media (max-width: 576px) {

            .table-responsive table,
            .table-responsive thead,
            .table-responsive tbody,
            .table-responsive th,
            .table-responsive td,
            .table-responsive tr {
                display: block;
            }

            .table-responsive thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .table-responsive tr {
                border: 1px solid #ccc;
                margin-bottom: 8px;
                border-radius: 4px;
                padding: 8px;
                background: #fff;
            }

            .table-responsive td {
                border: none;
                position: relative;
                padding: 6px 8px 6px 50%;
                text-align: left;
            }

            .table-responsive td:before {
                content: attr(data-label) ": ";
                position: absolute;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                color: #666;
            }
        }
    </style>

</head>

<body data-base-url="{{ url('/') }}">
    <div class="container-scroller" id="app">
        @include('layout.header')

        <div class="container-fluid page-body-wrapper">
            @include('layout.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                @include('layout.footer')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    {{-- Template vendor JS --}}
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>

    {{-- Legacy/custom stacks yang sudah ada --}}
    @stack('plugin-scripts')
    @stack('custom-scripts')

</body>

</html>
