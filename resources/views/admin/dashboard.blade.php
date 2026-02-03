@extends('layouts.app')

@section('content')
    <style>
        /* Header styling */
        .admin-header {
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            padding: 40px 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .admin-header::after {
            content: "";
            position: absolute;
            top: -40px;
            right: -40px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
        }

        /* Typing effect */
        .typing {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            border-right: 3px solid rgba(255, 255, 255, 0.75);
            animation: blink .75s step-end infinite;
            font-size: 1.1rem;
            margin-top: 10px;
            padding-right: 5px;
            max-width: 100%;
        }

        @keyframes typing {
            from {
                width: 0
            }

            to {
                width: 100%
            }
        }

        @keyframes blink {

            0%,
            100% {
                border-color: transparent;
            }

            50% {
                border-color: white;
            }
        }

        /* Card hover effect */
        .admin-card {
            transition: 0.3s;
            cursor: pointer;
            border-radius: 12px !important;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Logout button full width */
        .logout-btn {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            font-size: 1.1rem;
        }
    </style>

    <div class="container">
        {{-- HEADER --}}
        <div class="admin-header">
            <h2 class="fw-bold mb-0">📊 Dashboard Admin</h2>
            <div id="typing" class="typing">
                “Tetap semangat! Hari ini kesempatan baru untuk jadi lebih baik.”
            </div>
        </div>

        {{-- MENU ADMIN --}}
        <div class="row">

            <div class="col-md-4 mb-3">
                <a href="{{ route('products.index') }}" class="text-decoration-none text-dark">
                    <div class="card admin-card shadow-sm p-3">
                        <h5>📦 Kelola Produk</h5>
                        <p>CRUD produk + import data</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('orders.index') }}" class="text-decoration-none text-dark">
                    <div class="card admin-card shadow-sm p-3">
                        <h5>📝 Orders</h5>
                        <p>Kelola status & hapus order</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('barang-masuk.index') }}" class="text-decoration-none text-dark">
                    <div class="card admin-card shadow-sm p-3">
                        <h5>📥 Barang Masuk</h5>
                        <p>CRUD barang masuk</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="{{ route('orders.export') }}" class="text-decoration-none text-dark">
                    <div class="card admin-card shadow-sm p-3">
                        <h5>📤 Export Orders</h5>
                        <p>Download laporan</p>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mb-3">
                <a href="/test-pdf" class="text-decoration-none text-dark">
                    <div class="card admin-card shadow-sm p-3">
                        <h5>🖨 Test PDF</h5>
                        <p>Generate PDF Testing</p>
                    </div>
                </a>
            </div>

        </div>

        {{-- LOGOUT FULL WIDTH --}}
        <a href="{{ route('logout') }}">
            <button class="btn btn-danger logout-btn mt-3">
                🚪 Logout
            </button>
        </a>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const texts = [
                "“Tetap semangat! Hari ini kesempatan baru untuk jadi lebih baik.”",
                "“Fokus pada progres, bukan kesempurnaan.”",
                "“Setiap usaha kecil, jika konsisten, akan menjadi besar.”",
                "“Jangan menyerah, kamu lebih dekat pada hasil daripada yang kamu kira.”",
                "“Kerja keras hari ini adalah kemenangan esok hari.”"
            ];

            const typingElement = document.getElementById("typing");

            let textIndex = 0;
            let charIndex = 0;
            let isDeleting = false;

            function type() {
                const currentText = texts[textIndex];

                // Ketik
                if (!isDeleting) {
                    typingElement.textContent = currentText.substring(0, charIndex + 1);
                    charIndex++;

                    if (charIndex === currentText.length) {
                        setTimeout(() => isDeleting = true, 1200);
                    }
                }
                // Hapus
                else {
                    typingElement.textContent = currentText.substring(0, charIndex - 1);
                    charIndex--;

                    if (charIndex === 0) {
                        isDeleting = false;
                        textIndex = (textIndex + 1) % texts.length;
                    }
                }

                setTimeout(type, isDeleting ? 40 : 70);
            }

            type();
        });
    </script>
@endsection
