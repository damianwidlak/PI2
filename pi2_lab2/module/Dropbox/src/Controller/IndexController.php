<?php

namespace Dropbox\Controller;

use Dropbox\Service\Dropbox;
use Laminas\Http\Headers;
use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    private Dropbox $dropbox;
	
	public function __construct(Dropbox $dropbox)
	{
		$this->dropbox = $dropbox;
	}
	
    public function indexAction()
    {
		if (!$this->dropbox->authorized()) {
			return $this->redirect()->toRoute('dropbox/akcje', ['action' => 'authorize']);
        }

		$path = $this->params()->fromQuery('path', '');

		$files = $this->dropbox->getFileList($path);

        return is_array($files) ? ['path' => $path, 'files' => $files] : ['msg' => $files];
    }

	public function authorizeAction()
	{
		return ['authorize_url' => $this->dropbox->generateAuthorizationUrl()];
	}

	public function finishAction()
    {
        $code = $this->params()->fromQuery('code');
        $result = $this->dropbox->getAccessToken($code);

        if($result === true) {
            return $this->redirect()->toRoute('dropbox');
        }

        return ['msg' => $result];
    }

    public function usunAction()
    {
        if (!$this->dropbox->authorized()) {
            return $this->redirect()->toRoute('dropbox/akcje', ['action' => 'authorize']);
        }

        $path = $this->params()->fromQuery('path', '');
        if (strlen($path) > 1) {
            $files = $this->dropbox->deleteFile($path);
            return $this->redirect()->toRoute('dropbox', ['action' => 'index'], ['query' => ['path' => implode('/', array_slice(explode('/', $path), 0, -1))]]);

        } else
            return ['msg' => 'Nie można usunąć pliku: '. $path ];

    }

    public function pobierzAction()
    {
        if (!$this->dropbox->authorized()) {
            return $this->redirect()->toRoute('dropbox/akcje', ['action' => 'authorize']);
        }

        $path = $this->params()->fromQuery('path', '');
        $file = $this->dropbox->downloadFile($path);
        $resp = $this->getResponse();
        $resp->setStatusCode(200);
        $resp->setHeaders(Headers::fromString('Content-Disposition: attachment; filename="'.substr($path, 1).'"'));
        $resp->setContent($file->getContent());
        return $resp;
    }

    public function dodajAction()
    {
        if (!$this->dropbox->authorized()) {
            return $this->redirect()->toRoute('dropbox/akcje', ['action' => 'authorize']);
        }
        $request = $this->getRequest();
        $path = $this->params()->fromQuery('path', '');

        if ($request->isPost()) {
            $dane = $request->getPost();

            $filepath = $path . '/' . $dane['nazwaPliku'];

            $result = $this->dropbox->uploadFile($filepath, $dane['zawartoscPliku']);
            $this->getResponse()->setContent('ok');
        }
        return $this->getResponse();
    }

    public function dodajKatalogAction()
    {
        if (!$this->dropbox->authorized()) {
            return $this->redirect()->toRoute('dropbox/akcje', ['action' => 'authorize']);
        }
        $request = $this->getRequest();
        $path = $this->params()->fromQuery('path', '');

        if ($request->isPost()) {
            $dane = $request->getPost();

            $dirpath = $path . '/' . $dane['nazwaKatalogu'];

            $result = $this->dropbox->createFolder($dirpath);
            $this->getResponse()->setContent('ok');
        }
        return $this->getResponse();
    }

}

