<?php
/**
 * Extension configuration
 *
 * PHP Version 5
 *
 * @category Netresearch
 * @package  Netresearch\NrcSiamar
 * @author   Christian Opitz <steffen.goede@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/tree/pagetree/class.t3lib_tree_pagetree_dataprovider.php']['postProcessCollections'][] =
        'Netresearch\\NrPagetree\\CollectionProcessor';
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['preHeaderRenderHook'][] =
        'Netresearch\\NrPagetree\\DocumentTemplate->addAssets';
}
?>
