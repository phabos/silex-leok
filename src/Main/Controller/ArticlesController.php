<?php

namespace Main\Controller;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ArticlesController
{

    public function indexAction( Application $app )
    {
    	return $app['twig']->render( 'main.html', array() );
    }

    public function articlesListAction( Application $app, Request $request )
    {
        sleep(1);
        $offset = (int) $request->query->get('offset') * $app['sql.limit'];
        $articles = $app['repository.article']->getPublishedArticles( $app['sql.limit'], $offset );
        $pos = 0;
        foreach ($articles as $key => $value) {
            $date_creation = new \Datetime( $value['date_creation'] );
            $articles[$key]['date_creation'] = $date_creation->format( 'd-m-Y' );
            $articles[$key]['image'] = $value['image'];
            $articles[$key]['image_thumb'] = $this->getImageThumbUrl( $app['webroot.path'], $value['image'] );
            $articles[$key]['pos'] = $pos;
            $pos++;
        }
        return $app->json( $articles );
    }

    protected function getImageThumbUrl( $dir, $img ) {

        preg_match("/\/webroot\/gallery\/([0-9]+-[0-9]+)\/(.*)/", $img, $output_array);
        if( !empty( $output_array[2] ) && !empty( $output_array[1] ) )
            return $dir . $output_array[1] . '/R_500x500-' . $output_array[2];

        return '';
    }
}
