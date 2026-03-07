<footer class="main-footer bg-dark text-white py-4 mt-auto">
    <div class="container-fluid">
        <div class="row">
            <!-- Colonne 1 : Logo et copyright -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Carioca & Coquelicot" style="height: 45px; width: auto; margin-right: 12px;">
                    <div>
                        <h5 class="mb-0 text-white font-weight-bold" style="line-height: 1.2;">Carioca</h5>
                        <h5 class="mb-0 text-white font-weight-bold" style="line-height: 1.2;">& Coquelicot</h5>
                    </div>
                </div>
                <p class="text-white-50 small">
                    Solution complète de gestion de stocks, commandes, fournisseurs et vendeurs.
                </p>
                <div class="mt-3">
                    <span class="text-white-50">&copy; {{ date('Y') }} Carioca & Coquelicot</span>
                    <span class="text-white-50 mx-2">|</span>
                    <span class="text-white-50">Version 2.0.0</span>
                </div>
            </div>

            <!-- Colonne 2 : Liens rapides -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-white font-weight-bold mb-3">Liens rapides</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.products.index') }}" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-box mr-2"></i>Produits
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.stocks.index') }}" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-cubes mr-2"></i>Stocks
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.orders.index') }}" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-shopping-cart mr-2"></i>Commandes
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne 3 : Fournisseurs et vendeurs -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-white font-weight-bold mb-3">Partenaires</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('admin.suppliers.index') }}" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-truck mr-2"></i>Fournisseurs
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('admin.vendors.index') }}" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-store mr-2"></i>Vendeurs
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne 4 : Contact et support -->
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-white font-weight-bold mb-3">Support</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-question-circle mr-2"></i>Aide
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-white-50 text-decoration-none hover-gold">
                            <i class="fas fa-envelope mr-2"></i>Contact
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Colonne 5 : Réseaux sociaux -->
            <div class="col-lg-2 col-md-12">
                <h6 class="text-white font-weight-bold mb-3">Suivez-nous</h6>
                <div class="d-flex flex-wrap justify-content-center justify-content-lg-start">
                    <a href="#" class="social-icon bg-white-10 rounded-circle mr-2 mb-2 d-flex align-items-center justify-content-center">
                        <i class="fab fa-facebook-f text-white"></i>
                    </a>
                    <a href="#" class="social-icon bg-white-10 rounded-circle mr-2 mb-2 d-flex align-items-center justify-content-center">
                        <i class="fab fa-twitter text-white"></i>
                    </a>
                    <a href="#" class="social-icon bg-white-10 rounded-circle mr-2 mb-2 d-flex align-items-center justify-content-center">
                        <i class="fab fa-instagram text-white"></i>
                    </a>
                </div>
                <div class="mt-3 text-center text-lg-left">
                    <a href="#" class="text-white-50 text-decoration-none hover-gold small d-block mb-1">
                        <i class="fas fa-cookie-bite mr-1"></i>Politique
                    </a>
                    <a href="#" class="text-white-50 text-decoration-none hover-gold small d-block">
                        <i class="fas fa-file-contract mr-1"></i>CGU
                    </a>
                </div>
            </div>
        </div>

        <hr class="border-white-20 my-4">

        <div class="row">
            <div class="col-md-12 text-center">
                <p class="text-white-50 small mb-0">
                    <i class="fas fa-code mr-1"></i> Développé avec <i class="fas fa-heart text-danger mx-1"></i> pour 
                    <span class="text-white font-weight-bold">Carioca & Coquelicot</span>
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
.bg-dark {
    background: #1a1a2e !important;
    border-top: 3px solid #f7d44a;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.6) !important;
}

.border-white-20 {
    border-color: rgba(255, 255, 255, 0.15) !important;
}

.bg-white-10 {
    background-color: rgba(255, 255, 255, 0.1) !important;
    transition: all 0.3s ease;
}

.hover-gold {
    transition: all 0.3s ease;
    text-decoration: none;
}
.hover-gold:hover {
    color: #f7d44a !important;
    transform: translateX(3px);
}

.social-icon {
    width: 36px;
    height: 36px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}
.social-icon:hover {
    background-color: #f7d44a !important;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(247, 212, 74, 0.3);
    border-color: #f7d44a;
}
.social-icon:hover i {
    color: #1a1a2e !important;
}

h6.text-white {
    position: relative;
    padding-bottom: 8px;
    margin-bottom: 15px;
}
h6.text-white::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 40px;
    height: 2px;
    background: #f7d44a;
}

/* Responsive footer */
@media (max-width: 991px) {
    .main-footer .row > div {
        margin-bottom: 2rem;
    }
}

@media (max-width: 768px) {
    .main-footer {
        text-align: center;
        padding: 1.5rem 1rem !important;
        padding-bottom: 80px !important;
    }
    
    .main-footer .d-flex.align-items-center {
        justify-content: center;
    }
    
    h6.text-white::after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .main-footer ul {
        text-align: center;
        padding-left: 0;
    }
    
    .main-footer .d-flex.flex-wrap {
        justify-content: center !important;
    }
}
</style>