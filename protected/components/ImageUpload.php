<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('memory_limit', '-1');
require_once 'protected/extensions/EUploadedImage.php';
class ImageUpload  {
    
    public static function uploadImage($model,$imageColumn,$width,$height,$imageName,$type,$prefix=null){
            //$model->$imageColumn = EUploadedImage::getInstance($model,$imageColumn);
            $model->$imageColumn = EUploadedImage::getInstanceByName($imageColumn);
            $imageName = Yii::app()->params[$imageName].time().'_';
            $default = Yii::app()->params[$type];
            if(!empty($prefix))
                $default =$prefix."_".$default;
            $path = Yii::app()->params['uploadPath'];
            $extension = Yii::app()->params['extension'];
            
            $model->$imageColumn->thumb = array(
                                'maxWidth' => $width,
                                'maxHeight' => $height,
                                'dir' => 'thumb',
                                'prefix' => $imageName,
                                );
            
            $fullPath = Yii::app()->request->hostInfo.Yii::app()->baseUrl."/".$path."/thumb/";
            if ($model->$imageColumn->saveAs($path.'/'.$default.".".$extension))
                $imageName = $imageName.$default.".".$extension;
            else
                $imageName = NULL;
            
            return $fullPath.$imageName;
    }
}