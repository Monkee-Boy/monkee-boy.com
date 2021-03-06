<?php
class portfolio extends appController {
  function __construct() {
    // Load model when creating appController
    parent::__construct("portfolio");

    $this->tplAssign('aContent', $this->model->content);
    $this->tplAssign('aServiceContent', $this->model->service_content);
  }

  function index() {
    $aPortfolio = $this->model->getClients(false, false, true);

    foreach($aPortfolio as &$aItem) {
      usort($aItem['services'], function($a, $b) { return $a['order'] - $b['order']; });
    }

    $this->tplAssign('aPortfolio', $aPortfolio);
    $this->tplDisplay('index.php');
  }

  function single() {
    $aClient = $this->model->getClient(null, false, true, $this->urlVars->dynamic['tag']);

    if(empty($aClient))
      $this->error('404');

    usort($aClient['services'], function($a, $b) { return $a['order'] - $b['order']; });

    $this->tplAssign('aClient', $aClient);
    $this->tplDisplay('single.php');
  }

  function services_index() {
    $aServices = $this->model->getServices();
    $aContent = $this->model->service_content;

    $this->tplAssign("aServices", $aServices);
    $this->tplAssign("aContent", $aContent);
    $this->tplDisplay("services/index.php");
  }

  function services_single() {
    $aService = $this->model->getService(null, $this->urlVars->dynamic["tag"], true);
    $aContent = getContent(null, $this->urlVars->dynamic["tag"]);

    if(!empty($aContent)) {
      $aService['seo_title'] = $aContent['seo_title'];
      $aService['seo_description'] = $aContent['seo_title'];
      $aService['seo_keywords'] = $aContent['seo_title'];
    }

    if(empty($aService))
      $this->error('404');

    $this->tplAssign("aService", $aService);
    $this->tplAssign("aContent", $aContent);
    $this->tplDisplay("services/single.php");
  }
}
