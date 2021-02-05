<?php

namespace Flickr\Controller;

use Flickr\Model\Thumbnails;
use Flickr\Form\SearchForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FlickrController extends AbstractActionController
{
    /**
     * @var Thumbnails
     */
    private $thumbnails;

    /**
     * @var SearchForm
     */
    private $searchForm;

    public function __construct(Thumbnails $thumbnails, SearchForm $searchForm)
    {
        $this->thumbnails = $thumbnails;
        $this->searchForm = $searchForm;
    }

    public function indexAction()
    {
        // return new ViewModel([
        //     'photos' => $this->thumbnails->search(),
        // ]);
        $page = $this->params()->fromRoute('id');
        $phrase = $this->params()->fromQuery('phrase');
        $this->searchForm->get('search')->setValue('Szukaj');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->searchForm->setData($request->getPost());

            if ($this->searchForm->isValid()) {
                return new ViewModel([
                    'photos' => $this->thumbnails->search($request->getPost()->phrase),
                    'form' => $this->searchForm,
                ]);
            }
        } else {
            if (!$phrase) {
                $phrase = 'book';
            } else {
                $this->searchForm->setData(['phrase' => $phrase]);
            }
        }

        return new ViewModel([
            'photos' => $this->thumbnails->search($phrase, $page),
            'form' => $this->searchForm,
        ]);
    }

    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        $details = $this->thumbnails->getDetails($id);

        $view = new ViewModel(['details' => $details, 'id' => $id]);
        $view->setTerminal(true);

        return $view;
    }

    public function userAction()
    {
        $id = urldecode($this->params()->fromRoute('id'));
        $page = urldecode($this->params()->fromRoute('page'));
        $userPhotos = $this->thumbnails->getUser($id, $page);

        $view = new ViewModel(['user' => $userPhotos, 'id' => $id]);
        // $view->setTerminal(true);

        return $view;
    }
}
