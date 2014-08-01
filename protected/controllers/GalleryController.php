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
        );

        //mode = city -> city = ?
        //mode = type -> type =








        $popularEntries = array();
        $latestEntries = array();
        $searchEntries = array();
        $listType = isset($_GET['type']) ? $this->sanitizeData($_GET['type']) : 'basic';
        $mode = isset($_GET['mode']) ? $this->sanitizeData($_GET['mode']) : '';
        switch($listType){
            case 'basic':
                //get the list of all available content for popular & latest entries
                $popularEntries = Yii::app()->services->performRequest('/content',array('gallery'=>Yii::app()->params['ugcGalleryId'],'mode'=>'popular','type'=>'UGC','limit'=>'30'),'GET')->getResponseData(true);
                $latestEntries = Yii::app()->services->performRequest('/content',array('gallery'=>Yii::app()->params['ugcGalleryId'],'mode'=>'latest','type'=>'UGC','limit'=>'30'),'GET')->getResponseData(true);
                break;
            case 'list':
                if ($_GET['mode'] == 'popular'){
                    $popularEntries = Yii::app()->services->performRequest('/content',array('gallery'=>Yii::app()->params['ugcGalleryId'],'mode'=>'popular','type'=>'UGC','limit'=>-1),'GET')->getResponseData(true);
                } else if ($_GET['mode'] == 'latest'){
                    $latestEntries = Yii::app()->services->performRequest('/content',array('gallery'=>Yii::app()->params['ugcGalleryId'],'mode'=>'latest','type'=>'UGC','limit'=>-1),'GET')->getResponseData(true);
                }
                break;
            case 'search':
                $mode = "popular";
                $username = isset($_GET['username']) ? $this->sanitizeData($_GET['username']) : '';
                $content_type = isset($_GET['content_type']) ? $this->sanitizeData($_GET['content_type']) : array();

                $callParams = array('gallery'=>Yii::app()->params['ugcGalleryId'], 'limit'=>-1,'type'=>'UGC');
                if ($username){
                    $callParams['username'] = $username;
                }
                if ($content_type){
                    $callParams['content_type'] = $content_type;
                }
                $popularEntries = Yii::app()->services->performRequest('/content',$callParams,'GET')->getResponseData(true);
                break;
        }

        $this->page_name = 'gallery';

        $this->render($this->page_name, array(
            'page_name'=>$this->page_name,
            'nav' => $this->getNav(),
            'mode'=> $mode,
            'listType'=>$listType,
            'siteParams' => $this->getSiteParams(),
            'popularList' => $popularEntries,
            'latestList'  => $latestEntries,
            'widget' => array(
                'partners' => $this->getSitePartners(),
                'footer' => $this->getSiteFooter(),
            ),

        ));
        Yii::app()->end();
    }






}