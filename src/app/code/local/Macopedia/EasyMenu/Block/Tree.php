<?php
class Macopedia_EasyMenu_Block_Tree extends Mage_Core_Block_Template
{
    const REGISTRY_KEY_ACTIVE_ELEMENT = 'find_active_element';

    public function getRootCategories()
    {
        return Mage::getModel('EasyMenu/EasyMenu')->getRootCategories();
    }

    public function getDescendantsCategories($catId)
    {
        return Mage::getModel('EasyMenu/EasyMenu')->getDescendantsCategories($catId);
    }

    public function getChildrenCategories($catId)
    {
        return Mage::getModel('EasyMenu/EasyMenu')->getChildrenCategories($catId);
    }

    private function getLink($type, $value)
    {
//        if ($type == 1)
//            $url = Mage::getModel("catalog/category")->load($value)->getUrl();
//        else
        if ($type == 2) {
            $url = Mage::helper('cms/page')->getPageUrl($value);
        }
        else if($type ==3)
            $url = $value;
        if($url)
            return $url;
        else return false;
    }

    public function renderCategory($category, $admin = true)
    {
        $children = $this->getChildrenCategories($category['id']);
        $html = '';
        if ($admin) {
            $html .= '<li><span id="el-' . $category['id'] . '" onclick="getElement(' . $category['id'] . ',this);">' . $category['name'] . '</span>';
        } else {
            $url = $this->getLink($category['type'], $category['value']);
            if ($url) {

                $html .= '<li';

                if (count($children)) {
                    $html .= ' class="has-sublist"';
                }

                $html .= '>';

                if ($this->isElementActive($url)) {
                    $html .= '<a class="current-page" ';
                } else {
                    $html .= '<a ';
                }

                $html .= ' href="' .$url. '" id="el-' . $category['id'] . '">' . $category['name'] . '</a>';
            }
        }

        if (count($children)) $html .= '<ul id="' . $category['parent'] . '">';
        foreach ($children as $child) {
            $html .= $this->renderCategory($child, $admin);
        }
        if (count($children)) $html .= '</ul>';
        $html .= '</li>';
        return $html;
    }

    /**
     * Check if element in menu is active
     *
     * @param $url
     *
     * @return bool
     */
    public function isElementActive($url)
    {
        if($url === $this->getCurrentUrl()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getCurrentUrl()
    {
        $params = array(
            '_nosid' => true,
            '_current' => true,
            '_use_rewrite' => true,
            '_store_to_url' => true
        );

        return Mage::getUrl('', $params);
    }
}