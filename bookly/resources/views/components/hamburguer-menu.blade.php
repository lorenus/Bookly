<div class="menu-container">
    <!-- Botón hamburguesa personalizado -->
    <button class="hamburger-btn" id="hamburgerBtn" aria-label="Menú principal">
        <img src="{{ asset('img/elementos/hamburguesa.png') }}" alt="Abrir menú" class="hamburger-icon">
        <img src="{{ asset('img/elementos/cerrar.png') }}" alt="Cerrar menú" class="close-icon" style="display: none;">
    </button>

    <!-- Menú lateral con imagen de fondo -->
    <nav class="menu" id="menu" >
        <div class="menu-content">
            <ul class="menu-items">
                @foreach($menuItems as $item)
                    <li><a href="{{ $item['url'] }}">{{ $item['text'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </nav>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const menu = document.getElementById('menu');
    const overlay = document.getElementById('overlay');
    const hamburgerIcon = document.querySelector('.hamburger-icon');
    const closeIcon = document.querySelector('.close-icon');

    hamburgerBtn.addEventListener('click', () => {
        menu.classList.toggle('active');
        overlay.classList.toggle('active');
        hamburgerIcon.style.display = menu.classList.contains('active') ? 'none' : 'block';
        closeIcon.style.display = menu.classList.contains('active') ? 'block' : 'none';
    });

    overlay.addEventListener('click', () => {
        menu.classList.remove('active');
        overlay.classList.remove('active');
        hamburgerIcon.style.display = 'block';
        closeIcon.style.display = 'none';
    });
});
</script>