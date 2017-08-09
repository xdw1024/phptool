<?php
/**
 * 分页样式
 *
 * cx
 * 20170617
 */

namespace app\admin\driver;

use think\paginator\driver\Bootstrap;

class amazeuiPage extends Bootstrap
{
    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        if ($this->hasPages()) {
            return sprintf(
                '<ul class="am-pagination tpl-pagination">%s %s %s</ul>',
                $this->getPreviousButton(),
                $this->getLinks(),
                $this->getNextButton()
            );
//            if ($this->simple)
//            {
//                return sprintf(
//                    '<ul class="pager">%s %s</ul>',
//                    $this->getPreviousButton(),
//                    $this->getNextButton()
//                );
//            }
//            else
//            {
//                return sprintf(
//                    '<ul class="pagination">%s %s %s %s %s %s</ul>',
//                    $this->getPreviousButton(),
//                    $this->getFirstPage(),
//                    $this->getLinks(),
//                    $this->getLastPage(),
//                    $this->getNextButton(),
//                    $this->getTotal()
//                );
//            }
        }
    }

    /**
     * 页码按钮
     * @return string
     */
    protected function getLinks()
    {
        return parent::getLinks();
    }

    /**
     * 上一页按钮
     * @param string $text
     * @return string
     */
    protected function getPreviousButton($text = "«")
    {
        return parent::getPreviousButton($text);
    }

    /**
     * 下一页按钮
     * @param string $text
     * @return string
     */
    protected function getNextButton($text = '»')
    {
        return parent::getNextButton($text);
    }

    /**
     * 生成一个禁用的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<li class="am-disabled"><a href="#">' . $text . '</a></li>';
    }

    /**
     * 生成一个激活的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<li class="am-active"><a href="#">' . $text . '</a></li>';
    }

    /**
     * 生成一个可点击的按钮
     *
     * @param  string $url
     * @param  int    $page
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page)
    {
        return '<li><a href="javascript:void(0);" onclick="ajaxResult(\'' . htmlentities($url) . '\')">' . $page . '</a></li>';
    }
}