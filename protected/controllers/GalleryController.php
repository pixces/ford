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

        $offset = 0;
        $limit = 9;
        $displayMore = true;

        //get the basic details
        $entries = $this->actionDoSearch(false, array('limit'=>$limit,'offset'=>$offset));
        if (!$entries){
            $displayMore = false;
        }

        $this->page_name = 'gallery';
        $this->render($this->page_name, array(
            'page_name' => $this->page_name,
            'nav'       => $this->getNav(),
            'siteParams'=> $this->getSiteParams(),
            'entries'   => json_encode($entries),
            'viewMore'  => $displayMore,
            'offset'    => $offset,
            'limit'     => $limit,
            'widget'    => array(
                'partners'  => $this->getSitePartners(),
                'footer'    => $this->getSiteFooter(),
            ),
        ));
        Yii::app()->end();
    }


    /**
     * Method to do api call with
     * all the required search params and return search result
     *
     * Can be called both via AJAX or normal
     * @param bool $isAjax
     *
     * Search attributes
     * @param int Offset
     * @param int limit
     *
     * --- optionals ----
     * @param string city
     * @param string type (text/audio)
     * @param string channel_name (celebrity token)
     *
     */
    public function actionDoSearch($isAjax = true,$options=null){

        //basic params common to all set
        $params = array();
        $baseParams =  array(
            'is_ugc' => 1,
            'gallery' => Yii::app()->params['ugcGalleryId'],
            'status' => 'approved'
        );

        if ($isAjax == true){
            $options = $_GET;
        }

        if (!is_null($options)){

            //check for offset
            if(isset($options['offset'])){
                $params['offset'] = $options['offset'];
            }

            //check for limit
            if(isset($options['limit'])){
                $params['limit'] = $options['limit'];
            }

            //check for city
            if(isset($options['city'])){
                $params['city'] = $options['city'];
            }

            //check for type
            if(isset($options['type'])){
                $params['type'] = $options['type'];
            }

            //check for channel
            if(isset($options['channel'])){
                $params['channel'] = $options['channel'];
            }

        }
        $params = array_merge($params,$baseParams);

        $entries = Yii::app()->services->performRequest('/content',$params,'GET')->getResponseData(true);

        if ($isAjax == true){
            echo CJavaScript::jsonEncode( array('response'=>'success', 'content'=>$entries ));
        } else {
            return $entries;
        }
    }
}