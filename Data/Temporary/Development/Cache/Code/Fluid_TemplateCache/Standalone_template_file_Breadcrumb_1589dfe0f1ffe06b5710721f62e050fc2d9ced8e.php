<?php class FluidCache_Standalone_template_file_Breadcrumb_1589dfe0f1ffe06b5710721f62e050fc2d9ced8e extends \TYPO3\Fluid\Core\Compiler\AbstractCompiledTemplate {

public function getVariableContainer() {
	// TODO
	return new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
}
public function getLayoutName(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;

return NULL;
}
public function hasLayout() {
return FALSE;
}

/**
 * Main Render function
 */
public function render(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
$self = $this;
$output0 = '';

$output0 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\CommentViewHelper
$arguments1 = array();
$renderChildrenClosure2 = function() use ($renderingContext, $self) {
return 'This TypoScriptObject is deprecated in favor of TYPO3.Neos:BreadcrumbMenu which offers item states.';
};
$viewHelper3 = $self->getViewHelper('$viewHelper3', $renderingContext, 'TYPO3\Fluid\ViewHelpers\CommentViewHelper');
$viewHelper3->setArguments($arguments1);
$viewHelper3->setRenderingContext($renderingContext);
$viewHelper3->setRenderChildrenClosure($renderChildrenClosure2);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\CommentViewHelper

$output0 .= $viewHelper3->initializeArgumentsAndRender();

$output0 .= '
';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments4 = array();
// Rendering Boolean node
$arguments4['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean(\TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'items', $renderingContext));
$arguments4['then'] = NULL;
$arguments4['else'] = NULL;
$renderChildrenClosure5 = function() use ($renderingContext, $self) {
$output6 = '';

$output6 .= '
	<ul class="breadcrumbs">
		';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\ForViewHelper
$arguments7 = array();
$arguments7['each'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'items', $renderingContext);
$arguments7['as'] = 'item';
// Rendering Boolean node
$arguments7['reverse'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::convertToBoolean('TRUE');
$arguments7['key'] = '';
$arguments7['iteration'] = NULL;
$renderChildrenClosure8 = function() use ($renderingContext, $self) {
$output9 = '';

$output9 .= '
			';
// Rendering ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper
$arguments10 = array();
// Rendering Boolean node
$arguments10['condition'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\BooleanNode::evaluateComparator('==', \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item.hiddenInIndex', $renderingContext), 0);
$arguments10['then'] = NULL;
$arguments10['else'] = NULL;
$renderChildrenClosure11 = function() use ($renderingContext, $self) {
$output12 = '';

$output12 .= '
				<li>
					';
// Rendering ViewHelper TYPO3\Neos\ViewHelpers\Link\NodeViewHelper
$arguments13 = array();
$arguments13['node'] = \TYPO3\Fluid\Core\Parser\SyntaxTree\ObjectAccessorNode::getPropertyPath($renderingContext->getTemplateVariableContainer(), 'item', $renderingContext);
$arguments13['additionalAttributes'] = NULL;
$arguments13['data'] = NULL;
$arguments13['format'] = NULL;
$arguments13['absolute'] = false;
$arguments13['arguments'] = array (
);
$arguments13['section'] = '';
$arguments13['addQueryString'] = false;
$arguments13['argumentsToBeExcludedFromQueryString'] = array (
);
$arguments13['baseNodeName'] = 'documentNode';
$arguments13['nodeVariableName'] = 'linkedNode';
$arguments13['resolveShortcuts'] = true;
$arguments13['class'] = NULL;
$arguments13['dir'] = NULL;
$arguments13['id'] = NULL;
$arguments13['lang'] = NULL;
$arguments13['style'] = NULL;
$arguments13['title'] = NULL;
$arguments13['accesskey'] = NULL;
$arguments13['tabindex'] = NULL;
$arguments13['onclick'] = NULL;
$arguments13['name'] = NULL;
$arguments13['rel'] = NULL;
$arguments13['rev'] = NULL;
$arguments13['target'] = NULL;
$renderChildrenClosure14 = function() use ($renderingContext, $self) {
return NULL;
};
$viewHelper15 = $self->getViewHelper('$viewHelper15', $renderingContext, 'TYPO3\Neos\ViewHelpers\Link\NodeViewHelper');
$viewHelper15->setArguments($arguments13);
$viewHelper15->setRenderingContext($renderingContext);
$viewHelper15->setRenderChildrenClosure($renderChildrenClosure14);
// End of ViewHelper TYPO3\Neos\ViewHelpers\Link\NodeViewHelper

$output12 .= $viewHelper15->initializeArgumentsAndRender();

$output12 .= '
				</li>
			';
return $output12;
};
$viewHelper16 = $self->getViewHelper('$viewHelper16', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper16->setArguments($arguments10);
$viewHelper16->setRenderingContext($renderingContext);
$viewHelper16->setRenderChildrenClosure($renderChildrenClosure11);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output9 .= $viewHelper16->initializeArgumentsAndRender();

$output9 .= '
		';
return $output9;
};

$output6 .= TYPO3\Fluid\ViewHelpers\ForViewHelper::renderStatic($arguments7, $renderChildrenClosure8, $renderingContext);

$output6 .= '
	</ul>
';
return $output6;
};
$viewHelper17 = $self->getViewHelper('$viewHelper17', $renderingContext, 'TYPO3\Fluid\ViewHelpers\IfViewHelper');
$viewHelper17->setArguments($arguments4);
$viewHelper17->setRenderingContext($renderingContext);
$viewHelper17->setRenderChildrenClosure($renderChildrenClosure5);
// End of ViewHelper TYPO3\Fluid\ViewHelpers\IfViewHelper

$output0 .= $viewHelper17->initializeArgumentsAndRender();

$output0 .= '
';

return $output0;
}


}
#0             5751      