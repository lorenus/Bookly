<style>
    /* Estilos del botón hamburguesa */
    .hamburger-btn {
        position: relative; /* Cambiado de fixed a relative para que esté dentro del header */
        z-index: 100;
        background: none;
        border: none;
        cursor: pointer;
        padding: 10px;
    }
    
    .hamburger-line {
        display: block;
        width: 30px;
        height: 3px;
        background: #333;
        margin: 6px 0;
        transition: 0.4s;
    }
    
    /* Estilos del menú */
    .menu {
        position: fixed;
        top: 0;
        right: -300px;
        width: 300px;
        height: 100vh;
        background: #f8f8f8;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 90;
    }
    
    .menu.active {
        right: 0;
    }
    
    .menu-header {
        padding: 20px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .menu-items {
        list-style: none;
    }
    
    .menu-items li a {
        display: block;
        padding: 15px 20px;
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #eee;
        transition: background 0.3s;
    }
    
    .menu-items li a:hover {
        background: #e0e0e0;
    }
    
    /* Cuando el menú está activo, animar el botón */
    .hamburger-btn.active .hamburger-line:nth-child(1) {
        transform: rotate(-45deg) translate(-5px, 6px);
    }
    
    .hamburger-btn.active .hamburger-line:nth-child(2) {
        opacity: 0;
    }
    
    .hamburger-btn.active .hamburger-line:nth-child(3) {
        transform: rotate(45deg) translate(-5px, -6px);
    }
    
    /* Overlay para el fondo cuando el menú está abierto */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
        z-index: 80;
    }
    
    .overlay.active {
        opacity: 1;
        visibility: visible;
    }
</style>

<!-- Botón hamburguesa -->
<button class="hamburger-btn" id="hamburgerBtn" aria-label="Menú principal">
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
    <span class="hamburger-line"></span>
</button>

<!-- Menú lateral -->
<nav class="menu" id="menu">
    <div class="menu-header">
        <h2>Menú</h2>
    </div>
    <ul class="menu-items">
        @foreach($menuItems as $item)
            <li><a href="{{ $item['url'] }}">{{ $item['text'] }}</a></li>
        @endforeach
    </ul>
</nav>

<!-- Overlay para cerrar el menú al hacer clic fuera -->
<div class="overlay" id="overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const menu = document.getElementById('menu');
        const overlay = document.getElementById('overlay');
        
        hamburgerBtn.addEventListener('click', () => {
            menu.classList.toggle('active');
            hamburgerBtn.classList.toggle('active');
            overlay.classList.toggle('active');
        });
        
        overlay.addEventListener('click', () => {
            menu.classList.remove('active');
            hamburgerBtn.classList.remove('active');
            overlay.classList.remove('active');
        });
    });
</script>