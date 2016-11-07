<?php

namespace ZEDx\Utils;

use File;
use SoapBox\Formatter\Formatter;
use Themes;
use ZEDx\Models\Page;
use ZEDx\Models\Template;
use ZEDx\Models\Themepartial;

class TemplateSkeleton
{
    private $blockIdentifier = null;
    private $page = null;
    private $connectionClass = '';
    private $additionalClass = '';
    private $skeleton;
    private $generateFile = false;
    private $xmlTemplate;
    private $type;
    private $blockEdit = false;

    public function renderForPage(Page $page, $blockIdentifier)
    {
        $this->blockEdit = false;
        $this->type = 'page';
        $this->blockIdentifier = str_slug($blockIdentifier);
        $this->page = $page;
        $this->skeleton = $page->template->skeleton;

        return $this->render();
    }

    public function renderForConnecting($skeleton, $additionalClass, $page = false)
    {
        $this->page = $page;
        $this->blockEdit = false;
        $this->type = 'connection';
        $this->blockIdentifier = null;
        $this->connectionClass = 'block-to-connect-'.$additionalClass;
        $this->additionalClass = $additionalClass;
        $this->skeleton = $skeleton;

        return $this->render();
    }

    public function renderForEditing($skeleton, $additionalClass)
    {
        $this->page = null;
        $this->blockIdentifier = null;
        $this->type = 'editing';
        $this->blockEdit = true;
        $this->connectionClass = 'block-to-edit-'.$additionalClass;
        $this->additionalClass = $additionalClass;
        $this->skeleton = $skeleton;

        return $this->render();
    }

    public function generateTemplatesFile()
    {
        $templates = Template::all();
        foreach ($templates as $template) {
            $this->generateTemplateFile($template);
        }
    }

    public function generateTemplateFile(Template $template)
    {
        $this->generateFile = true;
        $this->skeleton = $template->skeleton;
        $this->parse();
        $templateStubPath = Themes::frontend()->getFilePath('templates'.DIRECTORY_SEPARATOR.'template.stub');

        if (File::exists($templateStubPath)) {
            $templateStub = File::get($templateStubPath);
            $templateContent = $this->replaceStubContent($templateStub);
            umask(0);
            File::put(storage_path('app/views/'.$template->file.'.blade.php'), $templateContent);
        }
    }

    protected function replaceStubContent($templateStub)
    {
        $partials = $this->getVariables($templateStub);
        $search = [];
        $replace = [];

        foreach ($partials as $code => $partial) {
            $search[] = $code;
            $replace[] = $partial != '__blocks' ? $this->replaceStubThemePartial($partial) : $this->replaceStubBlocks();
        }

        return str_replace($search, $replace, $templateStub);
    }

    protected function replaceStubThemePartial($partial)
    {
        $themePartialsPath = Themes::getFilePath('templates'.DIRECTORY_SEPARATOR.'partials'.DIRECTORY_SEPARATOR.$partial.'.blade.php');
        if (File::exists($themePartialsPath) && $this->isThemePartial($partial)) {
            $replace = '@if (in_array("'.$partial.'", $__themePartials) && view()->exists("frontend::templates.partials.'.$partial.'"))
      @include("frontend::templates.partials.'.$partial.'")
      @endif
      ';
        } else {
            $replace = '';
        }

        return $replace;
    }

    protected function isThemePartial($partial)
    {
        return Themepartial::whereName($partial)->first();
    }

    protected function replaceStubBlocks()
    {
        $this->parse();

        $template = '';
        if ($this->xmlTemplate) {
            $rows = isset($this->xmlTemplate->row->item) ? $this->xmlTemplate->row->item : [$this->xmlTemplate->row];
            $template = $this->renderRows($rows);
        }

        return $template;
    }

    protected function getVariables($templateStub)
    {
        $partials = [];
        preg_match_all('/{{\\s*\\#[\\w\\.]+\\s*}}/', $templateStub, $matchVariables);
        foreach ($matchVariables[0] as $partial) {
            preg_match('/[\\w\\.]+/', $partial, $matches);
            $partials[$partial] = $matches[0];
        }

        return $partials;
    }

    protected function render()
    {
        $this->parse();

        $template = '';
        if ($this->xmlTemplate) {
            if (!$this->blockEdit) {
                $template = '<div id="page-blocks" class="show-grid">';
            }
            $rows = isset($this->xmlTemplate->row->item) ? $this->xmlTemplate->row->item : [$this->xmlTemplate->row];
            $template .= $this->renderRows($rows);
            if (!$this->blockEdit) {
                $template .= '</div>';
            }
        }

        return $template;
    }

    protected function renderRows($xmlRows)
    {
        if (empty($xmlRows)) {
            return;
        }

        $template = '';
        foreach ($xmlRows as $xmlRow) {
            $cols = isset($xmlRow->col->item) ? $xmlRow->col->item : [$xmlRow->col];
            $templateCols = $this->renderCols($cols);
            $class = str_replace('Empty', '', $xmlRow->attributes->class);

            if ($templateCols) {
                if (!$this->blockEdit) {
                    $template .= '<div class="'.$class.'">';
                } else {
                    $template .= '<div class="row template-editing ui-sortable">
          <div class="template-tools clearfix">
            <a href="javascript:void(0)" title="'.trans('backend.template.move_row').'" class="template-moveRow pull-left"><i class="fa fa-arrows"></i> </a>
            <a href="javascript:void(0)" title="'.trans('backend.template.add_col').'" class="template-addColumn pull-left"><i class="fa fa-plus"></i> </a>
            <span class="pull-right"><span class="label label-success">'.trans('backend.template.class').'</span> <span class="template-element-class">'.$class.'</span></span>
          </div>';
                }
                $template .= $templateCols;
                if ($this->blockEdit) {
                    $template .= '<div class="template-tools clearfix">
            <a href="javascript:void(0)" title="'.trans('backend.template.delete_row').'" class="pull-right template-removeRow">
              <span class="fa fa-trash-o"></span>
            </a>
          </div>';
                }
                $template .= '</div>';
            }
        }

        return $template;
    }

    protected function renderCols($xmlCols)
    {
        if (empty($xmlCols)) {
            return;
        }

        $template = '';
        foreach ($xmlCols as $xmlCol) {
            $class = str_replace('Empty', '', $xmlCol->attributes->class);

            if ($xmlCol->row) {
                $rows = isset($xmlCol->row->item) ? $xmlCol->row->item : [$xmlCol->row];
                $templateRows = $this->renderRows($rows);
                if ($templateRows) {
                    if ($this->blockEdit) {
                        $template .= '
            <div class="col-md-'.$xmlCol->attributes->grid.' column template-editing ui-sortable" data-template-grid="'.$xmlCol->attributes->grid.'">
              <div class="template-tools clearfix">
                <a href="javascript:void(0)" title="'.trans('backend.template.move_col').'" class="template-moveCol pull-left"><i class="fa fa-arrows"></i> </a>
                <a href="javascript:void(0)" title="'.trans('backend.template.decrease_col').'" class="template-colDecrease pull-left"><i class="fa fa-minus"></i> </a>
                <a href="javascript:void(0)" title="'.trans('backend.template.increase_col').'" class="template-colIncrease pull-left"><i class="fa fa-plus"></i></a>
              </div>
            ';
                    } else {
                        $template .= '<div class="col-md-'.$xmlCol->attributes->grid.'" data-template-grid="'.$xmlCol->attributes->grid.'">';
                    }
                    $template .= $templateRows;
                    if ($this->blockEdit) {
                        $template .= '
            <div class="template-tools clearfix">
              <a href="javascript:void(0)" title="'.trans('backend.template.add_row').'" class="pull-left template-addRow"><i class="fa fa-plus-square"></i></a>
              <a href="javascript:void(0)" title="'.trans('backend.template.delete_col').'" class="pull-right template-removeCol"><i class="fa fa-trash-o"></i></a>
            </div>
            ';
                    }
                    $template .= '</div>';
                }
            } else {
                $template .= $this->renderBlock($xmlCol->block, $xmlCol->attributes->grid, $class);
            }
        }

        return $template;
    }

    protected function renderBlock($xmlBlock, $grid, $class)
    {
        if (empty($xmlBlock)) {
            return;
        }

        $template = '';
        $current = $this->blockIdentifier == str_slug($xmlBlock->attributes->identifier) ? 'current' : '';
        $blockId = md5('block-ao-'.str_slug($xmlBlock->attributes->identifier).'-'.$this->additionalClass.'-'.uniqid());

        if ($this->blockEdit) {
            $template .= $this->renderEditBlock($xmlBlock->attributes, $blockId, $grid, $class);

            return $template;
        }

        if ($this->type == 'connection' && $this->page) {
            $current = $this->isBlockInPage(str_slug($xmlBlock->attributes->identifier)) ? 'must-connect' : $current;
        }

        if (!$this->generateFile) {
            $template .= '<div id="'.$blockId.'" data-template-identifier="'.str_slug($xmlBlock->attributes->identifier).'" class="'.$this->connectionClass.' col-md-'.$grid.' block '.$current.'" data-template-grid="'.$grid.'">';
            $template .= '  <div class="template-block-content">';
            if ($this->type == 'page') {
                $template .= '    <a href="'.route('zxadmin.page.edit', [$this->page->id, str_slug($xmlBlock->attributes->identifier)]).'">';
            } else {
                $template .= '    <a href="javascript:void(0)">';
            }
            $template .= '        <div class="wrapper text-center">';
            $template .= '            <h5>'.$xmlBlock->attributes->title.'</h5>';
            $template .= '        </div>';
            $template .= '    </a>';
            $template .= '  </div>';
            $template .= '</div>';

            return $template;
        }

        $template .= '<div id="'.$blockId.'" data-template-identifier="'.str_slug($xmlBlock->attributes->identifier).'" class="'.$this->connectionClass.' col-md-'.$grid.' block '.$current.' '.$class.'" data-template-grid="'.$grid.'">';
        $template .= '  <div class="template-block-content">';
        $template .= "    @block('".str_slug($xmlBlock->attributes->identifier)."')";
        $template .= '  </div>';
        $template .= '</div>';

        return $template;
    }

    protected function isBlockInPage($identifier)
    {
        foreach ($this->page->nodes as $node) {
            if ($identifier == $node->block->identifier) {
                return true;
            }
        }

        return false;
    }

    protected function renderEditBlock($attributes, $blockId, $grid, $class)
    {
        return '
      <div id="'.$blockId.'" class="col-md-'.$grid.' column template-editing ui-sortable" data-template-grid="'.$grid.'">
        <div class="template-tools clearfix">
          <a href="javascript:void(0)" title="'.trans('backend.template.move_col').'" class="template-moveCol pull-left"><i class="fa fa-arrows"></i> </a>
          <a href="javascript:void(0)" title="'.trans('backend.template.decrease_col').'" class="template-colDecrease pull-left"><i class="fa fa-minus"></i> </a>
          <a href="javascript:void(0)" title="'.trans('backend.template.increase_col').'" class="template-colIncrease pull-left"><i class="fa fa-plus"></i></a>
          <span class="pull-right"><span class="label label-danger">'.trans('backend.template.class').'</span> <span class="template-element-class">'.$class.'</span></span>
        </div>
        <div class="template-editable-region" data-template-identifier="'.str_slug($attributes->identifier).'">
          <h4><center><i class="fa fa-edit"></i> <span class="template-block-title">'.$attributes->title.'</span></center></h4>
        </div>
        <div class="template-tools clearfix">
          <a href="javascript:void(0)" title="'.trans('backend.template.add_row').'" class="pull-left template-addRow"><i class="fa fa-plus-square"></i></a>
          <a href="javascript:void(0)" title="'.trans('backend.template.delete_col').'" class="pull-right template-removeCol"><i class="fa fa-trash-o"></i></a>
        </div>
    </div>
    ';
    }

    protected function parse()
    {
        $skeleton = Formatter::make($this->skeleton, Formatter::JSON);
        $this->xmlTemplate = @simplexml_load_string($skeleton->toXml(), 'SimpleXMLElement', LIBXML_NOWARNING);
    }
}
