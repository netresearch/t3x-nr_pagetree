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

use TYPO3\CMS\Backend\Tree\TreeNode;
use TYPO3\CMS\Backend\Tree\TreeNodeCollection;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\Utility\IconUtility;
use TYPO3\CMS\Core\Tree\TableConfiguration\DatabaseTreeDataProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * CollectionProcessor class
 *
 * @category TYPO3
 * @package  Nr_Pagetree
 * @author   Christian Opitz <christian.opitz@netresearch.de>
 * @license  http://www.netresearch.de Netresearch Copyright
 * @link     http://www.netresearch.de
 */
class CollectionProcessor implements \TYPO3\CMS\Backend\Tree\Pagetree\CollectionProcessorInterface
{
    protected function getTableConfiguration($pageId)
    {
        $pageTsConfig = BackendUtility::getPagesTSconfig($pageId);
        return (array) $pageTsConfig['options.']['pageTree.']['showTables.'];
    }

    /**
     * Post process the subelement collection of a specific node
     *
     * @param \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNode           $node
     * @param integer                                                 $mountPoint
     * @param integer                                                 $level
     * @param \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNodeCollection $nodeCollection
     *
     * @return void
     */
    public function postProcessGetNodes($node, $mountPoint, $level, $nodeCollection)
    {
        foreach ($nodeCollection as $childNode) {
            /* @var \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNode $childNode */
            if ($this->getTableConfiguration($childNode->getId())) {
                $childNode->setLeaf(false);
            }
        }

        $tableConfigurations = array();
        foreach ($this->getTableConfiguration($node->getId()) as $tableWithDot => $conf) {
            $table = rtrim($tableWithDot, '.');
            if ($GLOBALS['TCA'][$table]) {
                $tableConfigurations[$table] = array(
                    'relations' => array(),
                    'where' => $conf['where'] ?: 'pid=' . $node->getId()
                );
                foreach (GeneralUtility::trimExplode(',', $conf['relations']) as $column) {
                    $tableConfigurations[$table]['relations'][$column]
                        = BackendUtility::getTcaFieldConfiguration($table, $column);
                }
            }
        }

        foreach ($tableConfigurations as $table => $conf) {
            $this->addNodesFromTable($nodeCollection, $table, $conf['where'], $tableConfigurations);
        }
    }

    /**
     *
     * @param \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNodeCollection $nodeCollection
     * @param                                                         $table
     * @param                                                         $where
     * @param                                                         $tableConfigurations
     *
     * @return void
     */
    protected function addNodesFromTable($nodeCollection, $table, $where, $tableConfigurations)
    {
        $tca = $GLOBALS['TCA'][$table];
        $nameField = $tca['ctrl']['label'];
        $where = $where . BackendUtility::deleteClause($table);
        $fields = BackendUtility::getCommonSelectFields($table);
        $relations = (array) $tableConfigurations[$table]['relations'];
        foreach ($relations as $column => $config) {
            if ($config) {
                $fields .= ',' . $column;
            }
        }
        $records = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($fields, $table, $where);
        foreach ($records as $record) {
            $subNode = GeneralUtility::makeInstance('Netresearch\\NrPagetree\\PagetreeNode');
            $subNode->setRecord($record);
            $subNode->setType($table);
            $subNode->setId($record['uid']);
            $subNode->setQTip('id='.$record['uid']);
            $subNode->setWorkspaceId($record['_ORIG_uid'] ?: $record['uid']);

            $subNode->setText(htmlspecialchars($record[$nameField]), $nameField);
            $subNode->setSpriteIconCode(IconUtility::getSpriteIconForRecord($table, $record));
            $subNode->setLeaf(true);
            $subNode->setExpandable(false);
            $subNode->setExpanded(false);

            $subNode->setChildNodes(
                $children = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Tree\\Pagetree\\PagetreeNodeCollection')
            );

            $nodeCollection->append($subNode);

            foreach ($relations as $column => $config) {
                if ($config) {
                    $dbGroup = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Database\\RelationHandler');
                    $dbGroup->start($record[$column], $config['type'] === 'group' ? $config['allowed'] : $config['foreign_table'], $config['MM'], $record['uid'], $table, $config);
                    foreach ($dbGroup->tableArray as $relationTable => $ids) {
                        if ($ids) {
                            $this->addNodesFromTable($children, $relationTable, 'uid IN (' . implode(',', $ids) . ')', $tableConfigurations);
                        }
                    }
                } elseif ($table == 'pages' && $column == 'children') {
                    $this->addNodesFromTable($children, $table, "pid = {$record['uid']}", $tableConfigurations);
                }
            }

            if (count($children)) {
                $subNode->setLeaf(false);
                $subNode->setExpandable(true);
            }
        }
    }

    /**
     * Post process the subelement collection of a specific node-filter combination
     *
     * @param \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNode           $node
     * @param string                                                  $searchFilter
     * @param integer                                                 $mountPoint
     * @param \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNodeCollection $nodeCollection
     *
     * @return void
     */
    public function postProcessFilteredNodes($node, $searchFilter, $mountPoint, $nodeCollection)
    {
        // TODO: Implement postProcessFilteredNodes() method.
    }

    /**
     * Post process the collection of tree mounts
     *
     * @param string                                                  $searchFilter
     * @param \TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNodeCollection $nodeCollection
     *
     * @return void
     */
    public function postProcessGetTreeMounts($searchFilter, $nodeCollection)
    {
        // TODO: Implement postProcessGetTreeMounts() method.
    }
}
?>
