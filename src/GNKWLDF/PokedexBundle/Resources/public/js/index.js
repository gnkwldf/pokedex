$(document).ready(function(){
    $("a.pokedex-action").click(function(event){
        event.preventDefault();
        var pokemon = $(this);
        var pageurl = pokemon.attr('href');
        var number = pokemon.attr("data-pokemon-number");
        var pokemonElement = pokemon.children('.pokedex-element');
        var loader = true;
        if(!pokemonElement.hasClass('selected'))
        {
            var description = $('#pokeview').html();
            setTimeout(function(){
                if(loader)
                {
                    $('#pokeview').html('<div class="loader"><p class="hide">Loading …</p></div>');
                }
            }, 500);
            $.ajax({
                type: "GET",
                url: Routing.generate('pokedex_pokemon_description', { number: number }),
                success: function(data){
                    loader = false;
                    $('.pokedex-action .pokedex-element').removeClass( "selected" );
                    pokemonElement.addClass( "selected" );
                    $('#pokeview').html(data);
                    window.history.pushState({path:pageurl},null,pageurl);
                },
                error: function(){
                    loader = false;
                    $('#pokeview').html(description);
                }
            });
        }
    });
});