<?php
/**
 * Created by IntelliJ IDEA.
 * User: zainulabdeen
 * Date: 31/03/14
 * Time: 3:42 AM
 * To change this template use File | Settings | File Templates.
 */
class GalleryController extends Controller
{

    /**
     * Displaying Gallery and its related search
     * Search Type: Display by
     *  - All
     *  - City (Select City Name)
     *  - Type (All / Text / Voice)
     *  - By Channel (Rocky / Gaurav / Anushka)
     */
    public function actionIndex(){

        //basic params
        $params = array(
            'is_ugc' => 1,
            'gallery' => Yii::app()->params['ugcGalleryId'],
            'limit' => 30,
            'status' => 'approved'
        );

        //get the basic details
        $entries = Yii::app()->services->performRequest('/content',$params,'GET')->getResponseData(true);

        $this->page_name = 'gallery';
        $this->render($this->page_name, array(
            'page_name'=>$this->page_name,
            'nav' => $this->getNav(),
            'siteParams' => $this->getSiteParams(),
            'entries' => json_encode($entries),
            'widget' => array(
                'partners' => $this->getSitePartners(),
                'footer' => $this->getSiteFooter(),
            ),

        ));
        Yii::app()->end();
    }
}