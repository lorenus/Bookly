@props(['value'])

<label 
{{ $attributes->merge([
        'style' => 'display: block; 
                  font-family: "providence";
                  font-size: large;
                  color:rgb(0, 0, 0);
                  width: 100%;
                  text-align: center;', 
    ]) }}>
    {{ $value ?? $slot }}
</label>