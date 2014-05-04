$(document).ready(function(){
    $(".pokedex-action").click(function(){
        var number = $(this).attr("data-pokemon-number");
        $.ajax({
            type: "GET",
            url: Routing.generate('pokedex_api_pokemon_description', { number: number }),
            success: function(data){
                var view = $(".pokedex-view");
                view.html('');
                view.append('<div class="pokedex-img"><img height="150px" width="150px" /></div>');
                $(".pokedex-view .pokedex-img img").prop("src",data.image);
                $(".pokedex-view .pokedex-img img").prop("alt",data.name);
                $(".pokedex-view .pokedex-img img").prop("title",data.name);
                view.append('<ul class="pokedex-info"></ul>');
                view.append('<p class="pokedex-description"></p>');
                $(".pokedex-description").text(data.description);
                var ulinfo = $(".pokedex-view .pokedex-info");
                ulinfo.append('<li class="pokedex-pokemon-name"><strong></strong></li>');
                ulinfo.append('<li class="pokedex-pokemon-species"></li>');
                $(".pokedex-view .pokedex-info .pokedex-pokemon-name strong").text(data.name);
                $(".pokedex-view .pokedex-info .pokedex-pokemon-species").text(data.species);
                if(data.types !== null)
                {
                    ulinfo.append('<li class="pokedex-pokemon-types"></li>');
                    for(var key in data.types)
                    {
                        $(".pokedex-view .pokedex-info .pokedex-pokemon-types").append('<span class="label pokelabel-'+data.types[key].tag+'"></span> ');
                        $('.pokelabel-'+data.types[key].tag).text(data.types[key].name);
                    }
                }
            },
        });
    });
});