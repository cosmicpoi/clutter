<?php

class Clutter extends AbstractPicoPlugin
{
    protected $enabled = true;

    

    /**
     * Triggered before Pico renders the page
     *
     * @see    Pico::getTwig()
     * @see    DummyPlugin::onPageRendered()
     * @param  Twig_Environment &$twig          twig template engine
     * @param  array            &$twigVariables template variables
     * @param  string           &$templateName  file name of the template
     * @return void
     */
    public function root($string) {
        preg_match('/^([^\/]+\/)+/', $string, $matches);
        return $matches[0];
    }
    public function directoryChain($string) {
        $baseurl = Pico::getBaseUrl();
        $pieces = explode('/','/' . $string);

        $retstr = '';
        $aggregate = '';

        $arr2s = '';

        for($i = 1; $i<count($pieces)-1; $i++) {
            $arr2s = $arr2s.','.$pieces[i];
            $aggregate = $aggregate . $pieces[$i] . '/';
            // return $aggregate;
            $anchor = '<a href="'.$baseurl.'?'.$aggregate.'">'.$pieces[$i].'</a>';
            $retstr = $retstr .$anchor.'/';
        }


        return $retstr;
    }
    public function isIndex($string) {
        $pieces = explode('/',$string);
        return ($pieces[count($pieces)-1]=='index');
        
    }
    public function level($string) {
        $pieces = explode('/','/' . $string);

        if ($pieces[count($pieces)-1] == 'index') {
            return count($pieces)-1;
        }
        return count($pieces);
    }
    public function onPageRendering(Twig_Environment &$twig, array &$twigVariables, &$templateName)
    {

        $twig->addFilter(new Twig_SimpleFilter('directoryChain', array($this, 'directoryChain')));
        $twig->addFilter(new Twig_SimpleFilter('root', array($this, 'root')));
        $twig->addFilter(new Twig_SimpleFilter('level', array($this, 'level')));
        $twig->addFilter(new Twig_SimpleFilter('isIndex', array($this, 'isIndex')));
    }

}
