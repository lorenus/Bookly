@props(['disabled' => false])

<input 
    @disabled($disabled) 
    {{ $attributes->merge([
        'style' => 'display: block; 
                  font-family: "Providence", sans-serif;
                  color:rgb(0, 0, 0);
                  width: 100%; 
                  border: none; 
                  border-bottom: 2px solid black; 
                  background-color: transparent; 
                  border-radius: 0; 
                  box-shadow: none; 
                  text-align: center;
                  outline: none;
                  padding-bottom: 0; 
                  margin-bottom: 5%;
                  margin-top: 0.1rem;', 
    ]) }}>