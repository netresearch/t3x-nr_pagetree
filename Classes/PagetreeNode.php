<?php
/**
 * See class comment
 *
 * PHP Version 5
 *
 * @category   Netresearch
 * @package    ?
 * @subpackage ?
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */

namespace Netresearch\NrPagetree;

/**
 *
 *
 * @category   Netresearch
 * @package    ?
 * @subpackage ?
 * @author     Christian Opitz <christian.opitz@netresearch.de>
 * @license    http://www.netresearch.de Netresearch Copyright
 * @link       http://www.netresearch.de
 */
class PagetreeNode extends \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNode
{
    /**
     * Returns the calculated id representation of this node
     *
     * @param string $prefix Defaults to 'p'
     * @return string
     */
    public function calculateNodeId($prefix = 'p')
    {
        return parent::calculateNodeId($this->type);
    }

    /**
     * Checks if the user has editing rights
     *
     * @return boolean
     */
    protected function canEdit()
    {
        return false;
    }

    /**
     * Checks if the user may create pages below the given page
     *
     * @return boolean
     */
    protected function canCreate()
    {
        return false;
    }

    /**
     * Checks if the user has the right to delete the page
     *
     * @return boolean
     */
    protected function canRemove()
    {
        return false;
    }

    /**
     * Returns the draggable indicator
     *
     * @return boolean
     */
    public function isDraggable()
    {
        return false;
    }

    /**
     * Returns the indicator if the node is a drop target
     *
     * @return boolean
     */
    public function isDropTarget()
    {
        return false;
    }

    /**
     * Checks if the node can have child nodes
     *
     * @return boolean
     */
    public function canHaveChildren()
    {
        return $this->type == 'pages';
    }

    /**
     * Returns the editable label indicator
     *
     * @return boolean
     */
    public function isLabelEditable()
    {
        return $this->type == 'pages';
    }

    /**
     * Returns TRUE if the node is a mount point
     *
     * @return boolean
     */
    public function isMountPoint()
    {
        return false;
    }


}

?>
