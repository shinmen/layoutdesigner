<?php 
namespace TemplateDesigner\LayoutBundle\Service;

use Symfony\Component\Finder\Finder;

 
class TemplateFinder {

    private $rootDir;

    public function __construct($rootDir){
        $this->rootDir = $rootDir;
    }

    public function getFormattedTemplateForForm(){
        $finder = new Finder();
        $finder->files()->in($this->rootDir."/../src/")->name('*.html.*');
        $templates = array();
        
        foreach ($finder as $file) {
            preg_match('/^[a-zA-Z\/]+Resources\/views/', $file->getRelativePathname(),$first_matches);
            $rawFirstPart = preg_replace('/Resources\/views/', '', $first_matches[0]);
            $valueFirstPart = preg_replace('/Bundle/', '', $rawFirstPart);
            $keyFirstPart = preg_replace('/\//', '', $rawFirstPart);
            if(substr_count($rawFirstPart, '/')< 2 ){$keyFirstPart.=':';}
            $keyFirstPart.=':';

            preg_match('/Resources\/views\/[a-zA-Z\/\.]+$/', $file->getRelativePathname(),$second_matches);
            $rawSecondPart = preg_replace('/Resources\/views\//', '', $second_matches[0]);
            $valueSecondPart = preg_replace('/.html[\.\w]*$/', '', $rawSecondPart);
            $keySecondPart = preg_replace('/\//', ':', $rawSecondPart);

            $templates[$keyFirstPart.$keySecondPart] = $valueFirstPart.$valueSecondPart;
        }
        return $templates;
    }


}