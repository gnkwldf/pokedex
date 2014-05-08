$(document).ready(function(){
    $(".pokedex-action").click(function(event){
        event.preventDefault();
        var pageurl = $(this).attr('href');
        var number = $(this).attr("data-pokemon-number");
        $.ajax({
            type: "GET",
            url: Routing.generate('pokedex_pokemon_description', { number: number }),
            success: function(data){
                $('#pokeview').html(data);
                window.history.pushState({path:pageurl},null,pageurl);
            },
        });
    });
});