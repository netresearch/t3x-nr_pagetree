<?php
/**
 * See class comment
 *
 * PHP Version 5
 *
 * @category TYPO3
 * @package  Nr_Pagetree
 * @author   Christian Opitz <christian.opitz@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */

namespace Netresearch\NrPagetree;

/**
 * DocumentTemplate class
 *
 * @category TYPO3
 * @package  Nr_Pagetree
 * @author   Christian Opitz <christian.opitz@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */
class DocumentTemplate
{
    public function addAssets($params)
    {
        /* @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
        extract($params);

        $pageRenderer->addJsFile(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('nr_pagetree') . 'Resources/Public/JavaScript/pagetree.js');
    }
}

?>
