<?php

class PagesController extends Controller {

    public $is_submission = 0;

    public function actionDisplay() {
        //default value and id's
        $galleryId = 0;
        $pageId = null;
        $widgetList = array();
        $siteParams = $this->getSiteParams();

        //get the phase details
        $phase = $this->getPhase();
        $submission = 0;

        if ($phase) {
            //parse this to create the prepare the link list
            $nav = $this->getNav();     //the navigation array from beforeAction
            $pageId = 0;                //the pageId to be used to get page details

            //get the default page
            foreach($nav as $label => $item){
                if ($item['is_active']){
                    $pageId = $item['id'];
                    if (!isset($this->page_name)){
                        $this->page_name = $item['page_name'];
                    }
                }
            }

            //check if submission is active for the said page
            foreach($phase as $phaseDet){
                if ( $pageId == $phaseDet['page_id']){
                    $this->is_submission = $phaseDet['submission'];
                }
            }

            //if no pageId is resolved or no page is set as active page
            if (!$pageId){
                //redirect the page to set the default page
                $this->redirect(
                    $this->createAbsoluteUrl('pages/display')
                );
            }

            $pageUrl = "/page/" . $pageId;

            $pageObj = $this->getPageDetails($pageUrl);

            //get the gallery Id
            if ($pageObj['gallery']) {
                $galleryId = $pageObj['gallery'][0]['gallery_id'];
            }

            if ($pageObj['widget']){
                //get the widgets involved
                $widgetTypes = $this->getWidgetTypes();

                //get all the basic details
                $widgetList = array();
                foreach ($widgetTypes as $widgetType) {
                    $widgetList[$widgetType['name']] = json_encode(array());
                }

                if ($pageObj['widget']) {
                    foreach ($pageObj['widget'] as $widget) {
                        $callUrl = "/widget/" . $widget['widget_id'];
                        $widgetDetails = $this->getWidget($callUrl);
                        if ($widgetDetails) {
                            $widgetList[$widgetDetails['label']] = json_encode($widgetDetails['data']);
                        }
                    }
                }
            }

            //echo $this->page_name;

            $this->render($this->page_name, array(
                'page_name' => $this->page_name,
                'is_submission'=>$this->is_submission,
                'nav' => $nav,
                'widget' => $widgetList,
                'galleryId' => $galleryId,
                'siteParams' => $this->getSiteParams(),
            ));
            Yii::app()->end();
        } else {
            echo "No phase data is found";
        }
    }

    public function actionIndex() {
        $this->redirect(
            $this->createAbsoluteUrl('pages/display')
        );
    }

    public function actionRedirect() {
        $this->page_name = 'redirect';
        $this->render($this->page_name);
    }

}
