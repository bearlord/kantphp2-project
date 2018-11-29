<?php

/**
 * @package KantPHP
 * @author  Zhenqiang Zhang <zhenqiang.zhang@hotmail.com>
 * @copyright (c) KantPHP Studio, All rights reserved.
 * @license http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 */
namespace Kant\Tinymce;

use Kant\Kant;
use Kant\View\View;
use Kant\Widget\InputWidget;
use Kant\Tinymce\TinymceAsset;
use Kant\Helper\Html;
use Kant\Helper\Json;
use Kant\Helper\JsExpression;

class TinymceWidget extends InputWidget
{

    public $clientOptions = [];

    public $convention = [
        'plugins' => 'advlist autolink link image lists charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking spellchecker table contextmenu directionality emoticons paste textcolor',
        'toolbar' => 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | image | media | link unlink anchor | print preview code  | forecolor backcolor'
    ];

    public function init()
    {
        parent::init();
        $this->id = $this->options['id'];
    }

    public function run()
    {
        $this->registerClientScript();
        if ($this->hasModel()) {
            return Html::activeTextarea($this->model, $this->attribute, [
                'id' => $this->id
            ]);
        } else {
            return Html::textarea($this->id, $this->value, [
                'id' => $this->id
            ]);
        }
    }

    /**
     * Register client scripts
     */
    protected function registerClientScript()
    {
        $this->formatClientOptions();
        TinymceAsset::register($this->view);
        TinymceLangAsset::register($this->view);
        $clientOptions = Json::encode($this->clientOptions);
        $script = "tinymce.init(" . $clientOptions . ");";
        $this->view->registerJs($script);
    }

    protected function formatClientOptions()
    {
        $this->setFilemanager();
        $this->clientOptions['selector'] = "#" . $this->id;
        $this->clientOptions = array_merge($this->convention, $this->clientOptions);
    }

    protected function setFilemanager()
    {
        if (!empty($this->clientOptions['elfinder'])) {
            $this->convention['plugins'] .= ' elfinder';
            $this->convention['toolbar'] .= ' |filemanager';
            $this->convention['image_advtab'] = true;
            $this->convention['relative_urls'] = false;
            $this->convention['external_filemanager_path'] = $this->clientOptions['elfinder'];
            $this->convention['file_picker_types'] = 'file image media';
            $this->convention['filemanager_title'] = Kant::t('kant', 'File Manager');
            $this->convention['file_picker_callback'] = new JsExpression("function(callback,value,meta){var filetype=meta.filetype;if(filetype==='media'){filetype='audio|video'}var path=this.settings.external_filemanager_path;path=path+'&filter='+filetype;tinymce.activeEditor.windowManager.open({file:path,title:'File Manager',width:900,height:450,resizable:'yes'},{oninsert:function(file,fm){var url,reg,info;url=fm.convAbsUrl(file.url);info=file.name+' ('+fm.formatSize(file.size)+')';if(meta.filetype=='file'){callback(url,{text:info,title:info})}if(meta.filetype=='image'){callback(url,{alt:info})}if(meta.filetype=='media'){callback(url)}}});return false}");
        }
    }
}
