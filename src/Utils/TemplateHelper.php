<?php

namespace ZEDx\Utils;

use File;
use SoapBox\Formatter\Formatter;
use Themes;
use ZEDx\Events\Template\TemplateWasCreated;
use ZEDx\Events\Template\TemplateWillBeCreated;
use ZEDx\Models\Template;

class TemplateHelper
{
    /**
     * Save new templates for theme.
     *
     * @param string $themeName
     *
     * @return Template
     */
    public static function saveTemplates($themeName)
    {
        $additionalPath = 'templates'.DIRECTORY_SEPARATOR.'templates.xml';
        $templateSkeleton = Themes::getFilePath($additionalPath);
        $formatter = Formatter::make(File::get($templateSkeleton), Formatter::XML);
        $templates = $formatter->toArray();
        foreach ($templates['template'] as $template) {
            $identifier = str_slug($template['@attributes']['identifier']);
            $title = $template['@attributes']['title'];
            if (!Template::whereIdentifier($identifier)->whereTheme($themeName)->exists()) {
                self::saveNewTemplate($themeName, $template);
            }
        }
    }

    public static function saveNewTemplate($themeName, $template)
    {
        $identifier = $template['@attributes']['identifier'];
        $title = $template['@attributes']['title'];

        $newTemplate = new Template();
        $newTemplate->identifier = str_slug($identifier);
        $newTemplate->title = $title;
        $newTemplate->theme = $themeName;
        $newTemplate->file = md5($identifier.$title.$themeName);
        $newTemplate->skeleton = json_encode($template);

        event(
            new TemplateWillBeCreated($newTemplate)
        );

        $newTemplate->save();

        self::saveTemplateBlocks($newTemplate);
        \TemplateSkeleton::generateTemplateFile($newTemplate);

        event(
            new TemplateWasCreated($newTemplate)
        );

        return $newTemplate;
    }

    /**
     * Save template blocks.
     *
     * @param Template $template
     *
     * @return void
     */
    public static function saveTemplateBlocks(Template $template)
    {
        $skeleton = Formatter::make($template->skeleton, Formatter::JSON);
        $xmlTemplate = @simplexml_load_string($skeleton->toXml(), 'SimpleXMLElement', LIBXML_NOWARNING);
        $xmlBlocks = $xmlTemplate->xpath('//block');
        if (is_array($xmlBlocks)) {
            foreach ($xmlBlocks as $xmlBlock) {
                self::syncTemplateBlock($template, $xmlBlock);
            }
        }
    }

    /**
     * Synchronize template blocks.
     *
     * @param Template $template
     * @param [type]   $xmlBlock
     *
     * @return void
     */
    protected static function syncTemplateBlock(Template $template, $xmlBlock)
    {
        $blockIdentifier = (string) $xmlBlock->attributes->identifier;
        $blockTitle = (string) $xmlBlock->attributes->title;
        $blockIdentifier = str_slug($blockIdentifier);
        $block = $template->blocks()->whereIdentifier($blockIdentifier)->first();
        if ($block) {
            $block->title = $blockTitle;
            $block->save();
        } else {
            $template->blocks()->create([
                'identifier' => $blockIdentifier,
                'title'      => $blockTitle,
            ]);
        }
    }
}
