<?php
class ControllerModuleImgViewFeaturedImageZoomer extends Controller {
    protected function index($setting) {
        static $module = 0;

        $cssfile = 'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/featuredimagezoomer.css';

        $this->document->addScript('catalog/view/javascript/jquery/featuredimagezoomer/featuredimagezoomer.js');

        if (!file_exists($cssfile)) {
            $cssfile = 'catalog/view/theme/default/stylesheet/featuredimagezoomer.css';
        }
        $this->document->addStyle($cssfile);

        $this->data['module'] = $module++;

        $this->template = $this->config->get('config_template') . '/template/module/imgview_featuredimagezoomer.tpl';
        if (!file_exists(DIR_TEMPLATE . $this->template)) {
            $this->template = 'default/template/module/imgview_featuredimagezoomer.tpl';
        }

        $this->render();
    }
}
?>
