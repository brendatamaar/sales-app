<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Portal SWM</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

    {{-- Vendor CSS --}}
    <link href="{{ asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
