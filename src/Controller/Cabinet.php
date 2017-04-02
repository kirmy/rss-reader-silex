<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

use App\Model\SourceMapper;
use App\Model\SourceEntity;
use Symfony\Component\Validator\Constraints as Assert;
// use Symfony\Component\Validator\Mapping\ClassMetadata;
// use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Cabinet
{
    public static function _before(Request $request, Application $app)
    {
        $logged = $request->getSession()->get('logged');
        if (! $logged) $app->abort(403, 'Forbidden.');
    }

    public function getIndex(Request $request, Application $app)
    {
        $mapper = new SourceMapper($app['db']);
        $sources = $mapper->getSources();
        $errors = $request->getSession()->getFlashBag()->get('errors', array());   
        $app['view.name'] = 'cabinet';
        return $app['view']->data(['sources' => $sources, 'errors' => $errors])->render();
    }

    public function postAddSource(Request $request, Application $app)
    {
        $data = $request->request->all();
               
        $constraints = new Assert\Collection(array(
            'name' => new Assert\Length(array('min' => 3)),
            'source_link' => new Assert\Url(),
            'rss_feed_link' => new Assert\Url(),
        ));
        
        $errors = $app['validator']->validate($data, $constraints);
        //var_dump($this->validateurl($data['rss_feed_link']));
        
        if (count($errors) > 0) {
           $ers = [];
            foreach ($errors as $error) {
                $ers[] = [$error->getInvalidValue(),$error->getMessage()];
            }
            $request->getSession()->getFlashBag()->add('errors', $ers);
            return $app->redirect('/cabinet');
        }
        $source = new SourceEntity($data);
        $mapper = new SourceMapper($app['db']);
        $mapper->save($source);

        return $app->redirect('/cabinet');
    }

    public function postDisableSource(Request $request, Application $app, $id)
    {
        $mapper = new SourceMapper($app['db']);
        $data = $mapper->getSourceById($id);
        $data['is_active'] = ! $data['is_active'];

        $source = new SourceEntity($data);
        $mapper->save($source);

        return $app->redirect('/cabinet');
    }
    
    // public function validateurl($object, ExecutionContextInterface $context, $payload)
    // {
    //     $ch = curl_init();
    //     // set url
    //     // установка URL и других необходимых параметров
    //     curl_setopt($ch, CURLOPT_URL, $object);
    //     curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //     curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     // $output contains the output string
    //     $output = curl_exec($ch);
    //     $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    //     curl_close($ch);
    //     return $code;
    //     // if ($code != 200) {
    //     // $context->buildViolation('This name sounds totally fake!')
    //     //     ->atPath('firstName')
    //     //     ->addViolation();
    //     // }
    // }

}
