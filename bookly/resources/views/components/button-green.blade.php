<button {{ $attributes->merge(['type' => 'submit', 'style' => 'border: none; 
    background-color: transparent; 
    font-family: "Providence Sans"; 
    color:rgb(0, 0, 0); 
    font-size: x-large; 
    cursor: pointer;
    background-position: center 5px; 
    background-image: url("/img/elementos/btn-verde.png"); 
    background-size: contain; 
    background-repeat: no-repeat; 
    width: 180px; 
    height: 60px; 
    display: inline-block; 
    padding: 20px 20px; 
    text-align: center; 
    text-decoration: none;']) }}>
    {{ $slot }}
</button>
