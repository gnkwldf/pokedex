<?php

/*
 * This file is part of the GNKWLDF package.
 *
 * (c) Anthony Rey <anthony.rey@mailoo.org>
 *
 * For the full copyright and license information, please view the LICENSE-GNKWLDF
 * file that was distributed with this source code.
 */

namespace GNKWLDF\PokedexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gnkw\Symfony\HttpFoundation\FormattedResponse;

/**
 * Default Pokedex Controller
 * @author Anthony Rey <anthony.rey@mailoo.org>
 * @since 02/05/2014
 */
class DefaultController extends Controller
{
    private $pokemonNumber = 719;

    /**
     * Standard action
     * @Route("/", name="pokedex_home")
     * @Route("/pokemon/{number}", name="pokedex_pokemon")
     * @Template()
     */
    public function indexAction($number = null)
    {
        $request = $this->getRequest();
        $request->setLocale($request->getPreferredLanguage(array(
            'fr',
            'en'
        )));
        return array(
            'pokemonNumber' => $this->pokemonNumber,
            'number' => $number,
            'drawn' => $this->pokemonDrawnList()
        );
    }
    
    /**
     * Description action
     * @Route("/pokemon/{number}/description", name="pokedex_pokemon_description", options={"expose"=true})
     * @Template()
     */
    public function descriptionAction($number)
    {
        $description = $this->getDescription($number);
        return array(
            'description' => $description,
            'drawn' => $this->pokemonDrawnNumber(),
            'total' => $this->pokemonNumber + 1 // MissingNo. is not a Pokémon but he's drawn
        );
    }

    /**
     * Standard action
     * @Route("/about", name="pokedex_about")
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }
    
    /**
     * Api Description action
     * @Route("/api/pokemon/{number}/description", name="pokedex_api_pokemon_description")
     * @Method("GET")
     */
    public function apiDescriptionAction($number)
    {
        $description = $this->getDescription($number);
        if(null === $description)
        {
            return new Response('Problem with number', 400);
        }
        return new FormattedResponse($description);
    }
    
    private function pokemonDrawnList()
    {
        $pokemonFileList = scandir(__DIR__ . '/../Resources/public/images/pokemon/normal/');
        $pokemonList = preg_filter('#(.+).jpg#' , '$1', $pokemonFileList);
        return $pokemonList;
    }
    
    private function pokemonDrawnNumber()
    {
        return count($this->pokemonDrawnList());
    }
    
    /**
     * @return The description or null (problem with number)
     */
    private function getDescription($number)
    {
        $request = $this->getRequest();
        $request->setLocale($request->getPreferredLanguage(array(
            'fr',
            'en'
        )));
        if(null === $number)
        {
            return null;
        }
        $number = intval($number);
        if(!is_int($number) OR $this->pokemonNumber < $number OR 0 > $number)
        {
            return null;
        }
        $response = array(
            'name' => $this->get('translator')->trans('pokemon.list.' . $number . '.name'),
            'image' => $this->container->get('templating.helper.assets')->getUrl('bundles/gnkwldfpokedex/images/pokemon/no-pokemon.jpg'),
            'description' => $this->get('translator')->trans('pokemon.default.no.description'),
            'species' => $this->get('translator')->trans('pokemon.default.no.species'),
            'types' => null
        );
        if(is_file(__DIR__ . '/../Resources/public/images/pokemon/normal/'.$number.'.jpg'))
        {
            $response['image'] = $this->container->get('templating.helper.assets')->getUrl('bundles/gnkwldfpokedex/images/pokemon/normal/'.$number.'.jpg');
        }
        if(is_file(__DIR__ . '/../properties/pokemon/list/'.$number.'.json'))
        {
            $propertiestext = file_get_contents(__DIR__ . '/../properties/pokemon/list/'.$number.'.json');
            $properties = json_decode($propertiestext, true);
            if(isset($properties['types']))
            {
                $response['types'] = array();
                foreach($properties['types'] AS $type)
                {
                    $response['types'][] = array(
                        'tag' => $type,
                        'name' => $this->get('translator')->trans('pokemon.type.' . $type)
                    );
                }
            }
            if(!empty($properties['species']))
            {
                $response['species'] = $this->get('translator')->trans('pokemon.list.' . $number . '.species');
            }
            if(!empty($properties['description']))
            {
                $response['description'] = $this->get('translator')->trans('pokemon.list.' . $number . '.description');
            }
        }
        return $response;
    }
}
