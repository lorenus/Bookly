@props(['value'])

<label 
{{ $attributes->merge([
        'style' => 'display: block;
                  font-family: "providence";
                  font-size: x-large;
                  color:rgb(0, 0, 0);
                  width: 100%;
                  text-align: center;
                  margin-bottom: 0.25rem;',
    ]) }}>
    {{ $value ?? $slot }}
</label>
