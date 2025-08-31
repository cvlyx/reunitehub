<?php
//session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReuniteHub - Lost & Found Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Raleway:wght@700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            100: '#f5e9ff',
                            300: '#c084fc',
                            500: '#8b5cf6',
                            700: '#6d28d9',
                            900: '#4c1d95'
                        },
                        secondary: {
                            100: '#fffbeb',
                            300: '#fcd34d',
                            500: '#f59e0b',
                            700: '#b45309',
                            900: '#78350f'
                        },
                        accent: {
                            100: '#dcfce7',
                            500: '#10b981',
                            900: '#064e3b'
                        }
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                        raleway: ['Raleway', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f5e9ff 100%);
            min-height: 100vh;
        }
        
        .header-bg {
            background: linear-gradient(120deg, #6d28d9 0%, #8b5cf6 100%);
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            height: 85vh;
        }
        
        .card {
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
        }
        
        .category-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 10;
        }
        
        .lost-badge {
            background: rgba(220, 38, 38, 0.9);
            color: white;
        }
        
        .found-badge {
            background: rgba(16, 185, 129, 0.9);
            color: white;
        }
        
        .item-image {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        
        .pattern-bg {
            background: 
                radial-gradient(circle at 10% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.1) 0%, transparent 20%);
        }
        
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0.6) 100%);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .report-tab {
            border-radius: 15px 15px 0 0;
            padding: 15px 25px;
            transition: all 0.3s ease;
        }
        
        .report-tab.active {
            background: white;
            color: #6d28d9;
            transform: translateY(2px);
        }
        
        .form-container {
            background: white;
            border-radius: 0 20px 20px 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .map-placeholder {
            background: linear-gradient(45deg, #e0e7ff 0%, #ede9fe 100%);
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .map-grid {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(109, 40, 217, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(109, 40, 217, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .pulse {
            animation: pulseAnimation 2s infinite;
        }
        
        @keyframes pulseAnimation {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .floating {
            animation: floating 6s ease-in-out infinite;
        }
        
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 15px;
            border-radius: 5px;
            color: white;
            display: none;
        }
        .alert-success {
            background-color: #10b981;
        }
        .alert-error {
            background-color: #ef4444;
        }
    </style>
</head>
<body class="text-gray-800">
<div id="alert" class="alert"></div>