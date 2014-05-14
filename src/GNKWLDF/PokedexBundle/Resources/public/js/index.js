$(document).ready(function(){
    $("a.pokedex-action").click(function(event){
        event.preventDefault();
        var pokemon = $(this);
        var pageurl = pokemon.attr('href');
        var number = pokemon.attr("data-pokemon-number");
        var pokemonElement = pokemon.children('.pokedex-element');
        if(!pokemonElement.hasClass('selected'))
        {
            $.ajax({
                type: "GET",
                url: Routing.generate('pokedex_pokemon_description', { number: number }),
                success: function(data){
                    $('.pokedex-action .pokedex-element').removeClass( "selected" );
                    pokemonElement.addClass( "selected" );
                    $('#pokeview').html(data);
                    window.history.pushState({path:pageurl},null,pageurl);
                },
            });
        }
    });
});