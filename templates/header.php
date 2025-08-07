<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GajiApp - Sistem Penggajian Modern</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-blue: #0d6efd;
            --bs-indigo: #6610f2;
            --bs-purple: #6f42c1;
            --bs-pink: #d63384;
            --bs-red: #dc3545;
            --bs-orange: #fd7e14;
            --bs-yellow: #ffc107;
            --bs-green: #198754;
            --bs-teal: #20c997;
            --bs-cyan: #0dcaf0;
            --bs-white: #fff;
            --bs-gray: #6c757d;
            --bs-gray-dark: #343a40;
            --bs-primary: #4f46e5; /* Warna utama baru (Indigo) */
            --bs-secondary: #64748b; /* Abu-abu netral */
            --bs-success: #10b981;
            --bs-info: #0ea5e9;
            --bs-warning: #f59e0b;
            --bs-danger: #ef4444;
            --bs-light: #f8fafc; /* Latar belakang utama */
            --bs-dark: #1e293b; /* Sidebar */
            --bs-font-sans-serif: 'Inter', sans-serif;
            --bs-body-font-family: var(--bs-font-sans-serif);
            --bs-body-color: #334155; /* Warna teks utama */
            --bs-body-bg: var(--bs-light);
        }

        body {
            font-family: var(--bs-body-font-family);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        /* Header (Top Bar) */
        .app-header {
            background-color: #fff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .app-header .navbar-brand {
            color: var(--bs-dark);
            font-weight: 600;
        }

        /* Sidebar (Offcanvas) */
        .offcanvas {
            background-color: var(--bs-dark);
            color: #cbd5e1;
        }
        .offcanvas .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .offcanvas-title {
            color: #fff;
            font-weight: 600;
        }
        .offcanvas .list-group-item {
            background-color: transparent;
            color: #cbd5e1;
            border: 0;
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }
        .offcanvas .list-group-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }
        .offcanvas .list-group-item.active {
            background-color: var(--bs-primary);
            color: #fff;
            font-weight: 600;
        }

        /* Main Content */
        main {
            padding: 2rem;
        }
        
        /* Card Styling */
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            padding: 1rem 1.5rem;
        }

        /* Button Styling */
        .btn {
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

    </style>
</head>
<body>

<header class="app-header navbar navbar-light">
  <div class="container-fluid">
    <button class="btn btn-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <i class="fa-solid fa-bars"></i>
    </button>

    <div class="navbar-brand d-none d-sm-block">
        <i class="fa-solid fa-money-check-dollar text-primary"></i> GajiApp
    </div>
    
    <div></div> 
  </div>
</header>

<main>