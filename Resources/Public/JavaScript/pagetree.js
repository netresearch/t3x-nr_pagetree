Ext.namespace('TYPO3.Components.PageTree.Actions');

if (TYPO3.Components.PageTree.Actions.singleClick) {

    TYPO3.Components.PageTree.Actions.oldSingleClick = TYPO3.Components.PageTree.Actions.singleClick;

    /**
     * Reloads the content frame with the current module and node id
     *
     * @param {Ext.tree.TreeNode} node
     * @param {TYPO3.Components.PageTree.Tree} tree
     * @return {void}
     */
    TYPO3.Components.PageTree.Actions.singleClick = function (node, tree) {
        if (node.attributes.serializeClassName == 'Netresearch\\NrPagetree\\PagetreeNode') {
            node.select();
            var returnUrl = TYPO3.Backend.ContentContainer.src;
            if (returnUrl.indexOf('returnUrl') !== -1) {
                returnUrl = TYPO3.Utility.getParameterFromUrl(returnUrl, 'returnUrl');
            } else {
                returnUrl = encodeURIComponent(returnUrl);
            }
            TYPO3.Backend.ContentContainer.setUrl(
                'alt_doc.php?edit[' + node.attributes.type + '][' + node.attributes.nodeData.id + ']=edit&returnUrl=' + returnUrl
            );
        } else {
            TYPO3.Components.PageTree.Actions.oldSingleClick(node, tree);
        }
    };
}