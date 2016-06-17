<?php

namespace Main\Controller;
use Silex\Application;
use Main\Events\UploadEvent;
use Symfony\Component\HttpFoundation\Request;

class ArticlesController
{

    public function indexAction( Application $app )
    {
        $opts = $app['repository.options']->getSettings( 'settings' );
        $options = @json_decode($opts['value']);
    	return $app['twig']->render( 'main.html', array( 'options' => $options ) );
    }

    public function articlesListAction( Application $app, Request $request )
    {
        sleep(1);

        // Single article ?
        if( ! empty( $request->query->get('id') ) ) {
            $article = $app['repository.article']->read( (int) $request->query->get('id') );

            if( !empty( $article ) ) {
                $date_creation = new \Datetime( $article['date_creation'] );
                $article['date_creation'] = $date_creation->format( 'd-m-Y' );
                $article['image_thumb'] = $this->getImageThumbUrl( $app['webroot.path'], $article['image'] );
                $article['gallery'] = @json_decode( $article['gallery'] );
            }

            return $app->json( $article );
        }

        // Multiples articles
        $offset = (int) $request->query->get('offset') * $app['sql.limit'];
        $articles = $app['repository.article']->getPublishedArticles( $app['sql.limit'], $offset );
        $pos = 0;
        foreach ($articles as $key => $value) {
            $date_creation = new \Datetime( $value['date_creation'] );
            $articles[$key]['date_creation'] = $date_creation->format( 'd-m-Y' );
            $articles[$key]['image'] = $value['image'];
            $articles[$key]['image_thumb'] = $this->getImageThumbUrl( $app['webroot.path'], $value['image'] );
            $articles[$key]['pos'] = $pos;
            $articles[$key]['gallery'] = @json_decode( $articles[$key]['gallery'] );
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
