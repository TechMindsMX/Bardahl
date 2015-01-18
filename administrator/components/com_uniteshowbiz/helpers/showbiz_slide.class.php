<?php

/**
 * @package Unite Showbiz for Joomla 1.7-3.1
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die;

class BizSlide extends UniteElementsBaseBiz {

    const TYPE_GALLERY = "gallery";
    const TYPE_POST = "post";

    private $slideType;
    private $id;
    private $sliderID;
    private $slideOrder = 0;
    private $thumbID;
    private $imageUrl;
    private $imageFilepath;
    private $imageFilename;
    private $params;
    private $slider;

    public function __construct() {
        parent::__construct();
    }

    /**
     * 
     * init image parameters from url
     */
    private function initImageParams($urlImage) {

        if (is_numeric($urlImage)) {
            $this->thumbID = $urlImage;
            $this->imageUrl = UniteFunctionsWPBiz::getUrlAttachmentImage($this->thumbID);
        } else {
            $this->imageUrl = $urlImage;
        }

        //set image path, file and url			
        if (!empty($this->imageUrl)) {
            $this->imageFilepath = UniteFunctionsWPBiz::getImagePathFromURL($this->imageUrl);
            $realPath = UniteFunctionsWPBiz::getPathUploads() . $this->imageFilepath;
            if (file_exists($realPath) == false || is_file($realPath) == false)
                $this->imageFilepath = "";
            $this->imageFilename = basename($this->imageUrl);
        }
    }

    /**
     * 
     * get slider param
     */
    private function getSliderParam($sliderID, $name, $default, $validate = null) {

        if (empty($this->slider)) {
            $this->slider = new ShowBizSlider();
            $this->slider->initByID($sliderID);
        }

        $param = $this->slider->getParam($name, $default, $validate);

        return($param);
    }

    /**
     * 
     * init slide by db record
     */
    public function initByData($record) {
				
        $this->slideType = self::TYPE_GALLERY;
        $this->id = $record["id"];
        $this->sliderID = $record["slider_id"];
        $this->slideOrder = $record["slide_order"];

        $params = $record["params"];

        $params = (array) json_decode($params);
        
        //make the excerpt
        $text = UniteFunctionsBiz::getVal($params, "slide_text");
        $introText = UniteFunctionsBiz::getVal($params, "intro_text");
        $introText = trim($introText);
        
        $intro = $introText;
        
        //get intro from text
        if(empty($intro)){
        	
            $slider = new ShowBizSlider();
            $slider->initByID($this->sliderID);
        	
        	//get limit
            $customLimit = UniteFunctionsBiz::getVal($params, "showbiz_excerpt_limit");
            $customLimit = (int) $customLimit;
            if (!empty($customLimit))
                $excerpt_limit = $customLimit;
            else {
                $excerpt_limit = $slider->getParam("excerpt_limit", 55, ShowBizSlider::VALIDATE_NUMERIC);
                $excerpt_limit = (int) $excerpt_limit;
            }
            
            //set text
            $textForExcerpt = $text;
            
            //strip tags if needed
			$stripTags = $slider->getParam("strip_tags", "on");
			if($stripTags == "on"){								
				$strExclude =  $slider->getParam("strip_tags_exclude", "<b><strong><br><br/><i><small>");
            	$textForExcerpt = strip_tags($text, $strExclude);
			}
			
			//cut the text for intro
            $intro = UniteFunctionsBiz::getTextIntro($textForExcerpt, $excerpt_limit);
        }
        
        //dmp($excerpt_limit);exit();
        
        $params["excerpt"] = $intro;

        $urlImage = UniteFunctionsBiz::getVal($params, "slide_image");
//		    $this->initImageParams($urlImage);

        $this->params = $params;

        //dmp($this->params);exit();
    }

    /**
     * 
     * init slide as a demo
     */
    public function initDemoData($demoType) {

        $operations = new BizOperations();
        $params = $operations->getSlideDemoData($demoType);

        $this->params = $params;

        $this->imageUrl = $params["slide_image"];
        $this->slideType = "gallery";
        $this->id = 999;
    }

    /**
     * 
     * init the slider by id
     */
    public function initByID($slideid) {
        UniteFunctionsBiz::validateNumeric($slideid, "Slide ID");
        $slideid = $this->db->escape($slideid);
        $record = $this->db->fetchSingle(GlobalsShowBiz::$table_slides, "id=$slideid");

        $this->initByData($record);
    }

    /**
     * 
     * init by post id
     */
    public function initByPostID($postID, $sliderID, $slider = null) {

        $arrPostData = UniteFunctionsWPBiz::getPost($postID);

        $this->initByPostData($arrPostData, $sliderID, $slider);
    }


    /**
     * 
     * get slide ID
     */
    public function getID() {
        return($this->id);
    }

    /**
     * 
     * get slide order
     */
    public function getOrder() {
        $this->validateInited();
        return($this->slideOrder);
    }

    /**
     * 
     * get title
     */
    public function getValue($name) {
        $this->validateInited();
        $value = UniteFunctionsBiz::getVal($this->params, $name);
        return($value);
    }

    /**
     * 
     * get params for export
     */
    public function getParamsForExport() {
        $arrParams = $this->getParams();
        $urlImage = UniteFunctionsBiz::getVal($arrParams, "slide_image");
        if (!empty($urlImage))
            $arrParams["slide_image"] = UniteFunctionsWPBiz::getImagePathFromURL($urlImage);

        return($arrParams);
    }

    /**
     * 
     * get slide params
     */
    public function getParams() {
        $this->validateInited();
        return($this->params);
    }

    /**
     * 
     * get parameter from params array. if no default, then the param is a must!
     */
    function getParam($name, $default = null) {

        if ($default === null) {
            if (!array_key_exists($name, $this->params))
                UniteFunctionsBiz::throwError("The param <b>$name</b> not found in slide params.");
            $default = "";
        }

        return UniteFunctionsBiz::getVal($this->params, $name, $default);
    }

    /**
     * 
     * get image filename
     */
    public function getImageFilename() {
        return($this->imageFilename);
    }

    /**
     * 
     * get image filepath
     */
    public function getImageFilepath() {
        return($this->imageFilepath);
    }

    /**
     * 
     * get image url
     */
    public function getImageUrl() {
        return($this->imageUrl);
    }

    /**
     * 
     * get thumb url
     */
    public function getUrlImageThumb() {

        //get image url by thumb
        if (!empty($this->thumbID)) {
            $urlImage = UniteFunctionsWPBiz::getUrlAttachmentImage($this->thumbID, UniteFunctionsWPBiz::THUMB_MEDIUM);
        } else {
            //get from cache
            if (!empty($this->imageFilepath)) {
                $urlImage = UniteBaseClassBiz::getImageUrl($this->imageFilepath, 200, 100, true);
            }
            else
                $urlImage = $this->imageUrl;
        }

        if (empty($urlImage))
            $urlImage = $this->imageUrl;

        return($urlImage);
    }

    /**
     * 
     * get the slider id
     */
    public function getSliderID() {
        return($this->sliderID);
    }

    /**
     * 
     * validate that the slider exists
     */
    private function validateSliderExists($sliderID) {
        $slider = new ShowBizSlider();
        $slider->initByID($sliderID);
    }

    /**
     * 
     * validate that the slide is inited and the id exists.
     */
    private function validateInited() {
        if (empty($this->id))
            UniteFunctionsBiz::throwError("The slide is not inited!!!");
    }

    /**
     * 
     * create the slide (from image)
     */
    public function createSlide($sliderID, $urlImage = "") {
        //get max order
        $slider = new ShowBizSlider();
        $slider->initByID($sliderID);
        $maxOrder = $slider->getMaxOrder();
        $order = $maxOrder + 1;

        $jsonParams = "";
        if (!empty($urlImage)) {
            $params = array();
            $params["slide_image"] = $urlImage;
            $jsonParams = json_encode($params);
        }

        $arrInsert = array("params" => $jsonParams,
            "slider_id" => $sliderID,
            "slide_order" => $order
        );

        $slideID = $this->db->insert(GlobalsShowBiz::$table_slides, $arrInsert);

        return($slideID);
    }

    /**
     * 
     * update slide image from data
     */
    public function updateSlideImageFromData($data) {

        $urlImage = UniteFunctionsBiz::getVal($data, "slide_image");

        $slideID = UniteFunctionsBiz::getVal($data, "slide_id");
        $sliderID = UniteFunctionsBiz::getVal($data, "slider_id");
        $slider = new ShowBizSlider();
        $slider->initByID($sliderID);
        $this->initByID($slideID);
        $arrUpdate = array();
        $arrUpdate["slide_image"] = $urlImage;
        $this->updateParamsInDB($arrUpdate);
        return($urlImage);
    }

    /**
     * 
     * update slide parameters in db
     */
    private function updateParamsInDB($arrUpdate) {

        $this->params = array_merge($this->params, $arrUpdate);
        $jsonParams = json_encode($this->params);

        $arrDBUpdate = array("params" => $jsonParams);

        $this->db->update(GlobalsShowBiz::$table_slides, $arrDBUpdate, array("id" => $this->id));
    }

    /**
     * 
     * update slide from data
     * @param $data
     */
    public function updateSlideFromData($data) {

        $slideID = UniteFunctionsBiz::getVal($data, "slideid");
        $this->initByID($slideID);

        //treat params
        $params = UniteFunctionsBiz::getVal($data, "params");
        $params = $this->normalizeParams($params);

        $arrUpdate = array();
        $arrUpdate["params"] = json_encode($params);

        $this->db->update(GlobalsShowBiz::$table_slides, $arrUpdate, array("id" => $this->id));
    }

    /**
     * 
     * delete slide from data
     */
    public function deleteSlideFromData($data) {
        $sliderID = UniteFunctionsBiz::getVal($data, "sliderID");

        $slider = new ShowBizSlider();
        $slider->initByID($sliderID);

        $slideID = UniteFunctionsBiz::getVal($data, "slideID");
        $this->initByID($slideID);
        $this->db->delete(GlobalsShowBiz::$table_slides, "id='$slideID'");
    }

    /**
     * 
     * normalize params
     */
    private function normalizeParams($params) {

        if (isset($params["slide_text"])) {
            $params["slide_text"] = UniteFunctionsBiz::normalizeTextareaContent($params["slide_text"]);
        }

        return($params);
    }

    /**
     * 
     * set params from client
     */
    public function setParams($params) {
        $params = $this->normalizeParams($params);
        $this->params = $params;
    }

    /**
      /* toggle slide state from data
     */
    public function toggleSlideStatFromData($data) {

        $sliderID = UniteFunctionsBiz::getVal($data, "slider_id");
        $slideID = UniteFunctionsBiz::getVal($data, "slide_id");

        //init slider
        $slider = new ShowBizSlider();
        $slider->initByID($sliderID);

        if ($slider->isSourceFromPosts()) {
            $this->initByPostID($slideID, $sliderID);
            $state = $this->getParam("state", "published");
            $newState = ($state == "published") ? "unpublished" : "published";

            $wpStatus = ($newState == "published") ? UniteFunctionsWPBiz::STATE_PUBLISHED : UniteFunctionsWPBiz::STATE_DRAFT;

            //update the state in wp
            UniteFunctionsWPBiz::updatePostState($slideID, $wpStatus);
        } else {
            $this->initByID($slideID);

            $state = $this->getParam("state", "published");

            $newState = ($state == "published") ? "unpublished" : "published";
            $arrUpdate = array();
            $arrUpdate["state"] = $newState;

            $this->updateParamsInDB($arrUpdate);
        }

        $this->params["state"] = $newState;

        return($newState);
    }

    /**
     * 
     * replace template placeholder
     */
    private function replacePlaceholder($holderName, $text, $html, $addPrefix = true) {
        $prefix = "showbiz_";
        if ($addPrefix == true)
            $name = $prefix . $holderName;
        else
            $name = $holderName;

        $html = str_replace("[$name]", $text, $html);
        return($html);
    }

    /**
     * 
     * process template html
     * get item html and process it by the template
     */
    public function processTemplateHtml($html) {

        $title = $this->getValue("title");
        $alias = $this->getValue("alias");
        $urlImage = JURI::root().$this->getValue("slide_image"); //$this->imageUrl;
        $text = $this->getValue("slide_text");

        $link = $this->getValue("link");
        if (empty($link))
            $link = "#";

        $date = $this->getValue("date");

        $dateModified = $this->getValue("date_modified");
        $excerpt = $this->getValue("excerpt");

        $youtubeID = $this->getValue("youtube_id");
        $vimeoID = $this->getValue("vimeo_id");
        $authorName = $this->getValue("author_name");
        $numComments = $this->getValue("num_comments");
        $catList = $this->getValue("catlist");
        $tagList = $this->getValue("taglist");
        $postID = $this->id;

        //replace the items in the html
        $html = $this->replacePlaceholder("title", $title, $html);
        $html = $this->replacePlaceholder("id", $postID, $html);
        $html = $this->replacePlaceholder("alias", $alias, $html);
        $html = $this->replacePlaceholder("name", $alias, $html);
        $html = $this->replacePlaceholder("image", $urlImage, $html);
        $html = $this->replacePlaceholder("content", $text, $html);
        $html = $this->replacePlaceholder("link", $link, $html);
        $html = $this->replacePlaceholder("date", $date, $html);
        $html = $this->replacePlaceholder("modified_date", $dateModified, $html);
        $html = $this->replacePlaceholder("excerpt", $excerpt, $html);
        $html = $this->replacePlaceholder("youtube_id", $youtubeID, $html);
        $html = $this->replacePlaceholder("vimeo_id", $vimeoID, $html);
        $html = $this->replacePlaceholder("author", $authorName, $html);
        $html = $this->replacePlaceholder("numcomments", $numComments, $html);
        $html = $this->replacePlaceholder("catlist", $catList, $html);
        $html = $this->replacePlaceholder("taglist", $tagList, $html);

        //replace custom options:
        $wildcards = new ShowBizWildcards();
        $arrCustomOptionsNames = $wildcards->getWildcardsSettingNames();

        foreach ($arrCustomOptionsNames as $name => $title) {
            $html = $this->replacePlaceholder($name, $this->getValue($name), $html, false);
        }

        return($html);
    }

}

?>