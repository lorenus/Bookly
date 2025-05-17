<button {{ $attributes->merge(['type' => 'submit', 'style' => 'border: none; 
    background-color: transparent; 
    font-family: "Providence Sans"; 
    color:rgb(0, 0, 0); 
    font-size: large; 
    cursor: pointer;
    background-position: center; 
    background-image: url("/img/elementos/boton.png"); 
    background-size: 100% 100%;
    background-repeat: no-repeat;
    width: auto;
    padding: 20px 30px;
    max-height: 40px; 
    display: inline-flex; 
    align-items: center;
    justify-content: center;
    text-align: center; 
    text-decoration: none;
    white-space: nowrap; 
    ']) }}>
    {{ $slot }}
</button>