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
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $request->setLocale($request->getPreferredLanguage(array(
            'fr',
            'en'
        )));
        return array('number' => $this->pokemonNumber);
    }
    
    /**
     * Standard action
     * @Route("/api/pokemon/description/{number}", name="pokedex_api_pokemon_description", options={"expose"=true})
     * @Method("GET")
     */
    public function descriptionAction($number)
    {
        $number = intval($number);
        $request = $this->getRequest();
        $request->setLocale($request->getPreferredLanguage(array(
            'fr',
            'en'
        )));
        if(!is_int($number) OR $this->pokemonNumber < $number OR 0 > $number)
        {
            return new Response('Problem with number', 400);
        }
        $response = array(
            'name' => $this->get('translator')->trans('pokemon.list.' . $number . '.name'),
            'image' => $this->container->get('templating.helper.assets')->getUrl('bundles/gnkwldfpokedex/images/pokemon/normal/'.$number.'.jpg'),
            'description' => null
        );
        return new FormattedResponse($response);
    }
}
