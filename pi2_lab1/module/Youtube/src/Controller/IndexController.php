<?php

namespace Youtube\Controller;

use Youtube\Service\Youtube;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @var Youtube
     */
    private $ytService;

    /**
     * IndexController constructor.
     * @param Youtube $ytService
     */
    public function __construct(Youtube $ytService)
    {
        $this->ytService = $ytService;
    }

    public function indexAction()
    {
        $pageToken = $this->params()->fromQuery('page-token');
        $films = $this->ytService->getMostPopular($pageToken);

        return ['films' => $films, 'action' => 'index', 'title' => 'najpopularniejsze filmy'];
    }

    public function commentsAction()
    {
        $id = $this->params()->fromRoute('id');
        $pageToken = $this->params()->fromQuery('page-token');
        $comments = $this->ytService->getComments($pageToken, $id);

        $view = new ViewModel(['comments' => $comments, 'id' => $id]);
        $view->setTerminal(true);

        return $view;
    }

    public function searchAction()
    {
        $phrase = $this->params()->fromQuery('phrase');
        $pageToken = $this->params()->fromQuery('page-token');
        $view = new ViewModel(['phrase' => $phrase]);

        if (!empty($phrase)) {
            $films = $this->ytService->search($phrase, $pageToken);

            $listView = new ViewModel(['films' => $films, 'phrase' => $phrase, 'title' => 'wyszukano: ' . $phrase, 'action' => 'search']);
            $listView->setTemplate('youtube/index/index');

            $view->addChild($listView, 'list');
        }

        return $view;
    }
}
