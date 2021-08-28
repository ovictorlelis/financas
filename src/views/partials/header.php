<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --color-black: #0e1012;
      --color-dark: #1d2025;
      --color-success: #2ecc71;
      --color-danger: #e74c3c;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f3f3;
      color: var(--color-dark);
    }

    .bg-black {
      background-color: var(--color-black);
    }

    .bg-dark {
      background-color: var(--color-dark) !important;
    }

    .bg-success {
      background-color: var(--color-success) !important;
    }

    .text-success {
      color: var(--color-success) !important;
    }

    .bg-danger {
      background-color: var(--color-danger) !important;
    }

    .text-danger {
      color: var(--color-danger) !important;
    }

    .btn-dark {
      background-color: var(--color-dark) !important;
      border-color: var(--color-dark) !important;
    }

    .btn-success {
      background-color: var(--color-success) !important;
      border-color: var(--color-success) !important;
    }

    .btn-danger {
      background-color: var(--color-danger) !important;
      border-color: var(--color-danger) !important;
    }

    .form-control {
      border: none;
      padding: 0.5rem;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .form-check-input:checked {
      background-color: var(--color-dark);
      border: var(--color-dark);
    }

    .form-check-input:focus {
      box-shadow: none;
    }

    .modal-content {
      background-color: #f3f3f3;
      border: none;
    }

    .modal-header {
      border: none;
    }

    .modal-footer {
      border: none;
    }

    .btn:focus,
    .btn-close:focus {
      box-shadow: none !important;
    }

    .page-item.active .page-link {
      background-color: var(--color-dark);
      border-color: var(--color-dark);
    }

    .page-link {
      color: var(--color-dark);
      border: none;
    }

    .page-link:focus {
      color: var(--color-dark);
      box-shadow: none;
    }

    .page-link:hover {
      color: var(--color-dark);
    }

    .page-item:not(:first-child) .page-link {
      margin-left: initial;
    }

    .dropdown-item.active,
    .dropdown-item:active {
      background-color: var(--color-dark);
    }

    label {
      margin-bottom: 0.3rem;
    }

    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 0.5rem;
    }

    table thead tr {
      background-color: var(--color-dark);
    }

    table thead th {
      color: #fff;
    }

    table tbody tr {
      background-color: #fff;
    }

    tr:first-child td:first-child,
    tr:first-child th:first-child {
      border-top-left-radius: 0.25rem;
    }

    tr:first-child td:last-child,
    tr:first-child th:last-child {
      border-top-right-radius: 0.25rem;
    }

    tr:last-child td:first-child,
    tr:last-child th:first-child {
      border-bottom-left-radius: 0.25rem;
    }

    tr:last-child td:last-child,
    tr:last-child th:last-child {
      border-bottom-right-radius: 0.25rem;
    }

    table th,
    table td {
      padding: 1rem;

      white-space: nowrap;
    }

    table th {
      color: var(--color-dark);
      font-weight: 400;
    }
  </style>

  <title>Finanças <?= isset($title) ? ' - ' . $title : '';  ?></title>
</head>

<body class="min-vh-100">